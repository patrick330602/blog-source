---
title: WP8 停止音樂實現辦法
tags:
  - C#
  - WP8
date: 2014-07-08 21:49:56
lang: zh
---

和相機的LED燈一樣，WP也沒有提供相應的API供我們操作媒體播放器
當音樂開啟後，WP是不會關閉音樂的(在鎖屏介面和音量介面可以看到音樂的介面)，只有關機重啟後，音樂才會被關閉，當然，不關閉音樂基本也不會佔用記憶體
這裡說的關閉音樂指的是：在鎖屏和音量介面看不到音樂的介面
通過摸索，關閉音樂功能有三種途徑:

1. 關機
2. 開啟一個視訊，這樣，系統會自動關閉後臺的音樂功能，也可以實現關閉音樂的功能(嘗試了一下，很難通過程式碼來實現，操作麻煩)
3. 通過異常讓系統關閉後臺音樂功能(下面用此方法關閉音樂)
<!--more-->
首先要用到系統的媒體播放器。使用媒體播放器播放應用程式的音樂檔案的時候，當程式關閉或墓碑化時，音樂會停止播放，只有媒體庫中的音樂才能在後臺播放
首先準備一個音樂檔案(用來輔助關閉音樂功能，越小越好)，假設為00.mp3
用到的類：`MediaPlayer`
新增引用：`Microsoft.Xna.Framework`
首先是`MediaPlayer`的用法
由於在XNA中每33fp就會更新畫面一次，所以在Silverlight Application中需要透過指定一個定期執行`FrameworkDispatcher.Update()`的事件。

```csharp
 //設定定時器
DispatcherTimer timer = new DispatcherTimer();
timer.Interval = TimeSpan.FromMilliseconds(33);	  
timer.Tick += delegate { 
	try 
	{ 
		FrameworkDispatcher.Update(); 
	} 
	catch 
	{ 
	} 
};	  
timer.Start();//接下來通過Song建立音訊物件
```

Song沒有建構函式，通過靜態函數FromUri建立
`Song song = Song.FromUri("MySong", new Uri("00.mp3", UriKind.Relative));`
播放音樂
`MediaPlayer.Play(song);`
MediaPlayer屬性和方法(不支援快進/快退，不支援設定進度)
IsMuted					靜音設定
IsRepeating				重複播放
IsShuffled				隨機播放
MoveNext/MovePrevious	下一曲/上一曲
Play/Stop				播放/停止
Pause/Resume			暫停/恢復
PlayPosition			進度(只讀)
State					狀態
`Song.FromUri`建立Song物件的音訊檔案只能是資原始檔，不能是獨立儲存中的檔案。暫時沒有API可以用

**關閉音樂的思路：**
把前面註冊 FrameworkDispatcher.Update() 的事件去掉，這樣在呼叫MediaPlayer播放器播放音樂的時候就會出現異常
通過兩次呼叫，就可以關閉掉音樂服務，當然，程式也會關閉，可以寫一個磁貼貼在Start介面上，關閉音樂後自動關閉程式

```csharp
MediaPlayer.Play(song);
MediaPlayer.Play(song);
```
