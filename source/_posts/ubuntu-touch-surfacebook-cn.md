---
title: "启用Surface Book上Ubuntu的触屏"
date: 2017-03-16 13:49:48
tags:
- Linux
- Ubuntu
- Surface
---
我以前曾经尝试着启用Surface Book上Ubuntu的触屏但是失败了。幸运的是，有人发现了启用触屏的方法。。。针对于Surface Pro 4的。 但基于他的方法，我进行了一些修改，成功的启用了Surface Book上Ubuntu的触屏。现在除了休眠时会睡死，一切正常。

在此，我会使用/u/cantenna1的kernel。

![](https://cdn.patrickwu.space/posts/dev/wsl/music_on_bash.png)

> 从Reddit /r/SurfaceLinux上的[文章](https://www.reddit.com/r/SurfaceLinux/comments/4t64zt/getting_the_sp4_running_with_ubuntu_1604/)进行整理，修改和翻译。

### 1.压缩硬盘空间

右键开始菜单->磁盘管理，接着右键C盘分区，选择压缩卷，压缩至你想要用的大小（我的最多可压缩至120GB）。

### 2.创建Ubuntu引导盘

参见<http://www.ubuntu.com/download/desktop/create-a-usb-stick-on-windows>

### 3.插入外接键鼠（可选）

键盘和触控板在安装和后续步骤是会不可用， 所以你需要插入外接键鼠。如果你闲的蛋疼，你可以使用Ubuntu安装盘内置的OnBoard键盘（设置-> 通用辅助功能-> 打字 -> 屏幕键盘)

### 4.从USB启动并安装Ubuntu

关闭Surface， 按住电源和音量上键进入BIOS。修改启动顺序，使U盘先启动。

然后可以从U盘启动了。虽然原作者在安装时全部选择了默认选项，我还是建议进行自己分区（将`/boot`进行独立分区）以防止破坏EFI分区（不要问我为什么知道的，血的教训啊）。 

### 5.安装Kernel

现在，你可以正常进入Ubuntu了。但是，现在还是有很多问题，比如键鼠不能用。你现在需要使用新的Kernel。我个人使用的是/u/cantenna1的kernel：

这个kernel可以触屏，但是物理键会无法使用。同时，外接显示器会有严重的显示问题。

更加详细的资料片可查看<https://www.reddit.com/r/SurfaceLinux/comments/4vbzki/androidx86_with_the_new_ipts_driver/d5xs969> 和其中/u/cantenna1与/u/arda_coskunses的评论。详细的触屏支持请查看<https://github.com/ipts-linux-org/ipts-linux/wiki>。WiFi驱动来自<https://github.com/matthewwardrop/linux-surfacepro3/blob/master/wifi.patch> 。

安装的话，请下载<https://mega.nz/#!nJJ2DSJZ!4BYSRvzp3hb6NxU5X6_38xFkpuUEmSNvRo2px2TCDqc>并解压文件。 打开终端，cd到文件夹并输入以下指令：

```sh
sudo dpkg -i './linux-headers-4.4.0-rc8touchkernel+_1_amd64.deb'
sudo dpkg -i './linux-image-4.4.0-rc8touchkernel+_1_amd64.deb'
```

### 6.拷贝触屏驱动

要让触屏工作，我们需要Windows分区的触屏驱动。我们需要将这些文件从Windows分区拷贝到Ubuntu分区，以保证可以找到驱动。

> 注：如果你找不到或者删掉了Windows分区的话你可以从此下载<https://www.microsoft.com/en-us/download/details.aspx?id=49498>。选择zip格式，然后你可以在Drivers/System/SurfaceTouchServicingML下找到触屏驱动。

首先确保你的Windows分区已经挂载（最简单的方法是在资源管理器里直接挂载）。现在在根目录创建名为itouch的文件夹，并将驱动拷贝进去。

```sh
sudo mkdir /itouch
sudo cp /media/用户名/磁盘名/Windows/INF/PreciseTouch/Intel/* /itouch
```
你现在需要创建链接使驱动可以被搜索到。

```sh
sudo ln -sf /itouch/SurfaceTouchServicingKernelSKLMSHW0076.bin /itouch/vendor_kernel_skl.bin
sudo ln -sf /itouch/SurfaceTouchServicingSFTConfigMSHW0076.bin /itouch/integ_sft_cfg_skl.bin
sudo ln -sf /itouch/SurfaceTouchServicingDescriptorMSHW0076.bin /itouch/vendor_descriptor.bin
sudo ln -sf /itouch/iaPreciseTouchDescriptor.bin /itouch/integ_descriptor.bin
```

### 7.修改默认Kernel

所有驱动已经安装完，但是系统不一定会启动到正确的Kernel。你可以在启动时在grub手动选择Advanced options for Ubuntu -> Ubuntu, with Linux 4.4.0-rc8touchkernel+。要改变默认的Kernel你需要修改grub（我用的是-customizer: <http://www.howtogeek.com/howto/43471/how-to-configure-the-linux-grub2-boot-menu-the-easy-way/>)。

### 8.防止自动休眠

一旦休眠，你的电脑会睡的死死的。要防止盖上后自动休眠，输入以下指令：

```sh
sudo gedit /etc/UPower/UPower.conf
```

然后把`IgnoreLid=false`改成`IgnoreLid=true`。

对于Gnome，你需要在`gnome-tweak-tool`关闭休眠。