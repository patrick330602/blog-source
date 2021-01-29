---
title: 如何计划 Windows Phone 的磁贴更新
tags:
- C#
- WP8
- WP8.1
date: 2014-07-06 11:21:26
---

本主题介绍使用 [ShellTileSchedule](http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 更新应用程序磁贴的背景图像所需的步骤。[ShellTileSchedule](http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 也可以用于更新次要磁贴。[Windows Phone 的磁贴概述](http://msdn.microsoft.com/zh-cn/library/hh202948.aspx)包含磁贴属性以及可以用于更新磁贴的各种方法。

> 磁贴正面的背景图像是唯一一个可以使用 ShellTileSchedule 进行更新的属性。

可以在 [Windows Phone 的代码示例](http://msdn.microsoft.com/zhcn/library/ff431744.aspx)中找到这个已完成的示例。

<!--more-->
## 设置磁贴计划

[ShellTileSchedule](http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 允许您设置将更新磁贴的背景图像的一次性计划或定期计划。即使您的应用程序处于非活动状态，该计划仍然可以继续运行。ShellTileSchedule 还可以用于停止为您的应用程序运行的任何计划。每当应用程序由于计划失败而启动时，应用程序都应该存储其 ShellTileSchedule 设置并启动该计划，即使应用程序不再运行，也可以取消计划。

[ShellTileSchedule](http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 示例是一个具有四个按钮的简单程序。这四个按钮分别是：

1.  更新磁贴一次。
2.  创建一个更新磁贴次数不确定的计划。
3.  创建一个更新磁贴指定次数的计划。
4.  停止正在运行的任何计划。

![AP_Tiles_TileScheduleSample](https://cdn.patrickwu.space/posts/dev/wp/IC505451.jpg)
      
> 请记住，将应用程序的应用程序磁贴固定到“开始”屏幕以便测试磁贴更新功能。


## 创建应用程序 UI

1.  在 Visual Studio 2010 Express for Windows Phone 中，通过选择**“文件 | 新建项目”**菜单命令创建一个新项目。
2.  将显示“新建项目”窗口。展开 Visual C# 模板，然后选择**“Silverlight for Windows Phone”**模板。
3.  选择**“Windows Phone 应用程序”**模板。用您选择的名称填写“名称”。
4.  单击**“确定”**。将显示“新建 Windows Phone 应用程序”窗口。
5.  在**“Windows Phone OS 目标版本”**菜单中，确保**“Windows Phone OS 7.1”**处于选定状态。
6.  单击**“确定”**。一个新项目便创建完成，并在 Visual Studio 设计器窗口中打开 MainPage.xaml。
7.  在 MainPage.xaml 中，将名为 ContentPanel 的 Grid 替换为以下代码。该代码将为我们的 UI 创建四个按钮。

```xml
<Grid x:Name="ContentPanel" Grid.Row="1" Margin="12,0,12,0">
      <Button Content="Start One Time Schedule" Height="72" HorizontalAlignment="Center" Margin="38,42,38,0" Name="buttonOneTime" VerticalAlignment="Top" Width="400" Click="buttonOneTime_Click" />
      <Button Content="Start Indefinite Schedule" Height="72" HorizontalAlignment="Center"  Margin="38,120,38,0" Name="buttonIndefinite" VerticalAlignment="Top" Width="400" Click="buttonIndefinite_Click" />
      <Button Content="Start Defined Count Schedule" Height="72" HorizontalAlignment="Center"  Margin="38,198,38,0" Name="buttonDefined" VerticalAlignment="Top" Width="400"  Click="buttonDefined_Click"/>
      <Button Content="Stop Schedule" Height="72" HorizontalAlignment="Center" Margin="38,276,38,0" Name="buttonStop" VerticalAlignment="Top" Width="400" Click="buttonStop_Click" />
</Grid>
```

### 创建磁贴计划


1.  向 MainPage.xaml.cs 文件的顶部添加一个 using 指令并提供包含 [ShellTileSchedule]( http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 的命名空间的名称。

```csharp
 Microsoft.Phone.Shell;
```

1.  声明并初始化用于计划和状态的变量。


```csharp
    MainPage : PhoneApplicationPage
{
    ShellTileSchedule SampleTileSchedule =  ShellTileSchedule();     TileScheduleRunning = ;
```

1.  添加用于执行一次性更新的代码。

```csharp
  buttonOneTime_Click( sender, RoutedEventArgs e)
{    
    SampleTileSchedule.Recurrence = UpdateRecurrence.Onetime;    
    SampleTileSchedule.StartTime = DateTime.Now;
    SampleTileSchedule.RemoteImageUri =  Uri();
    SampleTileSchedule.Start();
    TileScheduleRunning = ;
}
```
1.  添加用于设置运行次数不确定的计划的代码。请记住，如果该计划失败次数太多，将取消该计划。

```csharp
  buttonIndefinite_Click( sender, RoutedEventArgs e)
{    
    SampleTileSchedule.Recurrence = UpdateRecurrence.Interval;    
    SampleTileSchedule.Interval = UpdateInterval.EveryHour;
    SampleTileSchedule.RemoteImageUri =  Uri();
    SampleTileSchedule.Start();
    TileScheduleRunning = ;
}
```

1.  添加用于设置某个计划的代码，该计划将更新磁贴无限次。同样，如果该计划失败次数太多，将取消该计划。

```csharp
  buttonDefined_Click( sender, RoutedEventArgs e)
{    
    SampleTileSchedule.Recurrence = UpdateRecurrence.Interval;    
    SampleTileSchedule.Interval = UpdateInterval.EveryHour;    
    SampleTileSchedule.MaxUpdateCount = 50;
    SampleTileSchedule.RemoteImageUri =  Uri();
    SampleTileSchedule.Start();
    TileScheduleRunning = ;
}
```

添加用于停止任何正在运行的计划的代码。请注意，我们如何通过首先启动计划来附加到该计划。

```csharp
  buttonStop_Click( sender, RoutedEventArgs e)
{    
     (!TileScheduleRunning)
    {
        buttonIndefinite_Click(sender, e);
    }
    SampleTileSchedule.Stop();
    TileScheduleRunning = ;
}
```

调试更新计划可能具有挑战性。为了省电，对更新进行批处理，因此可能需要多达约一小时的时间才能看到更新后的图像。当设备锁定时将不会进行更新，因为用户将看不到更新。

### 运行和调试应用程序的步骤



1.  通过选择**“调试 | 启动调试”**菜单命令运行应用程序。
2.  当模拟器初始化并且您的程序正在运行时，按模拟器上的**“开始”**按键以进入“开始”屏幕。导航到应用程序列表并找到您的应用程序。长按应用程序名称，然后从上下文菜单中选择**“固定到‘开始'屏幕”**。应用程序磁贴便固定到“开始”屏幕。
3.  按**“返回”**按键返回您的应用程序。按应用程序上的其中一个按钮启动某个计划。
4.  返回“开始”屏幕。等待该计划运行，以查看结果。请记住，此过程可能需要一小段时间，因为需要对更新进行批处理。

![AP_Tiles_TileScheduleResult](https://cdn.patrickwu.space/posts/dev/wp/IC505452.jpg)

## 计划次要磁贴

尽管该示例没有次要磁贴，但您也可以通过向 ShellTileSchedule 构造函数传递磁贴信息来为次要磁贴设置计划。下面的示例演示了如何为每个现有磁贴设置计划。

```csharp
 (ShellTile TileToSchedule  ShellTile.ActiveTiles)
{
    ShellTileSchedule mySchedule =  ShellTileSchedule(TileToSchedule);
    mySchedule.Interval = UpdateInterval.EveryHour;
    mySchedule.Recurrence = UpdateRecurrence.Interval;
    mySchedule.RemoteImageUri = imageURI;
    mySchedule.Start();
}
```
