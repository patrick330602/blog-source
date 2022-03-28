---
title: Enable Ubuntu Desktop Environment On Windows 10
date: 2016-07-13 21:19:02
tags:
- WSL
multi_lang:
  en: ''
  zh: '-ch'
---

Right now, using X server, CCSM, Compiz and other components with Bash On Ubuntu On Windows, You can now run Linux desktop environment from Windows desktop.
<!-- more -->
![Coding In xface4](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/main.png)

> Retrieved from [this post](https://github.com/Microsoft/BashOnWindows/issues/637) in BashOnWindows GitHub site. 

## For Unity

1. Run following code on Bash On Windows:
   ```sh
   echo "export DISPLAY=:0.0" >> ~/.bashrc
   sudo sed -i 's$<listen>.*</listen>$<listen>tcp:host=localhost,port=0</listen>$' /etc/dbus-1/session.conf
   ```

2. Install VcXsrv and open **XLaunch**. Choose "One large window" with display number as 0  like this:

   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/1.png)
   Other settings leave as default and finish the configuration.

3. Now open bash, install **ubuntu-desktop**, **unity** and **ccsm**:
   ```shell
   sudo apt-get install ubuntu-desktop unity compizconfig-settings-manager
   ```
   Export the display:
   ```shell
   export DISPLAY=localhost:0
   ```
   and open **ccsm**.
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/2.png)

4. Inside ccsm mouse pointer may be not visible due to icon not loaded. Enable the following plugins.
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/3.png)
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/4.png)

5. Now close ccsm and open **compiz**. 
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/5.png)
    Compiz will load and seconds later unity will show up.
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/6.png)

6. To exit from unity close bash or kill compiz, the only way of closing unity.
## For XFCE
1. The same configuration for VcXsrv applies here.

2. Install **xfce4-session**:
   ```shell
    sudo apt-get install xfce4-session
   ```
   and run **xfce4-session**.