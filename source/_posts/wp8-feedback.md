---
title: Windows Phone 8 开发：应用崩溃时给作者发送错误报告邮件
date: 2014/10/15 08:00:00
tags:
- C#
- WP8
thumbnail: /images/wp8-crash-mail.png
---

我们的应用难免会爆，但是手工在各个方法上加try…catch…未必能cover到所有场景，有时候我们不希望应用吞掉错误，发生严重异常时，我们应当允许应用在用户面前爆掉。如果没有处理这些异常，应用程序的表现就是闪退。比较好的做法是在应用崩溃时给用户一个选择是否发送邮件告诉应用作者崩溃的详细日志。对于作者来说，等待DevCenter的异常报告相对而言比较被动。如果能在应用崩溃之后立即得到回馈那就可以尽早修复问题。
<!--more-->
做法很简单，和ASP.NET网站在Global.asax中使用的全局错误处理类似，WP应用也有个全局错误处理的事件。

打开App.xaml.cs，定位到`Application_UnhandledException(object sender, ApplicationUnhandledExceptionEventArgs e)`事件处理函数上。


 <!--more-->

当任何未处理的异常产生时，这个函数都会被call到。我们的任务是发送邮件给应用作者（通常就是你自己），所以我们可以把项目模板中默认的代码给去掉，删除这些代码：


```csharp
if
 (Debugger.IsAttached)
{

    // An unhandled exception has occurred; break into the debugger
    Debugger.Break();
}
```

然后换成我们自己代码：

```csharp
var result = MessageBox.Show("程序不小心爆掉了。点击确定发送错误详情给软件作者以帮助改进软件。",  "注定孤独一生",MessageBoxButton.OKCancel);
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
        Subject = string.Format("《你的应用名称》应用异常报告"),
        Body = "Message: " + msg,
      To ="你的Email地址"
    };
    emailComposeTask.Show();
}
e.Handled = true;
```

代码比较直接，首先，用MessageBox给用户弹了个框，让用户自己选择是否发送错误报告。如果用户选择了“确定”，就开始收集异常详情。ApplicationUnhandledExceptionEventArgs这个参数的ExceptionObject类型就是System.Exception，所以获取最上层的异常消息做法就像平时try…catch里通常写的ex.Message类似，可以通过Exception对象Message属性获得
e.ExceptionObject.Message。

然而，异常并不通常只有一层，有时候我们会遇到exception里还包着一个innerexception的情况，并且我们不知道是不是包了两层就结束了，有可能innerexception里还有innerexception，所以就得写个循环来遍历直到最底层的innerexception，于是就有了这段代码：

```csharp
while
(null != e.ExceptionObject.InnerException)
{
    e.ExceptionObject = e.ExceptionObject.InnerException;
    msg += (e.ExceptionObject.Message + Environment.NewLine);
}
```

其中，“Environment.NewLine”的意思是换行，目的是提高邮件的可阅读性。

收集完异常信息后，我们要调用WP系统提供的发送邮件的task来发送异常报告。我们的应用调用系统任务，比如发邮件、打电话、发短信都是通过Task来完成的，email的用法在MSDN上可以找到：<http://msdn.microsoft.com/en-US/library/windowsphone/develop/hh394003%28v=vs.105%29.aspx> 这个网页里你也能找到其他task的用法。

![](https://cdn.patrickwu.space/posts/dev/wp/wp8-crash-mail.png)

Subject是邮件的主题，Body是正文，To是收件人。当然，如果你需要，你还可以增加Bcc(密件抄送)和Cc(抄送)。

转自：<http://diaosbook.com/Post/2014/3/23/send-error-report-windows-phone-8>

