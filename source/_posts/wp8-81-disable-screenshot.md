---
title: Windows Phone 8 APP中禁用截图
tags:
- C#
- WP8
- WP8.1
date: 2014-10-15 22:39:45
---

Windows Phone 8 有系统自带的截图功能，快捷键：电源键+Win键，可以随意截图。

Windows Phone 更新GDR2后新增了一个隐藏功能，允许APP禁用截图功能。
`PhoneApplicationPage.IsScreenCaptureEnabled`这个隐藏的属性需要通过反射来访问和修改状态。
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
调用`CanSetScreenCaptureEnabled()`方法检测Windows Phone版本是否符合要求（version 8.0.10322以上）。符合条件，然后就通过扩展方法`GetScreenCaptureEnabled()`和`SetScreenCaptureEnabled()`来修改`PhoneApplicationPage.IsScreenCaptureEnabled`属性。
使用：

```csharp
// 构造函数
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