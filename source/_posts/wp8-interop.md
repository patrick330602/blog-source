---
title: 完美解锁WP8手机方法
date: 2014-12-31 21:38:11
tags:
- WP8
- WP8.1
- Interop
---
# 需要环境

- 带sd卡且开发者解锁的wp8手机
- 带有部署的windows电脑

# 需要工具

- CustomPFD(需部署)
- Preview For Developers
- Pocket File Manager
- Metro Commander
- WPsystem Folder Unlocker(需部署)
<!--more-->
# 步骤

1. 安装所有程序与手机内存之中，然后用储存感知移动Preview For Developers和CustomPFD到SD卡中，所有程序都暂时不要打开
2. 运行WPSystem Folder Unlocker，点击Unlock WPSystem Folder(SD Card)
3. 用Pocket File Manager打开`SD Card\WPSystem`,将apps文件夹（也可能叫Apps）改名为Appx
4. 然后进入appx文件夹，删除Preview for developers应用文件夹`{178ac8a1-6519-4a0b-960c-038393741e96}`里面的所有文件
5. 把名字改回去（Apps或apps）
6. 打开Metro Commander,左边界面移到`WPSystem\apps\{27d6a090-50d2-4337-88cb-41d97597757a}`文件夹（CutomPFD的文件夹），右边界面移到`WPSystem\apps\{178ac8a1-6519-4a0b-960c-038393741e96}`文件夹（Preview for developer的文件夹）
7. 激活左边窗口，点击SELECT ALL，然后点击COPY，然后他就会把CutomPFD的程序内容全部拷到Preview For Developers的根目录之下
8. 完毕！现在运行Preview For Developers便是注册表编辑器咯！

# 原理

因为Preview For Developers是在市场中极少见的拥有读取WP8注册表权限的应用程序，直接覆盖他的源码会很方便的拥有和这个应用相同的权利

# 部署包
http://pan.baidu.com/s/1pJmdU7x 密码：bmep
