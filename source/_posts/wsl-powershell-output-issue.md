---
title: WSL powershell.exe Output Encoding Problem
tags:
  - Powershell
  - WSL
date: 2021-02-02 15:36:44
---

Well well well, here we go again.

I recently received a bug report that when there are umlauts in their username ([wslutilities/wslu#162](https://github.com/wslutilities/wslu/issues/162)), `wslusc` failed to copy the file. In His username is "StephanHochdörfer" and the output is the following:
```
...
[warn] wsl.ico not found in Windows directory. Copying right now...
mkdir: cannot create directory '/mnt/c/Users/StephanHochd\224rfer/wslu': No such file or directory
cp: cannot create regular file '/mnt/c/Users/StephanHochd'$'\224''rfer/wslu': No such file or directory
[info] wsl.ico copied. Located at "/mnt/c/Users/StephanHochd�rfer/wslu".
...
```

![Oh no! Anyway...](https://cdn.patrickwu.space/memes/oh-no-anyway.jpeg)

Oh no! Anyway, it's definitely related to the code page mess in our good old `powershell.exe`. What a great sequel to our [powershell raster font problem](https://patrickwu.space/2019/08/03/wsl-powershell-raster-font-problem/).

From last time, we already get a pretty good structure that solves raster font, so we will expand from there:

```bash
oemcp=$(reg.exe query "HKLM\\SYSTEM\\CurrentControlSet\\Control\\Nls\\CodePage" /v OEMCP 2>&1 | sed -n 3p | sed -e 's|\r||g' | grep -o '[[:digit:]]*')
chcp.com $oemcp
powershell.exe ... 
chcp.com 65001
```

`powershell.exe` have an interesting way in its input/output encoding, that it will follow the system code page. You can actually type `[Console]::OutputEncoding` and `[Console]::InputEncoding` in Powershell, and you can actually see what language/encoding/code page your console you are currently using (Mine here is already corrupted during the debug):

![My messed up encoding](https://cdn.patrickwu.space/posts/dev/wsl/wsl-winps-encoding-1.png)

However, As we mentioned in [powershell raster font problem article](https://patrickwu.space/2019/08/03/wsl-powershell-raster-font-problem/), we need UTF-8 all the time. Thus, we can force input to follow your system language by using `[Console]::InputEncoding = [System.Text.Encoding]::GetEncoding(<codepage>)` command, where `codepage` is... well... code page; we can then force the output to use UTF-8 by using `[Console]::OutputEncoding = [System.Text.Encoding]::UTF8` command. Both commands should be executed before executing your command, so it should be something like this:

```bash
powershell.exe ... -Command "[Console]::OutputEncoding = [System.Text.Encoding]::UTF8; [Console]::InputEncoding = [System.Text.Encoding]::GetEncoding(<codepage>); <command>"
```

Combining from the base, we got the following:

```bash
oemcp=$(reg.exe query "HKLM\\SYSTEM\\CurrentControlSet\\Control\\Nls\\CodePage" /v OEMCP 2>&1 | sed -n 3p | sed -e 's|\r||g' | grep -o '[[:digit:]]*')
chcp.com $oemcp
oemcp=$(($oemcp+0))
powershell.exe ... -Command "[Console]::OutputEncoding = [System.Text.Encoding]::UTF8; [Console]::InputEncoding = [System.Text.Encoding]::GetEncoding($cp); <command>"
chcp.com 65001
```

And now the problem is now gone! Following is demo of the fixed bug in `wslu`:

![](https://cdn.patrickwu.space/posts/dev/wsl/wsl-winps-encoding-2.png)

Now I wish there won't be another sequel to this problem...
