---
title: "Further Research on WSL Interoperability"
date: 2017-05-28 21:44:54
tags:
- WSL
---
The Windows Subsystem for Linux can invoke native Windows binaries and be invoked from a Windows command line since build 14951. This feature allows us to have a better experience between Windows 10 and WSL. To use it better, I did some research on path since I try to build a collection of utility of WSL. Here are some of the result from my little research.

## cannot use value from pipeline

Some of the apps have a behaviour similar to Linux apps such as Java that their print out value cannot be used in pipeline, such as `reg.exe`. To solve this, just add `2>&1` to the end of the application execution.  
<!--more-->

## Remaining Command Prompt

Sometimes, if command executed in something like `cmd.exe` do not execute well, the command prompt will remains like:

![](https://cdn.patrickwu.space/posts/dev/wsl/wsl-path-2.png)

To solve this add a exit at the every end of the line, or do not use `/k` parameter instead of `/c`:

```bash
cmd.exe /k "exec-sth && exit"
cmd.exe /c "exec-sth" # they are the same
```

## "Unable to translate current working directory. Using C:\Windows\system32"

When executing in shell script directly using `cmd.exe` or `powershell.exe`, sometimes this line will be popped up without knowing why. This is because `cmd.exe` and `powershell.exe` cannot translate the Linux path to Windows path. To solve this is actually pretty easy; just cd to a Windows path such as `/mnt/c` then execute `cmd.exe` or `powershell.exe`. 

## Windows Application With Admin Privileges

Some of the application cannot be called through Bash. Although Official Website do provide direct call of Windows Binary such as:

```bash
/mnt/c/Windows/System32/cmd.exe /C dir
/mnt/c/Windows/System32/PING.EXE www.microsoft.com
```

However, this is not working if you want to execute apps that requires admin privileges. A 'Permission denied' will be shown if you exec directly.

![](https://cdn.patrickwu.space/posts/dev/wsl/wsl-path-1.png)

However, after some investigations, I find a way of executing such applications. the code is pretty simple:

```bash
cd /mnt/c && cmd.exe /c "start "\\windows\\path\\to\\app.exe""
```

`cd /mnt/c` is to fix the previous problem. this command will treat the Windows application to run as normal Windows user instead of a linux user, so that you will have permission to execute the application within WSL.

## reg.exe
Registry is one of the way of receiving information about Windows System, and they can be directly called using `reg.exe` in the format like this:`reg.exe query "Registery Folder" /v "Register Key"`

However, the information called from the register is like this:
```bash
% reg.exe query "HKLM\Software\Microsoft\Windows NT\CurrentVersion" /v "CurrentBuild"

HKEY_LOCAL_MACHINE\Software\Microsoft\Windows NT\CurrentVersion
    CurrentBuild    REG_SZ    16193

%
```

so I add a pipeline to the comment:

```bash
reg.exe query "HKLM\Software\Microsoft\Windows NT\CurrentVersion" /v "CurrentBuild" 2>&1 | sed -n 3p | awk '{ print $3 }' | sed -e 's|\s||g'
```

this will print out just the value:
```bash
% reg.exe query "HKLM\Software\Microsoft\Windows NT\CurrentVersion" /v "CurrentBuild" 2>&1 | sed -n 3p | awk '{ print $3 }' | sed -e 's|\s||g'
16193
%
```