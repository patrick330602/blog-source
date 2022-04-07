---
title: 在 WSL2 上編譯並使用 Darling
date: 2019-10-29 10:12:12
tags:
- WSL
- WSL2
- Darwin
lang: zh
---

> 翻譯並修改自[darlinghq/darling issue #260 的評論](https://github.com/darlinghq/darling/issues/260#issuecomment-530184521)

# TL;DR

這篇文章將會講述如何在 WSL2 上編譯並使用[Darling]( http://www.darlinghq.org/ )，一款可以在 Linux 系統上執行 Darwin （就是 MacOS 的開源核心）的相容層,就像 WineHQ 一樣。

## 編譯 Darling 和 WSL2 Kernel

開啟 WSL Ubuntu，並依次鍵入以下指令。以下指令會下載、編譯和安裝除了 Kernel 以外大部分 Darling 元件，並下載、編譯和安裝一份修改過的 WSL Kernel並複製到C盤。

```
# 確保32位依賴能正確安裝
sudo dpkg --add-architecture i386
sudo apt-get update

# 安裝必要的安裝包
sudo apt-get install cmake clang bison flex xz-utils libfuse-dev libudev-dev pkg-config libc6-dev:i386 linux-headers-generic gcc-multilib libcap2-bin libcairo2-dev libgl1-mesa-dev libtiff5-dev libfreetype6-dev libfreetype6-dev:i386 git libelf-dev libxml2-dev libegl1-mesa-dev libfontconfig1-dev libbsd-dev libssl-dev

# 克隆並編譯 Darling
git clone --recursive https://github.com/darlinghq/darling.git
cd darling
mkdir build && cd build
cmake ..

# 連結一些檔案到 `/usr/include`
sudo ln -s /usr/include/x86_64-linux-gnu/jconfig.h /usr/include/jconfig.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiffio.h /usr/include/tiffio.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiff.h /usr/include/tiff.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiffconf.h /usr/include/tiffconf.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiffvers.h /usr/include/tiffvers.h
sudo ln -s /usr/include/x86_64-linux-gnu/tiffio.hxx /usr/include/tiffio.hxx

# 編譯大部分Darling 元件
make
sudo make install

# 在編譯 Kernel 模組之前，我們需要編譯
cd ..

# 克隆並修改 Kernel
git clone --depth 1 --single-branch --branch master https://github.com/microsoft/WSL2-Linux-Kernel.git kernel
cd kernel
sed -i 's/CONFIG_EMBEDDED=y/CONFIG_EMBEDDED=n/g' Microsoft/config-wsl

# 編譯並安裝 Kernel
make KCONFIG_CONFIG=Microsoft/config-wsl
sudo make modules_install
sudo make install
sudo update-initramfs -c -k 4.19.67-microsoft-standard+

# 複製到C盤；當然，你也可以選擇複製到任何地方
mkdir /mnt/c/linux-kernels
cp /boot/vmlinuz-4.19.67-microsoft-standard+ /mnt/c/linux-kernels/vmlinuz-4.19.67-microsoft-standard+
```

## 修改 WSL2 使用的 Kernel

現在在你 Windows 使用者資料夾下建立（或編輯） `.wslconfig` ，加入以下內容：

```
[wsl2]
#你的其他設定
kernel=C:\\linux-kernels\\vmlinuz-4.19.67-microsoft-standard+
```

然後在 PowerShell 或者命令列裡輸入以下命令關閉所有執行中的 WSL 發行版：

```
wsl --shutdown
```

## 編譯 Darling Kernel 模組

重新開啟你的 WSL 視窗並執行以下命令。以下指令會編譯並安裝 Kernel 模組：

```bash
# get back to the directory you built Darling in
cd darling/build
make lkm
sudo make lkm_install
```

## 搞定！

目前仍然有一個Bug使`darling shell` 只能以`root`使用者執行，這意味著你只能用以下命令執行：

```bash
sudo darling shell
```

然後享受 Darwin on Linux on Windows 吧！