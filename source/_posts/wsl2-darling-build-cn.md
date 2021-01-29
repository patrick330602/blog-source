---
title: 在WSL2上编译并使用Darling
date: 2019-10-29 10:12:12
tags:
- WSL
- WSL2
- Darwin
---

> 翻译并修改自[darlinghq/darling issue #260 的评论](https://github.com/darlinghq/darling/issues/260#issuecomment-530184521)

# TL;DR

这篇文章将会讲述如何在 WSL2 上编译并使用[Darling]( http://www.darlinghq.org/ )，一款可以在 Linux 系统上运行 Darwin （就是 MacOS 的开源内核）的兼容层,就像 WineHQ 一样。

## 编译 Darling 和 WSL2 Kernel

打开 WSL Ubuntu，并依次键入以下指令。以下指令会下载、编译和安装除了 Kernel 以外大部分 Darling 组件，并下载、编译和安装一份修改过的 WSL Kernel并复制到C盘。

```
# 确保32位依赖能正确安装
sudo dpkg --add-architecture i386
sudo apt-get update

# 安装必要的安装包
sudo apt-get install cmake clang bison flex xz-utils libfuse-dev libudev-dev pkg-config libc6-dev:i386 linux-headers-generic gcc-multilib libcap2-bin libcairo2-dev libgl1-mesa-dev libtiff5-dev libfreetype6-dev libfreetype6-dev:i386 git libelf-dev libxml2-dev libegl1-mesa-dev libfontconfig1-dev libbsd-dev libssl-dev

# 克隆并编译 Darling
git clone --recursive https://github.com/darlinghq/darling.git
cd darling
mkdir build && cd build
cmake ..

# 链接一些文件到 `/usr/include`
sudo ln -s /usr/include/x86_64-linux-gnu/jconfig.h /usr/include/jconfig.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiffio.h /usr/include/tiffio.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiff.h /usr/include/tiff.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiffconf.h /usr/include/tiffconf.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiffvers.h /usr/include/tiffvers.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiffio.hxx /usr/include/tiffio.hxx

# 编译大部分Darling 组件
make
sudo make install

# 在编译 Kernel 模块之前，我们需要编译
cd ..

# 克隆并修改 Kernel
git clone --depth 1 --single-branch --branch master https://github.com/microsoft/WSL2-Linux-Kernel.git kernel
cd kernel
sed -i 's/CONFIG_EMBEDDED=y/CONFIG_EMBEDDED=n/g' Microsoft/config-wsl

# 编译并安装 Kernel
make KCONFIG_CONFIG=Microsoft/config-wsl
sudo make modules_install
sudo make install
sudo update-initramfs -c -k 4.19.67-microsoft-standard+

# 复制到C盘；当然，你也可以选择复制到任何地方
mkdir /mnt/c/linux-kernels
cp /boot/vmlinuz-4.19.67-microsoft-standard+ /mnt/c/linux-kernels/vmlinuz-4.19.67-microsoft-standard+
```

## 修改 WSL2 使用的 Kernel

现在在你 Windows 用户文件夹下创建（或编辑） `.wslconfig` ，加入以下内容：

```
[wsl2]
#你的其他设置
kernel=C:\\linux-kernels\\vmlinuz-4.19.67-microsoft-standard+
```

然后在 PowerShell 或者命令行里输入以下命令关闭所有运行中的 WSL 发行版：

```
wsl --shutdown
```

## 编译 Darling Kernel 模组

重新打开你的 WSL 窗口并执行以下命令。以下指令会编译并安装 Kernel 模组：

```bash
# get back to the directory you built Darling in
cd darling/build
make lkm
sudo make lkm_install
```

## 搞定！

目前仍然有一个Bug使`darling shell` 只能以`root`用户运行，这意味着你只能用以下命令运行：

```bash
sudo darling shell
```

然后享受 Darwin on Linux on Windows 吧！