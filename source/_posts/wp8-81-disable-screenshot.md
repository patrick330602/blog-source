---
title: Windows Phone 8 APP中禁用截圖
tags:
- C#
- WP8
- WP8.1
date: 2014-10-15 22:39:45
lang: zh
---

Windows Phone 8 有系統自帶的截圖功能，快捷鍵：電源鍵+Win鍵，可以隨意截圖。

Windows Phone 更新GDR2後新增了一個隱藏功能，允許APP禁用截圖功能。
`PhoneApplicationPage.IsScreenCaptureEnabled`這個隱藏的屬性需要通過反射來訪問和修改狀態。
<!--more-->
```csharp
public static class PhoneApplicationPageExtensionMethods
    {
　　　　public static bool CanSetScreenCaptureEnabled(this PhoneApplicationPage page)
        {
    return Environment.OSVersion.Version &gt;= new Version(8, 0, 10322);
        }
        public static void SetScreenCaptureEnabled(this PhoneApplicationPage page, bool enabled)
        {
    var propertyInfo = typeof(PhoneApplicationPage).GetProperty("IsScreenCaptureEnabled");
    if (propertyInfo == null)
    {
        throw new NotSupportedException("Not supported in this Windows Phone version!");
    }
    propertyInfo.SetValue(page, enabled);
        }
        public static bool GetScreenCaptureEnabled(this PhoneApplicationPage page)
        {
    var propertyInfo = typeof(PhoneApplicationPage).GetProperty("IsScreenCaptureEnabled");
    if (propertyInfo == null)
    {
        throw new NotSupportedException("Not supported in this Windows Phone version!");
    }
    return (bool)propertyInfo.GetValue(page);
        }
    }
}
```
呼叫`CanSetScreenCaptureEnabled()`方法檢測Windows Phone版本是否符合要求（version 8.0.10322以上）。符合條件，然後就通過擴充套件方法`GetScreenCaptureEnabled()`和`SetScreenCaptureEnabled()`來修改`PhoneApplicationPage.IsScreenCaptureEnabled`屬性。
使用：

```csharp
// 建構函式
public MainPage()
{
    InitializeComponent();
    if (this.CanSetScreenCaptureEnabled())
    {
        this.SetScreenCaptureEnabled(false);
    }
}
```
![效果](https://cdn.patrickwu.space/posts/dev/wp/stop-wp8-screenshot.jpg)