---
title: 'Blursed WSL: use WSL on Mac via Parallels Desktop'
tags:
  - WSL
  - WSL2
  - OSX
date: 2020-02-14 23:40:20
---

## Some background...

I recently switched to Mac because of something requires me to use MacOS. I got a 2019 16 inch model of MacBook Pro, and I am pretty satisfied with it[^1].

[^1]: A review is coming ;)

As I am still developing for WSL (and also UWP), I used Parallels Desktop for running Windows on Mac, because I hate Boot Camp. After setting up Windows 10 on Parallels Desktop with come tweaks, successfully set up WSL/WSL2 with Mac. 

{% twitter https://twitter.com/callmepkwu/status/1199257722270601216?s=21 %}

## Use Parallels feature wisely

Two important feature in Parallels Desktop is used: Coherence Mode and Nested Virtualization. 

Using Coherence mode, you can use Windows applications and UWPs such as Windows Terminal just like native apps:

![Windows Terminal on Mac](https://cdn.patrickwu.space/posts/dev/wsl/mac/1.png)

Using Nested Virtualization is essential to use WSL2 and the brand new Windows Docker for WSL2. Some people might afraid that enabling it has little impact on my laptop. You can enable the feature here in Parallels Desktop:

![Nested virtualization in Parallels Configure Menu](https://cdn.patrickwu.space/posts/dev/wsl/mac/2.png)


## Auto-mounting Mac partition on WSL

WSL has been providing drive mounting feature using **DrvFs** for some times[^2]. Mounting with **DrvFs** is super easy; Without mounting your Windows drive, you can just:

[^2]: Good place to start with **DrvFs**: <https://docs.microsoft.com/en-gb/archive/blogs/wsl/file-system-improvements-to-the-windows-subsystem-for-linux>

- use `sudo mkdir /mnt/d && sudo mount -t drvfs D: /mnt/d/` to mount your D drive;
- use `sudo mount -t drvfs '\\server\share' /mnt/share` to mount netowork location `\\server\share`. 

You can even auto-mount using `fstab`.

However, auto-mounting with Mac partition requires something more than documentation provided, otherwise you might have trouble accessing the files. 

Before mounting, the file-sharing should be enabled in Parallels Configuration:

![Files Sharing in Parallels Configure Menu](https://cdn.patrickwu.space/posts/dev/wsl/mac/3.png)

It is suggested to use just the network location as drives can be unmounted automatically by Parallels and thus unpredictable.

Now, check the folder you want to mount. In my case, I tried to mount `\\Mac\Home\`. So I created a folder `/mnt/mac` and in my `/etc/fstab`, I input the following:

```
\\Mac\Home\      /mnt/mac        drvfs   metadata,uid=1000,gid=1000,umask=0022,fmask=11,case=off 0       0
```

Restart the distribution by using `wsl -t`, and drive will automatically be mounted:

![Mounted drive in /mnt/mac](https://cdn.patrickwu.space/posts/dev/wsl/mac/4.png)


## Opening website/file from WSL to Mac browser

This one is pretty easy to configure. If you used my [WSL Utilities](https://github.com/wslutilities/wslu), you can already open website using `wslview`. Then setup **Web pages** to **Open in Mac** in the Parallels Configuration:

![Webpage pages option in Parallels Desktop Configuration ](https://cdn.patrickwu.space/posts/dev/wsl/mac/5.png)

Then everything is now ready. When you launch a website using `wslview`, it will open in the default Mac web browser.

Here is a little demo:

![Demo for Opening website in Mac from WSL](https://cdn.patrickwu.space/posts/dev/wsl/mac/6.gif)

## WSL Desktop Shortcut On Mac

The component `wslusc` in my [WSL Utilities](https://github.com/wslutilities/wslu) allows you to create shortcuts on Windows Desktop. Combining with Parallels Desktop's feature, you can launch WSL GUI application right from your Mac Desktop.

Here is how it looks:

![Demo for Windows Shortcut on Mac](https://cdn.patrickwu.space/posts/dev/wsl/mac/7.gif)

To achieve this, We should enable Desktop Mapping in the Parallels Desktop with the following steps:

![Step 1 for enabling Desktop Mapping](https://cdn.patrickwu.space/posts/dev/wsl/mac/8.png)
![Step 2 for enabling Desktop Mapping](https://cdn.patrickwu.space/posts/dev/wsl/mac/9.png)

Afterward, the desktop should show the shortcut created on the Windows 10 Desktop. However, they won't look good; But we can do some small modification to make it better like the following:

![Effects on Icon edition](https://cdn.patrickwu.space/posts/dev/wsl/mac/10.png)

Here is how to modify it:

![Steps on how to change the icon](https://cdn.patrickwu.space/posts/dev/wsl/mac/11.png)

## To conclude...

The experience is not perfect, but this allows me to use Ubuntu without creating extra Virtual Machine on my MBP, which means a save on system memory when I need to run Windows and Ubuntu at the same time. 

This sure is a great experience using WSL on Mac.

I am also going to present a session on WSLConf ;)


