---
title: 完美解鎖WP8手機方法
date: 2014-12-31 21:38:11
tags:
- WP8
- WP8.1
- Interop
lang: zh
---
# 需要環境

- 帶sd卡且開發者解鎖的wp8手機
- 帶有部署的windows電腦

# 需要工具

- CustomPFD(需部署)
- Preview For Developers
- Pocket File Manager
- Metro Commander
- WPsystem Folder Unlocker(需部署)
<!--more-->
# 步驟

1. 安裝所有程式與手機記憶體之中，然後用儲存感知移動Preview For Developers和CustomPFD到SD卡中，所有程式都暫時不要開啟
2. 執行WPSystem Folder Unlocker，點選Unlock WPSystem Folder(SD Card)
3. 用Pocket File Manager開啟`SD Card\WPSystem`,將apps資料夾（也可能叫Apps）改名為Appx
4. 然後進入appx資料夾，刪除Preview for developers應用資料夾`{178ac8a1-6519-4a0b-960c-038393741e96}`裡面的所有檔案
5. 把名字改回去（Apps或apps）
6. 開啟Metro Commander,左邊介面移到`WPSystem\apps\{27d6a090-50d2-4337-88cb-41d97597757a}`資料夾（CutomPFD的資料夾），右邊介面移到`WPSystem\apps\{178ac8a1-6519-4a0b-960c-038393741e96}`資料夾（Preview for developer的資料夾）
7. 啟用左邊視窗，點選SELECT ALL，然後點選COPY，然後他就會把CutomPFD的程式內容全部拷到Preview For Developers的根目錄之下
8. 完畢！現在執行Preview For Developers便是登錄檔編輯器咯！

# 原理

因為Preview For Developers是在市場中極少見的擁有讀取WP8登錄檔許可權的應用程式，直接覆蓋他的源碼會很方便的擁有和這個應用相同的權利

# 部署包
http://pan.baidu.com/s/1pJmdU7x 密碼：bmep
