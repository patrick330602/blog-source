---
title: "Enable touch on Ubuntu on Surface Book 启用Surface Book上Ubuntu的触屏"
date: 2017-03-16 13:49:48
tags:
- Linux
- Ubuntu
- Surface
---
I used to try to enable the touch screen on Surface Book but failed. Fortunately, someone find out a way to enable touch... on Surface Pro 4. Using his idea with some modification, I successfully enabled touch on Surface Book. Now everything work except hibernating system since it sleeps like dead.
<!--more-->
I will be using /u/cantenna1's kernel in this post.

![](https://cdn.patrickwu.space/posts/dev/wsl/music_on_bash.png)

> Retrived from [post](https://www.reddit.com/r/SurfaceLinux/comments/4t64zt/getting_the_sp4_running_with_ubuntu_1604/) in Reddit /r/SurfaceLinux and modified

### 1.Shrink the windows partition

Right click Start -> Disk Management. Then right click on the windows partition and go shrink volume and reduce it as much as it will let you (Mine let me shrink to 120 GB).

### 2.Make a bootable Ubuntu usb drive

See <http://www.ubuntu.com/download/desktop/create-a-usb-stick-on-windows>

### 3.Plug in a usb hub

The keyboard and touch pad won't work for the next few steps so I needed a usb hub to plug in a usb keyboard, mouse and the bootable usb drive. Or if you think you are not tired or you are bored, you can use on screen typing with OnBoard built-in Ubuntu Installer(Settings -> Universal Access -> Typing -> OnScreen Keyboard)

### 4.Boot from usb and install Ubuntu

Turn the surface off and then hold the volume down button while powering on to boot to BIOS. There change the boot order so that usb drives boot before the internal drive.

then you should be able to boot off the Ubuntu usb stick now. Although the original writer chose all the default options and installed alongside windows, I personally suggest using custom drive partition, i.e, separate `/boot` to prevent direct corruption to the system EFI partition.

### 5.Install a patched kernel

You should hopefully now be able to boot to a working Ubuntu, albeit with a ton of issues (no keyboard / touch pad among other things). You now have to update the kernel to one that has support for these features. I personally uses /u/cantenna1's kernel.

This kernel gets the touchscreen working, but the physical buttons will not work and the i915 GuC version used is a little buggy and caused me some issues with external monitors.

Further details on this kernel can be found in this post <https://www.reddit.com/r/SurfaceLinux/comments/4vbzki/androidx86_with_the_new_ipts_driver/d5xs969> and in the comments by /u/cantenna1 and /u/arda_coskunses in this post. Details on the patch for the touch support can be found at <https://github.com/ipts-linux-org/ipts-linux/wiki>. The patch for WIFI is from <https://github.com/matthewwardrop/linux-surfacepro3/blob/master/wifi.patch>.

To install the kernel download this file <https://mega.nz/#!nJJ2DSJZ!4BYSRvzp3hb6NxU5X6_38xFkpuUEmSNvRo2px2TCDqc> and extract its contents. Now open a terminal cd to the location of the files and install them by going

```sh
sudo dpkg -i './linux-headers-4.4.0-rc8touchkernel+_1_amd64.deb'
sudo dpkg -i './linux-image-4.4.0-rc8touchkernel+_1_amd64.deb'
```

### 6.Copy binary files needed by the touch drivers

To work, the touch drivers need some information stored in binaries on your windows partition. You now need to copy them over to the Ubuntu partition and ensure the drivers can find them. 

> Note: If you cannot find the files or have deleted your windows partition you can download them here <https://www.microsoft.com/en-us/download/details.aspx?id=49498>. Select the zip download option and once downloaded you will find the files in the Drivers/System/SurfaceTouchServicingML folder.

To do this first ensure your windows partition is mounted (the easiest way to do this is just to open it in the files browser). Now create a folder named 'itouch' in your root directory and copy the binaries to it

```sh
sudo mkdir /itouch
sudo cp /media/YOUR_USERNAME_HERE/YOUR_DRIVE_NAME_HERE/Windows/INF/PreciseTouch/Intel/* /itouch
```

You now need to create links to the files giving them names that match what the driver will search for

```sh
sudo ln -sf /itouch/SurfaceTouchServicingKernelSKLMSHW0076.bin /itouch/vendor_kernel_skl.bin
sudo ln -sf /itouch/SurfaceTouchServicingSFTConfigMSHW0076.bin /itouch/integ_sft_cfg_skl.bin
sudo ln -sf /itouch/SurfaceTouchServicingDescriptorMSHW0076.bin /itouch/vendor_descriptor.bin
sudo ln -sf /itouch/iaPreciseTouchDescriptor.bin /itouch/integ_descriptor.bin
```

### 7.Change the kernel that boots by default

Everything is now installed, however there is a good chance that your laptop won't boot the right kernel by default. You can select it manually in grub at boot by going Advanced options for Ubuntu -> Ubuntu, with Linux 4.4.0-rc8touchkernel+. To switch out the default you will need to edit grub (I did this with grub-customizer <http://www.howtogeek.com/howto/43471/how-to-configure-the-linux-grub2-boot-menu-the-easy-way/> followed by sudo update-grub)

### 8.Prevent the laptop suspending when the lid closes.

Once suspended it currently cannot wake, to get around this I prevent closing the lid from doing anything. To do this go

```sh
sudo gedit /etc/UPower/UPower.conf
```

and change `IgnoreLid=false` to `IgnoreLid=true`.

For GNOME, you also have to turn off the suspending in the `gnome-tweak-tool`.
