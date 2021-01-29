---
title: "Install LDE On windows 10(Updated)"
date: 2016-08-23 21:38:08
tags:
- WSL
---
Previously, I wrote a article about how to use X server, CCSM, Compiz and other components with Bash On Ubuntu On Windows to run Linux desktop environment from Windows desktop. Recently, I find a improved way of installing the LDE. 
<!-- more -->
![Coding In xface4](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/main.png)


> Retrieved from [this post](https://github.com/Microsoft/BashOnWindows/issues/637) in BashOnWindows GitHub site. 

## First things first....

Run following code on Bash On Windows:

```sh
echo "export DISPLAY=:0.0" >> ~/.bashrc
```

And run the following code as root user:

```sh
sudo sed -i 's$<listen>.*</listen>$<listen>tcp:host=localhost,port=0</listen>$' /etc/dbus-1/session.conf
```

> it is really important to run the code as root user, otherwise the code won't work

## For Ubuntu

1. Install VcXsrv and open **XLaunch**. Choose "One large window" with display number as 0.
   Other settings leave as default and finish the configuration.

2. Now open bash, install **Ubuntu Dekstop Environment**, **Unity** and **Compiz Config Settings Manager**:
   ```shell
   sudo apt-get install ubuntu-desktop unity compizconfig-settings-manager
   ```
   and type `ccsm` to open Compiz Configuration Settings Manager.

3. Inside ccsm mouse pointer may be not visible due to icon not loaded. Enable the following plugins.
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/3.png)
   ![](https://cdn.patrickwu.space/posts/dev/wsl/lde-on-win10/4.png)

5. Now close ccsm and open ``compiz`` to start Unity Linux Desktop Environment.   

6. To exit from Ubuntu close bash or kill compiz, the only way of closing Ubuntu.

## For XUbuntu
1. Install **VcXsrv** and open **XLaunch**. Choose "One large window" or other options you like with display number as 0.
   Other settings leave as default and finish the configuration.

2. Install xorg and xubuntu:
   ```sh
   sudo apt-get install xorg xubuntu-desktop
   ```

   This might take a while to complete.

3. After success installation, open `xfce4-session` to start Xubuntu Desktop Environment.

4. To exit from XUbuntu close bash, the only way of closing xubuntu.
