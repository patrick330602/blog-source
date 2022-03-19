---
title: "在Windows10上安裝Linux桌面環境(更新)"
date: 2016-08-23 21:38:08
tags:
- WSL
lang: zh
---
> 從BashOnWindows GitHub網站上的[文章](https://github.com/Microsoft/BashOnWindows/issues/637) 進行整理和翻譯。

我前面寫了一遍如何利用X server, CCSM, Compiz和其他元件，與Bash On Ubuntu On Windows相互配合在Windows 10上直接執行Linux桌面環境。最近，我改進了其中的步驟，所以再發了一篇。

> 除非你是Linux發燒友，不建議使用此Linux桌面環境，因為質量和效果不是很好。推薦使用更輕量的i3wm和[openbox](https://patrickwu.space/2017/03/openbox-tint2-windows10/).

## 首先....

在Bash On Windows執行以下程式碼:

```sh
echo "export DISPLAY=:0.0" >> ~/.bashrc
```

以Root使用者執行以下程式碼:

```sh
sudo sed -i 's$<listen>.*</listen>$<listen>tcp:host=localhost,port=0</listen>$' /etc/dbus-1/session.conf
```

> 一定要以root執行，否則會沒有效果

## Unity桌面環境

1. 安裝VcXsrv並開啟**XLaunch**。 選擇 "One large window" 並在**display number** 輸入0，如圖：

   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/1.png)
   一路next下去就行，直到他完成配置

2. 開啟Bash On Windows並安裝 **ubuntu-desktop**，**unity** 和 **ccsm**：

   ```shell
   sudo apt-get install ubuntu-desktop unity compizconfig-settings-manager
   ```

   輸出顯示：

   ```shell
   export DISPLAY=localhost:0
   ```

   並開啟**ccsm**。
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/2.png)

3. **ccsm**中可能顯示不出滑鼠指針，因為沒有載入玩所有內容。如圖選擇外掛。

   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/3.png)
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/4.png)

4. 現在關閉ccsm並開啟 **compiz**. 
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/5.png)
    Compiz會花些時間載入，稍稍等待下桌面環境就會出現
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/6.png)

5. 關閉桌面環境的方法只能是關閉bash視窗，或者殺Compiz程序。

## XFCE桌面環境

1. 安裝VcXsrv並開啟**XLaunch**。 選擇 "One large window" 並在**display number** 輸入0，如圖：

   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/1.png)
   一路next下去就行，直到他完成配置

2. 安裝xorg和xubuntu:
   ```sh
   sudo apt-get install xorg xubuntu-desktop
   ```

   此步驟會花上一些時間。

3. 完成後輸入 `xfce4-session` 執行Xfce.