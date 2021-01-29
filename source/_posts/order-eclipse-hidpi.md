---
title: Enable HiDPI On Order version of Eclipse
date: 2017-05-10 21:48:48
tags:
- Eclipse
- HiDPI
---

Eclipse is commonly used worldwide as a old and awesome IDE, but it is also awful because of its unfriendliness to the user. Even Google stopped using Eclipse as the default platform and swap to IntelliJ(and it is awesome, especially PHPStorm, believe me). Unfortunately, our course still use a special customized order version of eclipse 4.4 for coding for homework, which is really annoying. What's worse, It does not support HiDPI, which made me hard to see the word and icons. Luckily, there is a way to fix this problem for the version before 4.5. Keep in mind that latest version of eclipse already fixed this problem. 

![Fixed Screenshot](https://cdn.patrickwu.space/posts/dev/eclipse-view.png)
<!--more-->
To do this is very simple. First, we have to enable the External Manifest through Registry Editor:

+ Navigate to `HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Windows\CurrentVersion\SideBySide` and create a DWORD Value called `PreferExternalManifest` and set it to one. 

![Completed Registry](https://cdn.patrickwu.space/posts/dev/eclipse-reg.png)

Then, navigate to the root folder and create a file named `eclipse.exe.manifest` with following content.

```xml
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<assembly xmlns="urn:schemas-microsoft-com:asm.v1" manifestVersion="1.0" xmlns:asmv3="urn:schemas-microsoft-com:asm.v3">
    <description>eclipse</description>
    <trustInfo xmlns="urn:schemas-microsoft-com:asm.v2">
        <security>
            <requestedPrivileges>
                <requestedExecutionLevel xmlns:ms_asmv3="urn:schemas-microsoft-com:asm.v3"
                               level="asInvoker"
                               ms_asmv3:uiAccess="false">
                </requestedExecutionLevel>
            </requestedPrivileges>
        </security>
    </trustInfo>
    <asmv3:application>
        <asmv3:windowsSettings xmlns="http://schemas.microsoft.com/SMI/2005/WindowsSettings">
            <ms_windowsSettings:dpiAware xmlns:ms_windowsSettings="http://schemas.microsoft.com/SMI/2005/WindowsSettings">false</ms_windowsSettings:dpiAware>
        </asmv3:windowsSettings>
    </asmv3:application>
</assembly>
```

This will disable the DPI awareness of Eclipse so that it will be displayed properly.