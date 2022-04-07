---
title: Window Phone 8 手電筒程式碼
tags:
  - 'C#'
  - WP8
date: 2014-07-06 11:16:28
lang: zh
---
我就想開發一個鎖屏也能用的手電筒，發現找資料那是相當的困難。找到的程式碼基本都不能令人滿意，有的需要使用照相機，有的需要使用錄影機，感覺都不是很爽。
最後經過摸索，Ui介面只要一個按鈕，就可以實現手電筒的開啟和關閉，而且是常亮的，廢話不多說了，上程式碼：<!--more-->
Ui介面：
一個按鈕就Ok了：

```xaml
<Button Name="btnFlash" Content="閃光燈" Height="200" Click="btnFlash_Click_1"/>
```
後臺頁面程式碼：


```csharp
private bool Flag = false;//閃關燈是否開啟
AudioVideoCaptureDevice avDevice = null;
// 建構函式
public MainPage()
{
InitializeComponent();
}
private async void btnFlash_Click_1(object sender, RoutedEventArgs e)//一定不要忘記加async關鍵字（因為下面的程式碼裡面用到了await關鍵字）
{
if (Flag)
{
//閃光燈已開啟 則釋放資源(關閉閃光燈)
if (avDevice != null)
{
avDevice.Dispose();
Flag = false;
}
}
else
{
var sensorLocation = CameraSensorLocation.Back;
avDevice = await AudioVideoCaptureDevice.OpenAsync(sensorLocation, AudioVideoCaptureDevice.GetAvailableCaptureResolutions(sensorLocation).First());
//開啟閃關燈
var supportedCameraModes = AudioVideoCaptureDevice
.GetSupportedPropertyValues(sensorLocation, KnownCameraAudioVideoProperties.VideoTorchMode);
if (supportedCameraModes.ToList().Contains((UInt32)VideoTorchMode.On))
{
avDevice.SetProperty(KnownCameraAudioVideoProperties.VideoTorchMode, VideoTorchMode.On);
Flag = true;
}
}
}
```

還有一個特別需要注意的地方：
一定不要忘記在WMAppManifest.xml裡面加上這兩個功能：

```xml
<Capability Name="ID_CAP_ISV_CAMERA" >
<Capability Name="ID_CAP_MICROPHONE" >
```
