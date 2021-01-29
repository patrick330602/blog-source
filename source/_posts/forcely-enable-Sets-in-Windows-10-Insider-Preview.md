---
title: forcely enable Sets in Windows 10 Insider Preview
tags:
  - Winodws 10 Insider
date: 2018-05-26 17:30:40
---


# TL;DR

This article is a more detailed tutorial comparing to [this on reddit](https://www.reddit.com/r/Windows10/comments/8cv9sr/guide_a_foolproof_complete_guide_to_enabling_sets/) as it do not contain some of the important things to do before and after. This article will help you forcely enable Sets on Windows 10 Insider Preview(RS5) using [mach2](https://github.com/riverar/mach2).

<!--more-->

## How-To

Download `mach2` from its [release](https://github.com/riverar/mach2/releases). For 32 bit system, download x86, for 64 bit system, download x64. Extract the downloaded file, and open the folder where `mach2.exe` located. Open Powershell window using Administrator privileges under this folder.

Run `.\mach2.exe display`. you should see something like this:

```
mach2 0.3 - Feature Control Multi-tool
Copyright (c) Rafael Rivera

This program comes with ABSOLUTELY NO WARRANTY.
This is free software, and you are welcome to redistribute it under certain conditions.

Enabled:
  14184448  (variant: 63)
  12805652
  16669694
  17057765
  13849566  (variant: 1)
  14184448  (variant: 63)
  12805652
  16669694
  17057765

Disabled:

Defaulted:
```

It should not contain any value of `10727725` and `13849566` in **Enabled** section. If it has, please remove them first correspondingly using `.\mach2.exe revert 10727725` or `.\mach2.exe revert 13849566`.

Then, run the following code and restart, then you can see Sets now:

```powershell
.\mach2.exe enable 13849566 -v 1
.\mach2.exe enable 10727725 -v 1
```
## Fixing

Actually, you might found Sets disppearing after some times, and this is because the variant value for `13849566` is changed. By running `.\mach2.exe display`, you might find `13849566  (variant: 63)` in **Enabled** section. If this happened, remove the entry `13849566` and readd it, then reboot. 