---
title: WP8摄像头API（硬件快门、自动对焦、实时修改捕获视频）
date: 2014-07-21 07:38:22
tags:
- 'C#'
- 'XAML'
- WP8
---

## 1、演示如何响应硬件快门HardwareShutter.xaml

```xml
 <phone:PhoneApplicationPage
     x:Class="Demo.Device.Camera.HardwareShutter"
     xmlns="[http://schemas.microsoft.com/winfx/2006/xaml/presentation](http://schemas.microsoft.com/winfx/2006/xaml/presentation)"
     xmlns:x="[http://schemas.microsoft.com/winfx/2006/xaml](http://schemas.microsoft.com/winfx/2006/xaml)"
     xmlns:phone="clr-namespace:Microsoft.Phone.Controls;assembly=Microsoft.Phone"
     xmlns:shell="clr-namespace:Microsoft.Phone.Shell;assembly=Microsoft.Phone"
     xmlns:d="[http://schemas.microsoft.com/expression/blend/2008](http://schemas.microsoft.com/expression/blend/2008)"
     xmlns:mc="[http://schemas.openxmlformats.org/markup-compatibility/2006](http://schemas.openxmlformats.org/markup-compatibility/2006)"
     FontFamily="{StaticResource PhoneFontFamilyNormal}"
     FontSize="{StaticResource PhoneFontSizeNormal}"
     Foreground="{StaticResource PhoneForegroundBrush}"
     SupportedOrientations="Portrait" Orientation="Portrait"
     mc:Ignorable="d" d:DesignHeight="768" d:DesignWidth="480"
     shell:SystemTray.IsVisible="True">
 
     <Grid x:Name="LayoutRoot" Background="Transparent">
         <StackPanel Orientation="Vertical">

             <Canvas Width="480" Height="320">
                 <Canvas.Background>
                     <VideoBrush x:Name="videoBrush">
                         <VideoBrush.RelativeTransform>
                             <!--把捕获到的图像正过来-->
                             <RotateTransform CenterX="0.5" CenterY="0.5" Angle="90" />
                         </VideoBrush.RelativeTransform>
                     </VideoBrush>
                 </Canvas.Background>
             </Canvas>

             <TextBlock Name="lblMsg" Text="通过按硬件快门来查看演示效果（半按压、全按压、释放）" TextWrapping="Wrap" />

         </StackPanel>
     </Grid>

 </phone:PhoneApplicationPage>
 ```
<!--more-->

HardwareShutter.xaml.cs

```csharp
 /*
  * 演示如何捕获相机的硬件快门的相关事件
  *
  * CameraButtons.ShutterKeyHalfPressed - 硬件快门半按压时所触发的事件
  * CameraButtons.ShutterKeyPressed - 硬件快门全按压时所触发的事件
  * CameraButtons.ShutterKeyReleased - 硬件快门被释放时所触发的事件
  *
  *
  * 注：无论是拍照模式还是摄像模式，只有在摄像头工作起来的时候，系统才能响应硬件快门的相关事件
  */

 using System;
 using System.Collections.Generic;
 using System.Linq;
 using System.Net;
 using System.Windows;
 using System.Windows.Controls;
 using System.Windows.Documents;
 using System.Windows.Input;
 using System.Windows.Media;
 using System.Windows.Media.Animation;
 using System.Windows.Shapes;
 using Microsoft.Phone.Controls;

 using Microsoft.Devices;
 using System.Windows.Navigation;

 namespace Demo.Device.Camera
 {
     public partial class HardwareShutter : PhoneApplicationPage
     {
         private PhotoCamera _camera;

         public HardwareShutter()
         {
             InitializeComponent();
         }

         protected override void OnNavigatedTo(NavigationEventArgs e)
         {
             if (PhotoCamera.IsCameraTypeSupported(CameraType.Primary))
             {
                 _camera = new PhotoCamera(CameraType.Primary);

                 // 注册硬件快门的相关事件
                 CameraButtons.ShutterKeyHalfPressed += CameraButtons_ShutterKeyHalfPressed;
                 CameraButtons.ShutterKeyPressed += CameraButtons_ShutterKeyPressed;
                 CameraButtons.ShutterKeyReleased += CameraButtons_ShutterKeyReleased;

                 // 相机模式下，必须将捕获到的信息输出到 UI 上，系统才能响应硬件快门的事件（同理，摄像模式下，必须调用了 CaptureSource.Start() 之后系统才能响应硬件快门的事件）
                 videoBrush.SetSource(_camera);
             }
         }

         protected override void OnNavigatingFrom(NavigatingCancelEventArgs e)
         {
             // 清理相关资源
             CameraButtons.ShutterKeyHalfPressed -= CameraButtons_ShutterKeyHalfPressed;
             CameraButtons.ShutterKeyPressed -= CameraButtons_ShutterKeyPressed;
             CameraButtons.ShutterKeyReleased -= CameraButtons_ShutterKeyReleased;
         }

         void CameraButtons_ShutterKeyHalfPressed(object sender, EventArgs e)
         {
             lblMsg.Text = "快门半按压";
         }

         void CameraButtons_ShutterKeyPressed(object sender, EventArgs e)
         {
             lblMsg.Text = "快门全按压";
         }

         void CameraButtons_ShutterKeyReleased(object sender, EventArgs e)
         {
             lblMsg.Text = "快门被释放";
         }
     }
 }
 ```

## 2、演示如何自动对焦，以及如何自动对焦到指定的点

Focus.xaml
```xml
 <phone:PhoneApplicationPage
     x:Class="Demo.Device.Camera.Focus"
     xmlns="[http://schemas.microsoft.com/winfx/2006/xaml/presentation](http://schemas.microsoft.com/winfx/2006/xaml/presentation)"
     xmlns:x="[http://schemas.microsoft.com/winfx/2006/xaml](http://schemas.microsoft.com/winfx/2006/xaml)"
     xmlns:phone="clr-namespace:Microsoft.Phone.Controls;assembly=Microsoft.Phone"
     xmlns:shell="clr-namespace:Microsoft.Phone.Shell;assembly=Microsoft.Phone"
     xmlns:d="[http://schemas.microsoft.com/expression/blend/2008](http://schemas.microsoft.com/expression/blend/2008)"
     xmlns:mc="[http://schemas.openxmlformats.org/markup-compatibility/2006](http://schemas.openxmlformats.org/markup-compatibility/2006)"
     FontFamily="{StaticResource PhoneFontFamilyNormal}"
     FontSize="{StaticResource PhoneFontSizeNormal}"
     Foreground="{StaticResource PhoneForegroundBrush}"
     SupportedOrientations="Portrait" Orientation="Portrait"
     mc:Ignorable="d" d:DesignHeight="768" d:DesignWidth="480"
     shell:SystemTray.IsVisible="True">

     <Grid x:Name="LayoutRoot" Background="Transparent">
         <StackPanel Orientation="Vertical">

             <Canvas Name="canvas" Width="480" Height="320" Tap="canvas_Tap">
                 <Canvas.Background>
                     <VideoBrush x:Name="videoBrush">
                         <VideoBrush.RelativeTransform>
                             <!--把捕获到的图像正过来-->
                             <RotateTransform CenterX="0.5" CenterY="0.5" Angle="90" />
                         </VideoBrush.RelativeTransform>
                     </VideoBrush>
                 </Canvas.Background>
             </Canvas>

             <Button Name="btnFocus" Content="自动对焦" Click="btnFocus_Click" />

             <TextBlock Name="lblMsg" />

         </StackPanel>
     </Grid>

 </phone:PhoneApplicationPage>
```

Focus.xaml.cs

```csharp
 /*
  * 演示如何自动对焦，以及如何自动对焦到指定的点
  *
  * PhotoCamera - 用于提供相机功能
  *     Focus() - 让相机自动对焦
  *     FocusAtPoint(double x, double y) - 自动对焦到取景器上指定的点
  *         x, y - 取景器上需要对焦的点的坐标，取景器左上角坐标为 0,0，取景器右下角坐标为 1,1
  *     AutoFocusCompleted - 自动对焦完成后所触发的事件（事件参数为 CameraOperationCompletedEventArgs 类型）
  *
  *
  * CameraOperationCompletedEventArgs
  *     Succeeded - 操作是否成功
  *     Exception - 异常信息
  */

 using System;
 using System.Collections.Generic;
 using System.Linq;
 using System.Net;
 using System.Windows;
 using System.Windows.Controls;
 using System.Windows.Documents;
 using System.Windows.Input;
 using System.Windows.Media;
 using System.Windows.Media.Animation;
 using System.Windows.Shapes;
 using Microsoft.Phone.Controls;

 using Microsoft.Devices;
 using System.Windows.Navigation;

 namespace Demo.Device.Camera
 {
     public partial class Focus : PhoneApplicationPage
     {
         private PhotoCamera _camera;

         public Focus()
         {
             InitializeComponent();
         }

         protected override void OnNavigatedTo(NavigationEventArgs e)
         {
             if (PhotoCamera.IsCameraTypeSupported(CameraType.Primary))
             {
                 // 实例化 PhotoCamera，注册相关事件
                 _camera = new PhotoCamera(CameraType.Primary);
                 _camera.AutoFocusCompleted += _camera_AutoFocusCompleted;

                 // 在 VideoBrush 上显示摄像头捕获到的实时信息
                 videoBrush.SetSource(_camera);
             }
         }

         protected override void OnNavigatingFrom(NavigatingCancelEventArgs e)
         {
             // 清理相关资源
             _camera.AutoFocusCompleted -= _camera_AutoFocusCompleted;
         }

         void _camera_AutoFocusCompleted(object sender, CameraOperationCompletedEventArgs e)
         {
             if (e.Succeeded)
             {
                 Deployment.Current.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "自动对焦完成";
                 });
             }
             else
             {
                 Deployment.Current.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "自动对焦失败";
                 });
             }
         }

         private void btnFocus_Click(object sender, RoutedEventArgs e)
         {
             if (_camera.IsFocusSupported == true)
             {
                 try
                 {
                     // 开始自动对焦
                     _camera.Focus();
                     lblMsg.Text = "开始自动对焦";
                 }
                 catch (Exception ex)
                 {
                     this.Dispatcher.BeginInvoke(delegate()
                     {
                         lblMsg.Text = "自动对焦失败：" + ex.ToString();
                     });
                 }
             }
             else
             {
      this.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "相机不支持自动对焦";
                 });
             }
         }

         private void canvas_Tap(object sender, System.Windows.Input.GestureEventArgs e)
         {
             if (_camera != null)
             {
                 if (_camera.IsFocusAtPointSupported == true)
                 {
                     try
                     {
                         // 获取用户触摸的点相对于 canvas 的坐标
                         Point tapLocation = e.GetPosition(canvas);

                         // 计算触摸点映射于取景器上的坐标（取景器左上角为0,0，右下角为1,1）
                         double focusXPercent = tapLocation.X / canvas.Width;
                         double focusYPercent = tapLocation.Y / canvas.Height;

                         // 自动对焦到指定的点
                         _camera.FocusAtPoint(focusXPercent, focusYPercent);

                         this.Dispatcher.BeginInvoke(delegate()
                         {
                             lblMsg.Text = String.Format("自动对焦到指定的点{0}X：{1:N2}{2}Y：{3:N2}", System.Environment.NewLine, focusXPercent, System.Environment.NewLine, focusYPercent);
                         });
                     }
                     catch (Exception ex)
                     {
                         this.Dispatcher.BeginInvoke(delegate()
                         {
                             lblMsg.Text = "自动对焦到指定的点失败：" + ex.ToString();
                         });
                     }
                 }
                 else
                 {
                     this.Dispatcher.BeginInvoke(delegate()
                     {
                         lblMsg.Text = "相机不支持自动对焦到指定的点";
                     });
                 }
             }
         }
     }
 }
```

## 3、演示如何实时修改捕获到的视频帧

LiveAlter.xaml
```xml
 <phone:PhoneApplicationPage
     x:Class="Demo.Device.Camera.LiveAlter"
     xmlns="[http://schemas.microsoft.com/winfx/2006/xaml/presentation](http://schemas.microsoft.com/winfx/2006/xaml/presentation)"
     xmlns:x="[http://schemas.microsoft.com/winfx/2006/xaml](http://schemas.microsoft.com/winfx/2006/xaml)"
     xmlns:phone="clr-namespace:Microsoft.Phone.Controls;assembly=Microsoft.Phone"
     xmlns:shell="clr-namespace:Microsoft.Phone.Shell;assembly=Microsoft.Phone"
     xmlns:d="[http://schemas.microsoft.com/expression/blend/2008](http://schemas.microsoft.com/expression/blend/2008)"
     xmlns:mc="[http://schemas.openxmlformats.org/markup-compatibility/2006](http://schemas.openxmlformats.org/markup-compatibility/2006)"
     FontFamily="{StaticResource PhoneFontFamilyNormal}"
     FontSize="{StaticResource PhoneFontSizeNormal}"
     Foreground="{StaticResource PhoneForegroundBrush}"
     SupportedOrientations="Landscape" Orientation="Landscape"
     mc:Ignorable="d" d:DesignHeight="480" d:DesignWidth="728"
     shell:SystemTray.IsVisible="True">

     <Grid x:Name="LayoutRoot" Background="Transparent">
         <StackPanel Orientation="Vertical">

             <Grid Width="480" Height="320" HorizontalAlignment="Left">
                 <Canvas Visibility="Collapsed">
                     <Canvas.Background>
                         <VideoBrush x:Name="videoBrush" />
                     </Canvas.Background>
                 </Canvas>

                 <!--用于显示经过处理后的实时画面-->
                 <Image x:Name="imgEffect" HorizontalAlignment="Left" />
             </Grid>

             <TextBlock Name="lblMsg" />

         </StackPanel>
     </Grid>

 </phone:PhoneApplicationPage>
```

LiveAlter.xaml.cs

```csharp
 /*
  * 演示如何实时处理摄像头捕获到的图像
  *
  * PhotoCamera - 用于提供相机功能
  *     PreviewResolution - 捕获到的图像的当前的分辨率（返回 System.Windows.Size 类型的结构体，其包含 Width 和 Height 字段）
  *     GetPreviewBufferArgb32(int[] pixelData) - 将当前捕获到的图像的 ARGB 数据复制到指定的缓冲区中
  *
  *
  * 注：
  * Resolution 指的是相机设置的分辨率
  * PreviewResolution 指的是系统针对显示设备缩放后的真实分辨率
  * 因为通常相机能够拍摄大于设备显示器的分辨率的图像，所以实时显示摄像头捕获到的图像时，系统会对其分辨率进行优化，PreviewResolution 就是优化后的数据
  */

 using System;
 using System.Collections.Generic;
 using System.Linq;
 using System.Net;
 using System.Windows;
 using System.Windows.Controls;
 using System.Windows.Documents;
 using System.Windows.Input;
 using System.Windows.Media;
 using System.Windows.Media.Animation;
 using System.Windows.Shapes;
 using Microsoft.Phone.Controls;

 using Microsoft.Devices;
 using System.Threading;
 using System.Windows.Media.Imaging;
 using System.Windows.Navigation;

 namespace Demo.Device.Camera
 {
     public partial class LiveAlter : PhoneApplicationPage
     {
         private PhotoCamera _camera = new PhotoCamera();

         // 用于显示处理后的图像
         private WriteableBitmap _writeableBitmap;
         // 有信号
         private static ManualResetEvent _manualReset = new ManualResetEvent(true);

         public LiveAlter()
         {
             InitializeComponent();
         }

         protected override void OnNavigatedTo(NavigationEventArgs e)
         {
             if (PhotoCamera.IsCameraTypeSupported(CameraType.Primary))
             {
                 // 实例化 PhotoCamera，并注册相关事件
                 _camera = new PhotoCamera(CameraType.Primary);
                 _camera.Initialized += _camera_Initialized;

                 videoBrush.SetSource(_camera);
             }
             else
             {
                 this.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "设备不支持主摄像头";
                 });
             }
         }

         protected override void OnNavigatingFrom(NavigatingCancelEventArgs e)
         {
          // 清理资源
             if (_camera != null)
             {
                 _camera.Dispose();
                 _camera.Initialized -= _camera_Initialized;
             }
         }

         void _camera_Initialized(object sender, CameraOperationCompletedEventArgs e)
         {
             // 新开线程去执行实时处理图片的任务
             Thread thread = new Thread(CameraToGray);
             thread.Start();

             this.Dispatcher.BeginInvoke(delegate()
             {
                 // 让 Image 显示 WriteableBitmap 中的内容
                 _writeableBitmap = new WriteableBitmap((int)_camera.PreviewResolution.Width, (int)_camera.PreviewResolution.Height);
                 imgEffect.Source = _writeableBitmap;
             });
         }

         private void CameraToGray()
         {
             // 初始化缓冲区大小：图像宽和高的乘积
             int[] buffer = new int[(int)_camera.PreviewResolution.Width * (int)_camera.PreviewResolution.Height];

             try
             {
                 while (true)
                 {
                     // 实例化 ManualResetEvent 的时候，指定了其是有信号的
                     _manualReset.WaitOne(); // 有信号则不阻塞，无信号则阻塞

                     // 将当前捕获到的图像以 ARGB 的方式写入到缓冲区
                     _camera.GetPreviewBufferArgb32(buffer);

                     // 将缓冲区中的每一个像素的颜色都转换为灰色系
                     for (int i = 0; i < buffer.Length; i++)
                     {
                         buffer[i] = ColorToGray(buffer[i]);
                     }

                     _manualReset.Reset(); // 设置为无信号

                     Deployment.Current.Dispatcher.BeginInvoke(delegate()
                     {
                         // 将处理后的图像数据保存到 WriteableBitmap 对象
                         buffer.CopyTo(_writeableBitmap.Pixels, 0);
                       // 重新绘制整个 WriteableBitmap 对象
                         _writeableBitmap.Invalidate();

                         lblMsg.Text = "图像实时处理中";

                         _manualReset.Set();  // 设置为有信号
                     });
                 }

             }
             catch (Exception ex)
             {
                 this.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "图像处理失败：" + ex.ToString();
                 });
            }
         }

         // 将指定的颜色转换成灰色系的颜色
         private int ColorToGray(int color)
         {
             int gray = 0;

             int a = color > 24;
             int r = (color &amp; 0x00ff0000) > 16;
             int g = (color &amp; 0x0000ff00) > 8;
             int b = (color &amp; 0x000000ff);

             if ((r == g) &amp;&amp; (g == b))
             {
                 gray = color;
             }
             else
             {
                 int i = (7 * r + 38 * g + 19 * b + 32) > 6;

                 gray = ((a &amp; 0xFF) < 24) | ((i &amp; 0xFF) < 16) | ((i &amp; 0xFF) < 8) | (i &amp; 0xFF);
             }

             return gray;
         }
     }
 }
 ```