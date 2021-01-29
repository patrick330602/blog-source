---
title: 在WSL上配置输入法
date: 2019-10-28 22:12:00
tags:
- WSL
---

# TL;DR

这篇文章会简述如何在WSL上配置输入法,以进行中日韩输入。安装以 Ubuntu 为例。

## 为啥?

WSL 在 GUI 下并不支持 Windows CJK 输入法的直接输入，所以要配置独立的输入法。这种方法既适用于Linux桌面环境，也适用于多窗口。

## 安装 fcitx （小企鹅输入法）

输入以下命令：

```shell
sudo apt install fcitx fonts-noto-cjk fonts-noto-color-emoji dbus-x11
```

这会安装 CJK 字体和 fcitx 核心。

然后,安装你想要使用的输入法。下表列出了一些比较常用的 fcitx 输入法：

| 语言     | 输入法                          | 安装包名称                      |
| -------- | ------------------------------- | ------------------------------- |
| 中文     | 基于`sunpinyin`引擎的拼音输入法 | `fcitx-sunpinyin`               |
| 中文     | 基于`libpinyin`引擎的拼音输入法 | `fcitx-libpinyin`               |
| 中文     | 谷歌拼音输入法                  | `fcitx-googlepinyin`            |
| 中文     | 基于`rime`引擎的输入法          | `fcitx-rime`                    |
| 中文     | 新酷音输入法                    | `fcitx-chewing`                 |
| 日文     | 基于`Anthy`引擎的日文输入法     | `fcitx-anthy`                   |
| 日文     | 基于`mozc`引擎的日文输入法      | `fcitx-mozc`                    |
| 日文     | 假名-汉字转换器(kkc)日文输入法  | `fcitx-kkc` `fcitx-kkc-dev`     |
| 韩文     | 韩语输入法                      | `fcitx-hangul`                  |
| 越南文   | 基于`unikey`引擎的越南文输入法  | `fcitx-unikey`                  |
| 僧伽罗文 | 僧伽罗文输入法                  | `fcitx-sayura`                  |
| -        | 码表类输入法                    | `fcitx-table` `fcitx-table-all` |

使用 `sudo apt install <安装包名称>`进行安装。

## 配置输入环境

首先使用root账号生成dbus机器码：

```shell
dbus-uuidgen > /var/lib/dbus/machine-id
```

用`root`账号创建`/etc/profile.d/fcitx.sh`文件, 内容如下:

```shell
#!/bin/bash
export QT_IM_MODULE=fcitx
export GTK_IM_MODULE=fcitx
export XMODIFIERS=@im=fcitx
export DefaultIMModule=fcitx

#可选，fcitx 自启
fcitx-autostart &>/dev/null
```

## 初期配置

首先确保你的 X 服务器已启动，并配置好了`DISPLAY`。WSL2 的话，请确保 WSL2 的公共 IP 可以与 X 服务器建立连接。

运行以下命令：

```shell
export GTK_IM_MODULE=fcitx
export QT_IM_MODULE=fcitx
export XMODIFIERS=@im=fcitx
export DefaultIMModule=fcitx
fcitx-autostart &>/dev/null
```

**fcitx** 会启动。

运行 `fcitx-config-gtk3`,会出现如图的界面：

![](https://cdn.patrickwu.space/posts/dev/wsl/fcitx-1.png)

安装好后，如果没有看见输入法，点击左下角的**+**，搜索安装好的输入法：

![](//cdn.patrickwu.space/posts/dev/wsl/fcitx-2.png)

选择点击 OK 即可添加。

然后选择 **Global Config** ，将 **Trigger Input Method** 与 **Scroll between Input Method** 的快捷键修改掉（与微软拼音输入法快捷键冲突）：

![](//cdn.patrickwu.space/posts/dev/wsl/fcitx-3.png)

至此，配置完成！

## 效果

在任意GUI应用用 **Trigger Input** 设置的快捷键启用输入法，用 **Scroll between Input Method** 的快捷键切换输入法，效果如下：

![](//cdn.patrickwu.space/posts/dev/wsl/fcitx-4.png)