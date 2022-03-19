---
title: "啟用Surface Book上Ubuntu的觸屏"
date: 2017-03-16 13:49:48
tags:
- Linux
- Ubuntu
- Surface
lang: zh
---
我以前曾經嘗試著啟用Surface Book上Ubuntu的觸屏但是失敗了。幸運的是，有人發現了啟用觸屏的方法。。。針對於Surface Pro 4的。 但基於他的方法，我進行了一些修改，成功的啟用了Surface Book上Ubuntu的觸屏。現在除了休眠時會睡死，一切正常。

在此，我會使用/u/cantenna1的kernel。

![](https://cdn.patrickwu.space/posts/dev/wsl/music_on_bash.png)

> 從Reddit /r/SurfaceLinux上的[文章](https://www.reddit.com/r/SurfaceLinux/comments/4t64zt/getting_the_sp4_running_with_ubuntu_1604/)進行整理，修改和翻譯。

### 1.壓縮硬碟空間

右鍵開始選單->磁碟管理，接著右鍵C盤分區，選擇壓縮卷，壓縮至你想要用的大小（我的最多可壓縮至120GB）。

### 2.建立Ubuntu引導盤

參見<http://www.ubuntu.com/download/desktop/create-a-usb-stick-on-windows>

### 3.插入外接鍵鼠（可選）

鍵盤和觸控板在安裝和後續步驟是會不可用， 所以你需要插入外接鍵鼠。如果你閒的蛋疼，你可以使用Ubuntu安裝盤內建的OnBoard鍵盤（設定-> 通用輔助功能-> 打字 -> 螢幕鍵盤)

### 4.從USB啟動並安裝Ubuntu

關閉Surface， 按住電源和音量上鍵進入BIOS。修改啟動順序，使U盤先啟動。

然後可以從U盤啟動了。雖然原作者在安裝時全部選擇了預設選項，我還是建議進行自己分區（將`/boot`進行獨立分區）以防止破壞EFI分區（不要問我為什麼知道的，血的教訓啊）。 

### 5.安裝Kernel

現在，你可以正常進入Ubuntu了。但是，現在還是有很多問題，比如鍵鼠不能用。你現在需要使用新的Kernel。我個人使用的是/u/cantenna1的kernel：

這個kernel可以觸屏，但是物理鍵會無法使用。同時，外接顯示器會有嚴重的顯示問題。

更加詳細的資料片可檢視<https://www.reddit.com/r/SurfaceLinux/comments/4vbzki/androidx86_with_the_new_ipts_driver/d5xs969> 和其中/u/cantenna1與/u/arda_coskunses的評論。詳細的觸屏支援請檢視<https://github.com/ipts-linux-org/ipts-linux/wiki>。WiFi驅動來自<https://github.com/matthewwardrop/linux-surfacepro3/blob/master/wifi.patch> 。

安裝的話，請下載<https://mega.nz/#!nJJ2DSJZ!4BYSRvzp3hb6NxU5X6_38xFkpuUEmSNvRo2px2TCDqc>並解壓檔案。 開啟終端，cd到資料夾並輸入以下指令：

```sh
sudo dpkg -i './linux-headers-4.4.0-rc8touchkernel+_1_amd64.deb'
sudo dpkg -i './linux-image-4.4.0-rc8touchkernel+_1_amd64.deb'
```

### 6.拷貝觸屏驅動

要讓觸屏工作，我們需要Windows分區的觸屏驅動。我們需要將這些檔案從Windows分區拷貝到Ubuntu分區，以保證可以找到驅動。

> 注：如果你找不到或者刪掉了Windows分區的話你可以從此下載<https://www.microsoft.com/en-us/download/details.aspx?id=49498>。選擇zip格式，然後你可以在Drivers/System/SurfaceTouchServicingML下找到觸屏驅動。

首先確保你的Windows分區已經掛載（最簡單的方法是在資源管理器裡直接掛載）。現在在根目錄建立名為itouch的資料夾，並將驅動拷貝進去。

```sh
sudo mkdir /itouch
sudo cp /media/使用者名稱/磁碟名/Windows/INF/PreciseTouch/Intel/* /itouch
```
你現在需要建立連結使驅動可以被搜尋到。

```sh
sudo ln -sf /itouch/SurfaceTouchServicingKernelSKLMSHW0076.bin /itouch/vendor_kernel_skl.bin
sudo ln -sf /itouch/SurfaceTouchServicingSFTConfigMSHW0076.bin /itouch/integ_sft_cfg_skl.bin
sudo ln -sf /itouch/SurfaceTouchServicingDescriptorMSHW0076.bin /itouch/vendor_descriptor.bin
sudo ln -sf /itouch/iaPreciseTouchDescriptor.bin /itouch/integ_descriptor.bin
```

### 7.修改預設Kernel

所有驅動已經安裝完，但是系統不一定會啟動到正確的Kernel。你可以在啟動時在grub手動選擇Advanced options for Ubuntu -> Ubuntu, with Linux 4.4.0-rc8touchkernel+。要改變預設的Kernel你需要修改grub（我用的是-customizer: <http://www.howtogeek.com/howto/43471/how-to-configure-the-linux-grub2-boot-menu-the-easy-way/>)。

### 8.防止自動休眠

一旦休眠，你的電腦會睡的死死的。要防止蓋上後自動休眠，輸入以下指令：

```sh
sudo gedit /etc/UPower/UPower.conf
```

然後把`IgnoreLid=false`改成`IgnoreLid=true`。

對於Gnome，你需要在`gnome-tweak-tool`關閉休眠。