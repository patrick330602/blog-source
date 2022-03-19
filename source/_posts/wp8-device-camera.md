---
title: WP8攝像頭API（硬體快門、自動對焦、實時修改捕獲視訊）
date: 2014-07-21 07:38:22
tags:
- 'C#'
- 'XAML'
- WP8
lang: zh
---

## 1、演示如何響應硬體快門HardwareShutter.xaml

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
                             <!--把捕獲到的影象正過來-->
                             <RotateTransform CenterX="0.5" CenterY="0.5" Angle="90" />
                         </VideoBrush.RelativeTransform>
                     </VideoBrush>
                 </Canvas.Background>
             </Canvas>

             <TextBlock Name="lblMsg" Text="通過按硬體快門來檢視演示效果（半按壓、全按壓、釋放）" TextWrapping="Wrap" />

         </StackPanel>
     </Grid>

 </phone:PhoneApplicationPage>
 ```
<!--more-->

HardwareShutter.xaml.cs

```csharp
 /*
  * 演示如何捕獲相機的硬體快門的相關事件
  *
  * CameraButtons.ShutterKeyHalfPressed - 硬體快門半按壓時所觸發的事件
  * CameraButtons.ShutterKeyPressed - 硬體快門全按壓時所觸發的事件
  * CameraButtons.ShutterKeyReleased - 硬體快門被釋放時所觸發的事件
  *
  *
  * 注：無論是拍照模式還是攝像模式，只有在攝像頭工作起來的時候，系統才能響應硬體快門的相關事件
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

                 // 註冊硬體快門的相關事件
                 CameraButtons.ShutterKeyHalfPressed += CameraButtons_ShutterKeyHalfPressed;
                 CameraButtons.ShutterKeyPressed += CameraButtons_ShutterKeyPressed;
                 CameraButtons.ShutterKeyReleased += CameraButtons_ShutterKeyReleased;

                 // 相機模式下，必須將捕獲到的資訊輸出到 UI 上，系統才能響應硬體快門的事件（同理，攝像模式下，必須呼叫了 CaptureSource.Start() 之後系統才能響應硬體快門的事件）
                 videoBrush.SetSource(_camera);
             }
         }

         protected override void OnNavigatingFrom(NavigatingCancelEventArgs e)
         {
             // 清理相關資源
             CameraButtons.ShutterKeyHalfPressed -= CameraButtons_ShutterKeyHalfPressed;
             CameraButtons.ShutterKeyPressed -= CameraButtons_ShutterKeyPressed;
             CameraButtons.ShutterKeyReleased -= CameraButtons_ShutterKeyReleased;
         }

         void CameraButtons_ShutterKeyHalfPressed(object sender, EventArgs e)
         {
             lblMsg.Text = "快門半按壓";
         }

         void CameraButtons_ShutterKeyPressed(object sender, EventArgs e)
         {
             lblMsg.Text = "快門全按壓";
         }

         void CameraButtons_ShutterKeyReleased(object sender, EventArgs e)
         {
             lblMsg.Text = "快門被釋放";
         }
     }
 }
 ```

## 2、演示如何自動對焦，以及如何自動對焦到指定的點

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
                             <!--把捕獲到的影象正過來-->
                             <RotateTransform CenterX="0.5" CenterY="0.5" Angle="90" />
                         </VideoBrush.RelativeTransform>
                     </VideoBrush>
                 </Canvas.Background>
             </Canvas>

             <Button Name="btnFocus" Content="自動對焦" Click="btnFocus_Click" />

             <TextBlock Name="lblMsg" />

         </StackPanel>
     </Grid>

 </phone:PhoneApplicationPage>
```

Focus.xaml.cs

```csharp
 /*
  * 演示如何自動對焦，以及如何自動對焦到指定的點
  *
  * PhotoCamera - 用於提供相機功能
  *     Focus() - 讓相機自動對焦
  *     FocusAtPoint(double x, double y) - 自動對焦到取景器上指定的點
  *         x, y - 取景器上需要對焦的點的座標，取景器左上角座標為 0,0，取景器右下角座標為 1,1
  *     AutoFocusCompleted - 自動對焦完成後所觸發的事件（事件參數為 CameraOperationCompletedEventArgs 類型）
  *
  *
  * CameraOperationCompletedEventArgs
  *     Succeeded - 操作是否成功
  *     Exception - 異常資訊
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
                 // 例項化 PhotoCamera，註冊相關事件
                 _camera = new PhotoCamera(CameraType.Primary);
                 _camera.AutoFocusCompleted += _camera_AutoFocusCompleted;

                 // 在 VideoBrush 上顯示攝像頭捕獲到的實時資訊
                 videoBrush.SetSource(_camera);
             }
         }

         protected override void OnNavigatingFrom(NavigatingCancelEventArgs e)
         {
             // 清理相關資源
             _camera.AutoFocusCompleted -= _camera_AutoFocusCompleted;
         }

         void _camera_AutoFocusCompleted(object sender, CameraOperationCompletedEventArgs e)
         {
             if (e.Succeeded)
             {
                 Deployment.Current.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "自動對焦完成";
                 });
             }
             else
             {
                 Deployment.Current.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "自動對焦失敗";
                 });
             }
         }

         private void btnFocus_Click(object sender, RoutedEventArgs e)
         {
             if (_camera.IsFocusSupported == true)
             {
                 try
                 {
                     // 開始自動對焦
                     _camera.Focus();
                     lblMsg.Text = "開始自動對焦";
                 }
                 catch (Exception ex)
                 {
                     this.Dispatcher.BeginInvoke(delegate()
                     {
                         lblMsg.Text = "自動對焦失敗：" + ex.ToString();
                     });
                 }
             }
             else
             {
      this.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "相機不支援自動對焦";
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
                         // 獲取使用者觸控的點相對於 canvas 的座標
                         Point tapLocation = e.GetPosition(canvas);

                         // 計算觸控點對映於取景器上的座標（取景器左上角為0,0，右下角為1,1）
                         double focusXPercent = tapLocation.X / canvas.Width;
                         double focusYPercent = tapLocation.Y / canvas.Height;

                         // 自動對焦到指定的點
                         _camera.FocusAtPoint(focusXPercent, focusYPercent);

                         this.Dispatcher.BeginInvoke(delegate()
                         {
                             lblMsg.Text = String.Format("自動對焦到指定的點{0}X：{1:N2}{2}Y：{3:N2}", System.Environment.NewLine, focusXPercent, System.Environment.NewLine, focusYPercent);
                         });
                     }
                     catch (Exception ex)
                     {
                         this.Dispatcher.BeginInvoke(delegate()
                         {
                             lblMsg.Text = "自動對焦到指定的點失敗：" + ex.ToString();
                         });
                     }
                 }
                 else
                 {
                     this.Dispatcher.BeginInvoke(delegate()
                     {
                         lblMsg.Text = "相機不支援自動對焦到指定的點";
                     });
                 }
             }
         }
     }
 }
```

## 3、演示如何實時修改捕獲到的視訊幀

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

                 <!--用於顯示經過處理後的實時畫面-->
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
  * 演示如何實時處理攝像頭捕獲到的影象
  *
  * PhotoCamera - 用於提供相機功能
  *     PreviewResolution - 捕獲到的影象的當前的解析度（返回 System.Windows.Size 類型的結構體，其包含 Width 和 Height 欄位）
  *     GetPreviewBufferArgb32(int[] pixelData) - 將當前捕獲到的影象的 ARGB 資料複製到指定的緩衝區中
  *
  *
  * 注：
  * Resolution 指的是相機設定的解析度
  * PreviewResolution 指的是系統針對顯示裝置縮放後的真實解析度
  * 因為通常相機能夠拍攝大於裝置顯示器的解析度的影象，所以實時顯示攝像頭捕獲到的影象時，系統會對其解析度進行優化，PreviewResolution 就是優化後的資料
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

         // 用於顯示處理後的影象
         private WriteableBitmap _writeableBitmap;
         // 有訊號
         private static ManualResetEvent _manualReset = new ManualResetEvent(true);

         public LiveAlter()
         {
             InitializeComponent();
         }

         protected override void OnNavigatedTo(NavigationEventArgs e)
         {
             if (PhotoCamera.IsCameraTypeSupported(CameraType.Primary))
             {
                 // 例項化 PhotoCamera，並註冊相關事件
                 _camera = new PhotoCamera(CameraType.Primary);
                 _camera.Initialized += _camera_Initialized;

                 videoBrush.SetSource(_camera);
             }
             else
             {
                 this.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "裝置不支援主攝像頭";
                 });
             }
         }

         protected override void OnNavigatingFrom(NavigatingCancelEventArgs e)
         {
          // 清理資源
             if (_camera != null)
             {
                 _camera.Dispose();
                 _camera.Initialized -= _camera_Initialized;
             }
         }

         void _camera_Initialized(object sender, CameraOperationCompletedEventArgs e)
         {
             // 新開執行緒去執行實時處理圖片的任務
             Thread thread = new Thread(CameraToGray);
             thread.Start();

             this.Dispatcher.BeginInvoke(delegate()
             {
                 // 讓 Image 顯示 WriteableBitmap 中的內容
                 _writeableBitmap = new WriteableBitmap((int)_camera.PreviewResolution.Width, (int)_camera.PreviewResolution.Height);
                 imgEffect.Source = _writeableBitmap;
             });
         }

         private void CameraToGray()
         {
             // 初始化緩衝區大小：影象寬和高的乘積
             int[] buffer = new int[(int)_camera.PreviewResolution.Width * (int)_camera.PreviewResolution.Height];

             try
             {
                 while (true)
                 {
                     // 例項化 ManualResetEvent 的時候，指定了其是有訊號的
                     _manualReset.WaitOne(); // 有訊號則不阻塞，無訊號則阻塞

                     // 將當前捕獲到的影象以 ARGB 的方式寫入到緩衝區
                     _camera.GetPreviewBufferArgb32(buffer);

                     // 將緩衝區中的每一個畫素的顏色都轉換為灰色系
                     for (int i = 0; i < buffer.Length; i++)
                     {
                         buffer[i] = ColorToGray(buffer[i]);
                     }

                     _manualReset.Reset(); // 設定為無訊號

                     Deployment.Current.Dispatcher.BeginInvoke(delegate()
                     {
                         // 將處理後的影象資料儲存到 WriteableBitmap 物件
                         buffer.CopyTo(_writeableBitmap.Pixels, 0);
                       // 重新繪製整個 WriteableBitmap 物件
                         _writeableBitmap.Invalidate();

                         lblMsg.Text = "影象實時處理中";

                         _manualReset.Set();  // 設定為有訊號
                     });
                 }

             }
             catch (Exception ex)
             {
                 this.Dispatcher.BeginInvoke(delegate()
                 {
                     lblMsg.Text = "影象處理失敗：" + ex.ToString();
                 });
            }
         }

         // 將指定的顏色轉換成灰色系的顏色
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