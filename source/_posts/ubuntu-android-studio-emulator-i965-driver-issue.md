---
title: 解决Ubuntu 17.10下Android Studio模拟器无法运行的问题
date: 2018-03-18 15:05:36
tags:
- Android
- Ubuntu
---
因为Windows下WSL的兼容性我还是不满足，于是乎又在我的Surafce Book上装回了Ubuntu 17.10，但在运行Android Studio时发现Android模拟器启动不了，log里则有这个提示：
```
libGL error: unable to load driver: i965_dri.so
libGL error: driver pointer missing
```
网上搜索了一番，发现这个问题在不同版本Ubuntu 16.10+都有出现，原因很简单：谷歌为了防止在不同系统上为了防止出现兼容性问题，用了自己的`libstdc++`包，但这些写死的包却不一定支持用户所对应的系统，比如我就是用的自定义的Surface Book内核。虽然这个问题是[已知](https://code.google.com/p/android/issues/detail?id=197254)的，但谷歌似乎还是没能给出一个完美的解决方案。其实，解决问题很简单：使用系统的`libstdc+`就行了。
<!--more-->
- 首先，安装系统的`libstdc++`和其他的依赖项：
	```bash
	sudo apt-get install lib64stdc++6:i386 mesa-utils
	```
- 然后创建软链接：
	```bash
	cd $HOME/Android/Sdk/emulator/lib64 #旧版请使用$HOME/Android/Sdk/tools/lib64
	mv libstdc++/ libstdc++.bak
	ln -s /usr/lib64/libstdc++.so.6  libstdc++
	```

然后模拟器就可以正常启动啦！

![](https://cdn.patrickwu.space/posts/dev/running-emulator.png)

话说Linux也是很麻烦啊。。。（不，只是我喜欢作死用预览版而已