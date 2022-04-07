---
title: 解決 Ubuntu 17.10 下 Android Studio 模擬器無法執行的問題
date: 2018-03-18 15:05:36
tags:
- Android
- Ubuntu
lang: zh
---
因為Windows下WSL的相容性我還是不滿足，於是乎又在我的Surafce Book上裝回了Ubuntu 17.10，但在執行Android Studio時發現Android模擬器啟動不了，log裡則有這個提示：
```
libGL error: unable to load driver: i965_dri.so
libGL error: driver pointer missing
```
網上搜尋了一番，發現這個問題在不同版本Ubuntu 16.10+都有出現，原因很簡單：谷歌為了防止在不同系統上為了防止出現相容性問題，用了自己的`libstdc++`包，但這些寫死的包卻不一定支援使用者所對應的系統，比如我就是用的自定義的Surface Book核心。雖然這個問題是[已知](https://code.google.com/p/android/issues/detail?id=197254)的，但谷歌似乎還是沒能給出一個完美的解決方案。其實，解決問題很簡單：使用系統的`libstdc+`就行了。
<!--more-->
- 首先，安裝系統的`libstdc++`和其他的依賴項：
	```bash
	sudo apt-get install lib64stdc++6:i386 mesa-utils
	```
- 然後建立軟連結：
	```bash
	cd $HOME/Android/Sdk/emulator/lib64 #舊版請使用$HOME/Android/Sdk/tools/lib64
	mv libstdc++/ libstdc++.bak
	ln -s /usr/lib64/libstdc++.so.6  libstdc++
	```

然後模擬器就可以正常啟動啦！

![](https://cdn.patrickwu.space/posts/dev/running-emulator.png)

話說Linux也是很麻煩啊。。。（不，只是我喜歡作死用預覽版而已