---
title: 如何計劃 Windows Phone 的磁貼更新
tags:
- C#
- WP8
- WP8.1
date: 2014-07-06 11:21:26
lang: zh
---

本主題介紹使用 [ShellTileSchedule](http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 更新應用程式磁貼的背景影象所需的步驟。[ShellTileSchedule](http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 也可以用於更新次要磁貼。[Windows Phone 的磁貼概述](http://msdn.microsoft.com/zh-cn/library/hh202948.aspx)包含磁貼屬性以及可以用於更新磁貼的各種方法。

> 磁貼正面的背景影象是唯一一個可以使用 ShellTileSchedule 進行更新的屬性。

可以在 [Windows Phone 的程式碼示例](http://msdn.microsoft.com/zhcn/library/ff431744.aspx)中找到這個已完成的示例。

<!--more-->
## 設定磁貼計劃

[ShellTileSchedule](http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 允許您設定將更新磁貼的背景影象的一次性計劃或定期計劃。即使您的應用程式處於非活動狀態，該計劃仍然可以繼續執行。ShellTileSchedule 還可以用於停止為您的應用程式執行的任何計劃。每當應用程式由於計劃失敗而啟動時，應用程式都應該儲存其 ShellTileSchedule 設定並啟動該計劃，即使應用程式不再執行，也可以取消計劃。

[ShellTileSchedule](http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 示例是一個具有四個按鈕的簡單程式。這四個按鈕分別是：

1.  更新磁貼一次。
2.  建立一個更新磁貼次數不確定的計劃。
3.  建立一個更新磁貼指定次數的計劃。
4.  停止正在執行的任何計劃。

![AP_Tiles_TileScheduleSample](https://cdn.patrickwu.space/posts/dev/wp/IC505451.jpg)
      
> 請記住，將應用程式的應用程式磁貼固定到“開始”螢幕以便測試磁貼更新功能。


## 建立應用程式 UI

1.  在 Visual Studio 2010 Express for Windows Phone 中，通過選擇**“檔案 | 新建項目”**選單命令建立一個新項目。
2.  將顯示“新建項目”視窗。展開 Visual C# 模板，然後選擇**“Silverlight for Windows Phone”**模板。
3.  選擇**“Windows Phone 應用程式”**模板。用您選擇的名稱填寫“名稱”。
4.  單擊**“確定”**。將顯示“新建 Windows Phone 應用程式”視窗。
5.  在**“Windows Phone OS 目標版本”**選單中，確保**“Windows Phone OS 7.1”**處於選定狀態。
6.  單擊**“確定”**。一個新項目便建立完成，並在 Visual Studio 設計器視窗中開啟 MainPage.xaml。
7.  在 MainPage.xaml 中，將名為 ContentPanel 的 Grid 替換為以下程式碼。該程式碼將為我們的 UI 建立四個按鈕。

```xml
<Grid x:Name="ContentPanel" Grid.Row="1" Margin="12,0,12,0">
      <Button Content="Start One Time Schedule" Height="72" HorizontalAlignment="Center" Margin="38,42,38,0" Name="buttonOneTime" VerticalAlignment="Top" Width="400" Click="buttonOneTime_Click" />
      <Button Content="Start Indefinite Schedule" Height="72" HorizontalAlignment="Center"  Margin="38,120,38,0" Name="buttonIndefinite" VerticalAlignment="Top" Width="400" Click="buttonIndefinite_Click" />
      <Button Content="Start Defined Count Schedule" Height="72" HorizontalAlignment="Center"  Margin="38,198,38,0" Name="buttonDefined" VerticalAlignment="Top" Width="400"  Click="buttonDefined_Click"/>
      <Button Content="Stop Schedule" Height="72" HorizontalAlignment="Center" Margin="38,276,38,0" Name="buttonStop" VerticalAlignment="Top" Width="400" Click="buttonStop_Click" />
</Grid>
```

### 建立磁貼計劃


1.  向 MainPage.xaml.cs 檔案的頂部新增一個 using 指令並提供包含 [ShellTileSchedule]( http://msdn.microsoft.com/zh-cn/library/microsoft.phone.shell.shelltileschedule.aspx) 的名稱空間的名稱。

```csharp
 Microsoft.Phone.Shell;
```

1.  聲明並初始化用於計劃和狀態的變數。


```csharp
    MainPage : PhoneApplicationPage
{
    ShellTileSchedule SampleTileSchedule =  ShellTileSchedule();     TileScheduleRunning = ;
```

1.  新增用於執行一次性更新的程式碼。

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
1.  新增用於設定執行次數不確定的計劃的程式碼。請記住，如果該計劃失敗次數太多，將取消該計劃。

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

1.  新增用於設定某個計劃的程式碼，該計劃將更新磁貼無限次。同樣，如果該計劃失敗次數太多，將取消該計劃。

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

新增用於停止任何正在執行的計劃的程式碼。請注意，我們如何通過首先啟動計劃來附加到該計劃。

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

偵錯更新計劃可能具有挑戰性。為了省電，對更新進行批處理，因此可能需要多達約一小時的時間才能看到更新後的影象。當裝置鎖定時將不會進行更新，因為使用者將看不到更新。

### 執行和偵錯應用程式的步驟



1.  通過選擇**“偵錯 | 啟動偵錯”**選單命令執行應用程式。
2.  當模擬器初始化並且您的程式正在執行時，按模擬器上的**“開始”**按鍵以進入“開始”螢幕。導航到應用程式列表並找到您的應用程式。長按應用程式名稱，然後從上下文選單中選擇**“固定到‘開始'螢幕”**。應用程式磁貼便固定到“開始”螢幕。
3.  按**“返回”**按鍵返回您的應用程式。按應用程式上的其中一個按鈕啟動某個計劃。
4.  返回“開始”螢幕。等待該計劃執行，以檢視結果。請記住，此過程可能需要一小段時間，因為需要對更新進行批處理。

![AP_Tiles_TileScheduleResult](https://cdn.patrickwu.space/posts/dev/wp/IC505452.jpg)

## 計劃次要磁貼

儘管該示例沒有次要磁貼，但您也可以通過向 ShellTileSchedule 建構函式傳遞磁貼資訊來為次要磁貼設定計劃。下面的示例演示瞭如何為每個現有磁貼設定計劃。

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
