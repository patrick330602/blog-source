---
title: 'Make WSL DE Great Again: Openbox and Tint2'
date: 2017-03-02 21:17:07
tags:
- WSL
- Openbox
---
In this world full of complexity, we need a DE that can help us to run away from sophisticated world. With two lightweight component Openbox and Tint2, you can get a familiar, simple and easy-to-use Desktop Environment in Windows 10 as well as in native Linux system. In this article, I will talk about how to install Openbox and Tint2 to use as the default DE in WSL which you will be also able to open the DE with one click using VcXsrv.

![Running Openbox and Tint2](https://cdn.patrickwu.space/posts/dev/wsl/openbox-tin2-desktop.png)
<!--more-->
1. Install VcXsrv and open **XLaunch**. Choose "One Window without titlebar" with display number as 0. Other settings leave as default. In the last page, save the configuration as a xlaunch file.
2. Install tint2 as well as openbox, and some tools:
    ```sh
    sudo apt install tint2 openbox lxappearance feh tint2conf obconf
    ```
3. create a bat file with following content:
    ```sh
    /path/to/xlaunch.exe -run "/path/to/config.xlaunch"
    bash -c "DISPLAY=:0 openbox-session"
    ```
    > xlaunch.exe can be found in the installation folder in VcXsrv
    
    Now you can start the openbox environment using one click.

4. Copy the default configuration using the following code:
    ```shell
    cp -R /etc/xdg/openbox ~/.config/
    ```
5. Configure autostart by using `vim ~/.config/openbox/autostart`; Add `tint2 &` to start tint2 taskbar automatically. Add `feh --bg-scale /path/to/wallpaper &` to set a wallpaper for Openbox.
6. Configure menu.xml by using `vim ~/.config/openbox/menu.xml`; Add the following line in the **root-menu**:
    ```xml
    <item label="tint2 Configuration">
        <action name="Execute"> 
            <execute>tint2conf</execute>
        </action>
    </item>
    <item label="Openbox Appearance">
        <action name="Execute">
            <execute>lxappearance</execute>
        </action>
    </item>
    ```

Now, the basic configuration is completed. You can now open the openbox by using the bat you previously created. Also, you can manually launch by start VcXsrv using the configuration I mentioned in Step 1, and run `DISPLAY=:0 openbox-session` in Bash Terminal to start the server. To open the menu, right click the desktop. to configure Openbox, use `obconf`(Also in menu); To configure Tint2, use `tint2conf`(Tint2 Configuration in menu); To configure other customization, use `lxappearance`(Openbox Appearance).

Now everything is set up. Happy bashing!