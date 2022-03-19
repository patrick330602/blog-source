---
title: Windows Phone 8 開發：應用崩潰時給作者傳送錯誤報告郵件
date: 2014/10/15 08:00:00
tags:
- C#
- WP8
lang: zh
---

我們的應用難免會爆，但是手工在各個方法上加try…catch…未必能cover到所有場景，有時候我們不希望應用吞掉錯誤，發生嚴重異常時，我們應當允許應用在使用者面前爆掉。如果沒有處理這些異常，應用程式的表現就是閃退。比較好的做法是在應用崩潰時給使用者一個選擇是否傳送郵件告訴應用作者崩潰的詳細日誌。對於作者來說，等待DevCenter的異常報告相對而言比較被動。如果能在應用崩潰之後立即得到回饋那就可以儘早修復問題。
<!--more-->
做法很簡單，和ASP.NET網站在Global.asax中使用的全局錯誤處理類似，WP應用也有個全局錯誤處理的事件。

開啟App.xaml.cs，定位到`Application_UnhandledException(object sender, ApplicationUnhandledExceptionEventArgs e)`事件處理函數上。


 <!--more-->

當任何未處理的異常產生時，這個函數都會被call到。我們的任務是傳送郵件給應用作者（通常就是你自己），所以我們可以把項目模板中預設的程式碼給去掉，刪除這些程式碼：


```csharp
if
 (Debugger.IsAttached)
{

    // An unhandled exception has occurred; break into the debugger
    Debugger.Break();
}
```

然後換成我們自己程式碼：

```csharp
var result = MessageBox.Show("程式不小心爆掉了。點選確定傳送錯誤詳情給軟體作者以幫助改進軟體。",  "註定孤獨一生",MessageBoxButton.OKCancel);
if
 (result == MessageBoxResult.OK)
{
    var msg = e.ExceptionObject.Message;
    while(null != e.ExceptionObject.InnerException)
    {
        e.ExceptionObject = e.ExceptionObject.InnerException;
        msg += (e.ExceptionObject.Message + Environment.NewLine);

}
    var emailComposeTask = new EmailComposeTask
    {
        Subject = string.Format("《你的應用名稱》應用異常報告"),
        Body = "Message: " + msg,
      To ="你的Email地址"
    };
    emailComposeTask.Show();
}
e.Handled = true;
```

程式碼比較直接，首先，用MessageBox給使用者彈了個框，讓使用者自己選擇是否傳送錯誤報告。如果使用者選擇了“確定”，就開始收集異常詳情。ApplicationUnhandledExceptionEventArgs這個參數的ExceptionObject類型就是System.Exception，所以獲取最上層的異常訊息做法就像平時try…catch裡通常寫的ex.Message類似，可以通過Exception物件Message屬性獲得
e.ExceptionObject.Message。

然而，異常並不通常只有一層，有時候我們會遇到exception裡還包著一個innerexception的情況，並且我們不知道是不是包了兩層就結束了，有可能innerexception裡還有innerexception，所以就得寫個迴圈來遍歷直到最底層的innerexception，於是就有了這段程式碼：

```csharp
while
(null != e.ExceptionObject.InnerException)
{
    e.ExceptionObject = e.ExceptionObject.InnerException;
    msg += (e.ExceptionObject.Message + Environment.NewLine);
}
```

其中，“Environment.NewLine”的意思是換行，目的是提高郵件的可閱讀性。

收集完異常資訊後，我們要呼叫WP系統提供的傳送郵件的task來傳送異常報告。我們的應用呼叫系統任務，比如發郵件、打電話、發簡訊都是通過Task來完成的，email的用法在MSDN上可以找到：<http://msdn.microsoft.com/en-US/library/windowsphone/develop/hh394003%28v=vs.105%29.aspx> 這個網頁裡你也能找到其他task的用法。

![](https://cdn.patrickwu.space/posts/dev/wp/wp8-crash-mail.png)

Subject是郵件的主題，Body是正文，To是收件人。當然，如果你需要，你還可以增加Bcc(密件抄送)和Cc(抄送)。

轉自：<http://diaosbook.com/Post/2014/3/23/send-error-report-windows-phone-8>

