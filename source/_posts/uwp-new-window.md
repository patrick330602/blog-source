---
title: How to create a new window in UWP
date: 2016/2/7 08:00:00
tags:
- C#
- UWP
---

Some friends of mine asked me how to create a window in UWP just like WinForms or UWP, to make their app more desktop-like. Actually, it is pretty simple.

 <!--more-->

![new window in UWP](https://cdn.patrickwu.space/posts/dev/uwp-new-window.png)

I cannot say this is difficult or simple. In order to open a new window, You need to use CoreApplication.CreateNewView() to generate the Window, or view:


```csharp
var currentAV = ApplicationView.GetForCurrentView();

await newAV.Dispatcher.RunAsync(
 CoreDispatcherPriority.Normal,
 async () =>
 {
 var newWindow = Window.Current;
 var newAppView = ApplicationView.GetForCurrentView();newAppView.Title = title;  //The title of new windowvar frame = new Frame();
 frame.Navigate(typeof(Page), Datatosend); //Navigation is here
 newWindow.Content = frame;
 newWindow.Activate();await ApplicationViewSwitcher.TryShowAsStandaloneAsync(
newAppView.Id,
 ViewSizePreference.UseMinimum,
currentAV.Id,
 ViewSizePreference.UseMinimum);
 });
```