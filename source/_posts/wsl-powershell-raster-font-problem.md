---
title: "How to stop Powershell from resetting your WSL console font"
date: 2019-08-03 10:27:26
tags:
- Powershell
- WSL
---
This problem exists for a long time, starting from the beginning of my development of `wslu` project. Someone told me there was such problem but unfortunately I am unable to reproduce such problem for a very long time (Later turned out that using the experimental UTF-8 option at the time in Insider builds prevent this issue). Few days ago, someone opened an issue ([wslutilities/wslu issue #83](https://github.com/wslutilities/wslu/issues/83)) so that I have to took it more seriously and I created a Virtual Machine trying to reproduce the issue. And I did.

Tracing down and found out this is an existing issue for a long time([Microsoft/Terminal issue #280](https://github.com/microsoft/terminal/issues/280)). As one Microsoft employee on `wslu`'s issue said,

> Powershell sets and resets the system codepage, which it shouldn't be doing. This can cause the fonts to reset to "raster fonts". 

So what does this mean? Well, by default, system have a global variable called the **OEM Codepage** (`oemcp`). It is usually a 3 to 4-digit number determined by your system language, and this is intended to help apps to determine what character set it needs to use. For example, "**OEM - United States**" is Codepage 437. There is a lot more, checkout Wikipedia page of [DOS code pages](https://en.wikipedia.org/wiki/Category:DOS_code_pages) and [Windows code pages](https://en.wikipedia.org/wiki/Windows_code_page). 

Among them, there is one special codepage: 65001, or better known as **UTF-8**. In latest version of Windows, you can set the OEM Codepage to UTF-8 by going to Control Panel -> Clock and Region -> Change date, time or number formats -> Administrative -> Change System Locale and choose **Use Unicode UTF-8**:

![](https://cdn.patrickwu.space/posts/dev/ps-raster/1.png)

And now problem surfaces: WSL Distro consoles do not use **OEM Codepage**, they use **Unicode UTF-8 Codepage**; but PowerShell uses **OEM Codepage**. So, if you don't set system to use the Unicode UTF-8, executing mixing commands with bash and PowerShell cause `powershell.exe` to reset codepage forcefully which leads to resetting the console to raster font. 

Now it is clear what caused the problem, so the solution become simple:

1. set to OEM Codepage before you do powershell;
2. powershell something;
3. set back to WSL Distro console Codepage 65001.

You can use the `chcp.com` (**ch**ange **c**ode**p**age) to change codepage.

So for the first step, you should get **OEM Codepage** from registry (in WSL):

```bash
oemcp=$(reg.exe query "HKLM\\SYSTEM\\CurrentControlSet\\Control\\Nls\\CodePage" /v OEMCP 2>&1 | sed -n 3p | sed -e 's|\r||g' | grep -o '[[:digit:]]*')
```

Then set the WSL console codepage to OEM Codepage:

```bash
chcp.com $oemcp
```

Then you powershell something. When complete, set back to UTF-8 Codepage:

```bash
chcp.com 65001
```

Overall, it should be something like this:

```bash
oemcp=$(reg.exe query "HKLM\\SYSTEM\\CurrentControlSet\\Control\\Nls\\CodePage" /v OEMCP 2>&1 | sed -n 3p | sed -e 's|\r||g' | grep -o '[[:digit:]]*')
chcp.com $oemcp
powershell.exe ...
chcp.com 65001
```

Now, the problem is gone!

![](https://cdn.patrickwu.space/posts/dev/ps-raster/2.png)

**UPDATE**: Some spelling and grammar error fixes and better elaboration.