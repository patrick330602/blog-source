---
title: Window Phone 8手电筒代码
tags:
  - 'C#'
  - WP8
date: 2014-07-06 11:16:28
---
我就想开发一个锁屏也能用的手电筒，发现找资料那是相当的困难。找到的代码基本都不能令人满意，有的需要使用照相机，有的需要使用录像机，感觉都不是很爽。
最后经过摸索，Ui界面只要一个按钮，就可以实现手电筒的开启和关闭，而且是常亮的，废话不多说了，上代码：<!--more-->
Ui界面：
一个按钮就Ok了：

```xaml
<Button Name="btnFlash" Content="闪光灯" Height="200" Click="btnFlash_Click_1"/>
```
后台页面代码：


```csharp
private bool Flag = false;//闪关灯是否开启
AudioVideoCaptureDevice avDevice = null;
// 构造函数
public MainPage()
{
InitializeComponent();
}
private async void btnFlash_Click_1(object sender, RoutedEventArgs e)//一定不要忘记加async关键字（因为下面的代码里面用到了await关键字）
{
if (Flag)
{
//闪光灯已开启 则释放资源(关闭闪光灯)
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
//打开闪关灯
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

还有一个特别需要注意的地方：
一定不要忘记在WMAppManifest.xml里面加上这两个功能：

```xml
<Capability Name="ID_CAP_ISV_CAMERA" >
<Capability Name="ID_CAP_MICROPHONE" >
```
