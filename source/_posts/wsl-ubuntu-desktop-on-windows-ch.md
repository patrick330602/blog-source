---
title: 在Windows 10下啟用Ubuntu桌面環境
date: 2016-07-13 21:19:02
tags:
- WSL
lang: zh
---

> 從BashOnWindows GitHub網站上的[文章](https://github.com/Microsoft/BashOnWindows/issues/637) 進行整理和翻譯。

現在，只要你利用X server, CCSM, Compiz和其他元件，與Bash On Ubuntu On Windows相互配合，你現在可以在Windows 10上直接執行Linux桌面環境.

> 除非你是Linux發燒友，不建議使用此Linux桌面環境。

## Unity桌面環境

1. 在 Bash On Windows執行以下程式碼:

   ```sh
   echo "export DISPLAY=:0.0" >> ~/.bashrc
   sudo sed -i 's$<listen>.*</listen>$<listen>tcp:host=localhost,port=0</listen>$' /etc/dbus-1/session.conf
   ```

2. 安裝VcXsrv並開啟**XLaunch**。 選擇 "One large window" 並在**display number** 輸入0，如圖：

   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/1.png)
   一路next下去就行，直到他完成配置

3. 開啟Bash On Windows並安裝 **ubuntu-desktop**，**unity** 和 **ccsm**：

   ```shell
   sudo apt-get install ubuntu-desktop unity compizconfig-settings-manager
   ```

   輸出顯示：

   ```shell
   export DISPLAY=localhost:0
   ```

   並開啟**ccsm**。
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/2.png)

4. **ccsm**中可能顯示不出滑鼠指針，因為沒有載入玩所有內容。如圖選擇外掛。

   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/3.png)
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/4.png)

5. 現在關閉ccsm並開啟 **compiz**. 
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/5.png)
    Compiz會花些時間載入，稍稍等待下桌面環境就會出現
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/6.png)

6. 關閉桌面環境的方法只能是關閉bash視窗，或者殺Compiz程序。

## XFCE桌面環境

1. 使用與Unity同樣的方法配置VcXsrv。（1-4步）
2. 安裝**xfce4-session**：
   ```shell
    sudo apt-get install xfce4-session
   ```
   並執行**xfce4-session**.