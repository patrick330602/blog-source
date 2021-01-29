---
title: Setup Input Method for WSL
date: 2019-10-28 22:12:00
tags:
- WSL
---

# TL;DR

This article will talk about how to configure input method for CJK input. Using Ubuntu as example.

## Why?

WSL do not support direct CJK input from Windows input method, so we need to configure input method independently on WSL. This will applies to both multi-window mode and single-window mode on your preferred X Server.

## Install fcitx

Type the follwing command:

```shell
sudo apt install fcitx fonts-noto-cjk fonts-noto-color-emoji dbus-x11
```

This will install CJK fonts and `fcitx`.

Then, install the input method you want. Here is a list of common fcitx input method:

| Language   | Input Method                              | Package                         |
| ---------- | ----------------------------------------- | ------------------------------- |
| Chinese    | `sunpinyin`-based pinyin input            | `fcitx-sunpinyin`               |
| Chinese    | `libpinyin`-based pinyin input            | `fcitx-libpinyin`               |
| Chinese    | Google Pyinyin Input                      | `fcitx-googlepinyin`            |
| Chinese    | `rime`-based Chinese input                | `fcitx-rime`                    |
| Chinese    | Chewing Input                             | `fcitx-chewing`                 |
| Japanese   | `Anthy`-based Japanese Input              | `fcitx-anthy`                   |
| Japanese   | `mozc`-based Japanese Input               | `fcitx-mozc`                    |
| Japanese   | Kana-Kanji Converter (kkc) Japanese Input | `fcitx-kkc` `fcitx-kkc-dev`     |
| Korean     | Korean Input                              | `fcitx-hangul`                  |
| Vietnamese | `unikey`-based Vietnamese Input           | `fcitx-unikey`                  |
| Sinhalese  | Sinhalese Input                           | `fcitx-sayura`                  |
| -          | Fcitx Tables Input                        | `fcitx-table` `fcitx-table-all` |

Install using  `sudo apt install <Package>`.

## Confiure environment

generate dbus machine id using `root` account:

```shell
dbus-uuidgen > /var/lib/dbus/machine-id
```

create `/etc/profile.d/fcitx.sh` file using `root` account with following content:

```shell
#!/bin/bash
export QT_IM_MODULE=fcitx
export GTK_IM_MODULE=fcitx
export XMODIFIERS=@im=fcitx
export DefaultIMModule=fcitx

#optional
fcitx-autostart &>/dev/null
```

## Initial Configuration

Make sure you X Server is already started and complete configured `DISPLAY`. For WSL2, make sure public access is granted on your X Server.

Run following commands:

```shell
export GTK_IM_MODULE=fcitx
export QT_IM_MODULE=fcitx
export XMODIFIERS=@im=fcitx
export DefaultIMModule=fcitx
fcitx-autostart &>/dev/null
```

fcitx will start now.

Run `fcitx-config-gtk3`, following interface will appear:

![](https://cdn.patrickwu.space/posts/dev/wsl/fcitx-1.png)

If you complete input installation but don't see your input method，press **+** on the bottom left corner and search for the input you installed:

![](//cdn.patrickwu.space/posts/dev/wsl/fcitx-2.png)

Press **OK** to add.

Then choose **Global Config** and change the hotkey to **Trigger Input Method and **Scroll between Input Method** (They are conflict with Microsoft Pinyin Input Hotkey):

![](//cdn.patrickwu.space/posts/dev/wsl/fcitx-3.png)

Now, the configuration is complete!

## Outcome

In any GUI application, use the hotkey set for **Trigger Input** to start input method and use the hotkey set for **Scroll between Input Method** to switch input method, the outcome is like the following:

![](//cdn.patrickwu.space/posts/dev/wsl/fcitx-4.png)