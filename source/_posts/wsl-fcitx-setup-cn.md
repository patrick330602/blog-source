---
title: 在WSL上配置輸入法
date: 2019-10-28 22:12:00
tags:
- WSL
lang: zh
---

# TL;DR

這篇文章會簡述如何在WSL上配置輸入法,以進行中日韓輸入。安裝以 Ubuntu 為例。

## 為啥?

WSL 在 GUI 下並不支援 Windows CJK 輸入法的直接輸入，所以要配置獨立的輸入法。這種方法既適用於Linux桌面環境，也適用於多視窗。

## 安裝 fcitx （小企鵝輸入法）

輸入以下命令：

```shell
sudo apt install fcitx fonts-noto-cjk fonts-noto-color-emoji dbus-x11
```

這會安裝 CJK 字型和 fcitx 核心。

然後,安裝你想要使用的輸入法。下表列出了一些比較常用的 fcitx 輸入法：

| 語言     | 輸入法                          | 安裝包名稱                      |
| -------- | ------------------------------- | ------------------------------- |
| 中文     | 基於`sunpinyin`引擎的拼音輸入法 | `fcitx-sunpinyin`               |
| 中文     | 基於`libpinyin`引擎的拼音輸入法 | `fcitx-libpinyin`               |
| 中文     | 谷歌拼音輸入法                  | `fcitx-googlepinyin`            |
| 中文     | 基於`rime`引擎的輸入法          | `fcitx-rime`                    |
| 中文     | 新酷音輸入法                    | `fcitx-chewing`                 |
| 日文     | 基於`Anthy`引擎的日文輸入法     | `fcitx-anthy`                   |
| 日文     | 基於`mozc`引擎的日文輸入法      | `fcitx-mozc`                    |
| 日文     | 假名-漢字轉換器(kkc)日文輸入法  | `fcitx-kkc` `fcitx-kkc-dev`     |
| 韓文     | 韓語輸入法                      | `fcitx-hangul`                  |
| 越南文   | 基於`unikey`引擎的越南文輸入法  | `fcitx-unikey`                  |
| 僧伽羅文 | 僧伽羅文輸入法                  | `fcitx-sayura`                  |
| -        | 碼錶類輸入法                    | `fcitx-table` `fcitx-table-all` |

使用 `sudo apt install <安裝包名稱>`進行安裝。

## 配置輸入環境

首先使用root賬號生成dbus機器碼：

```shell
dbus-uuidgen > /var/lib/dbus/machine-id
```

用`root`賬號建立`/etc/profile.d/fcitx.sh`檔案, 內容如下:

```shell
#!/bin/bash
export QT_IM_MODULE=fcitx
export GTK_IM_MODULE=fcitx
export XMODIFIERS=@im=fcitx
export DefaultIMModule=fcitx

#可選，fcitx 自啟
fcitx-autostart &>/dev/null
```

## 初期配置

首先確保你的 X 伺服器已啟動，並配置好了`DISPLAY`。WSL2 的話，請確保 WSL2 的公共 IP 可以與 X 伺服器建立連線。

執行以下命令：

```shell
export GTK_IM_MODULE=fcitx
export QT_IM_MODULE=fcitx
export XMODIFIERS=@im=fcitx
export DefaultIMModule=fcitx
fcitx-autostart &>/dev/null
```

**fcitx** 會啟動。

執行 `fcitx-config-gtk3`,會出現如圖的介面：

![](https://cdn.patrickwu.space/posts/dev/wsl/fcitx-1.png)

安裝好後，如果沒有看見輸入法，點選左下角的**+**，搜尋安裝好的輸入法：

![](//cdn.patrickwu.space/posts/dev/wsl/fcitx-2.png)

選擇點選 OK 即可新增。

然後選擇 **Global Config** ，將 **Trigger Input Method** 與 **Scroll between Input Method** 的快捷鍵修改掉（與微軟拼音輸入法快捷鍵衝突）：

![](//cdn.patrickwu.space/posts/dev/wsl/fcitx-3.png)

至此，配置完成！

## 效果

在任意GUI應用用 **Trigger Input** 設定的快捷鍵啟用輸入法，用 **Scroll between Input Method** 的快捷鍵切換輸入法，效果如下：

![](//cdn.patrickwu.space/posts/dev/wsl/fcitx-4.png)