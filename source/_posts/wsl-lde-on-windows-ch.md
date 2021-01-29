---
title: "在Windows10上安装Linux桌面环境(更新)"
date: 2016-08-23 21:38:08
tags:
- WSL
---
> 从BashOnWindows GitHub网站上的[文章](https://github.com/Microsoft/BashOnWindows/issues/637) 进行整理和翻译。

我前面写了一遍如何利用X server, CCSM, Compiz和其他组件，与Bash On Ubuntu On Windows相互配合在Windows 10上直接运行Linux桌面环境。最近，我改进了其中的步骤，所以再发了一篇。

> 除非你是Linux发烧友，不建议使用此Linux桌面环境，因为质量和效果不是很好。推荐使用更轻量的i3wm和[openbox](https://patrickwu.space/2017/03/openbox-tint2-windows10/).

## 首先....

在Bash On Windows运行以下代码:

```sh
echo "export DISPLAY=:0.0" >> ~/.bashrc
```

以Root用户运行以下代码:

```sh
sudo sed -i 's$<listen>.*</listen>$<listen>tcp:host=localhost,port=0</listen>$' /etc/dbus-1/session.conf
```

> 一定要以root运行，否则会没有效果

## Unity桌面环境

1. 安装VcXsrv并打开**XLaunch**。 选择 "One large window" 并在**display number** 输入0，如图：

   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/1.png)
   一路next下去就行，直到他完成配置

2. 打开Bash On Windows并安装 **ubuntu-desktop**，**unity** 和 **ccsm**：

   ```shell
   sudo apt-get install ubuntu-desktop unity compizconfig-settings-manager
   ```

   输出显示：

   ```shell
   export DISPLAY=localhost:0
   ```

   并打开**ccsm**。
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/2.png)

3. **ccsm**中可能显示不出鼠标指针，因为没有加载玩所有内容。如图选择插件。

   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/3.png)
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/4.png)

4. 现在关闭ccsm并打开 **compiz**. 
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/5.png)
    Compiz会花些时间载入，稍稍等待下桌面环境就会出现
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/6.png)

5. 关闭桌面环境的方法只能是关闭bash窗口，或者杀Compiz进程。

## XFCE桌面环境

1. 安装VcXsrv并打开**XLaunch**。 选择 "One large window" 并在**display number** 输入0，如图：

   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/1.png)
   一路next下去就行，直到他完成配置

2. 安装xorg和xubuntu:
   ```sh
   sudo apt-get install xorg xubuntu-desktop
   ```

   此步骤会花上一些时间。

3. 完成后输入 `xfce4-session` 运行Xfce.