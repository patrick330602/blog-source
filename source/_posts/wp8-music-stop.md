---
title: WP8停止音乐实现办法
tags:
  - C#
  - WP8
date: 2014-07-08 21:49:56
---

和相机的LED灯一样，WP也没有提供相应的API供我们操作媒体播放器
当音乐打开后，WP是不会关闭音乐的(在锁屏界面和音量界面可以看到音乐的界面)，只有关机重启后，音乐才会被关闭，当然，不关闭音乐基本也不会占用内存
这里说的关闭音乐指的是：在锁屏和音量界面看不到音乐的界面
通过摸索，关闭音乐功能有三种途径:

1. 关机
2. 打开一个视频，这样，系统会自动关闭后台的音乐功能，也可以实现关闭音乐的功能(尝试了一下，很难通过代码来实现，操作麻烦)
3. 通过异常让系统关闭后台音乐功能(下面用此方法关闭音乐)
<!--more-->
首先要用到系统的媒体播放器。使用媒体播放器播放应用程序的音乐文件的时候，当程序关闭或墓碑化时，音乐会停止播放，只有媒体库中的音乐才能在后台播放
首先准备一个音乐文件(用来辅助关闭音乐功能，越小越好)，假设为00.mp3
用到的类：`MediaPlayer`
添加引用：`Microsoft.Xna.Framework`
首先是`MediaPlayer`的用法
由于在XNA中每33fp就会更新画面一次，所以在Silverlight Application中需要透过指定一个定期执行`FrameworkDispatcher.Update()`的事件。

```csharp
 //设置定时器
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
timer.Start();//接下来通过Song创建音频对象
```

Song没有构造函数，通过静态函数FromUri创建
`Song song = Song.FromUri("MySong", new Uri("00.mp3", UriKind.Relative));`
播放音乐
`MediaPlayer.Play(song);`
MediaPlayer属性和方法(不支持快进/快退，不支持设置进度)
IsMuted					静音设置
IsRepeating				重复播放
IsShuffled				随机播放
MoveNext/MovePrevious	下一曲/上一曲
Play/Stop				播放/停止
Pause/Resume			暂停/恢复
PlayPosition			进度(只读)
State					状态
`Song.FromUri`创建Song对象的音频文件只能是资源文件，不能是独立存储中的文件。暂时没有API可以用

**关闭音乐的思路：**
把前面注册 FrameworkDispatcher.Update() 的事件去掉，这样在调用MediaPlayer播放器播放音乐的时候就会出现异常
通过两次调用，就可以关闭掉音乐服务，当然，程序也会关闭，可以写一个磁贴贴在Start界面上，关闭音乐后自动关闭程序

```csharp
MediaPlayer.Play(song);
MediaPlayer.Play(song);
```
