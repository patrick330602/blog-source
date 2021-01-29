---
title: Awesome tools that enhance your WSL experience
date: 2017-02-24 09:22:43
tags:
- WSL
- Ubuntu
- Windows
---
I have been trying to use X11 apps using Bash on Ubuntu on Windows 10 [before](http://www.patrickwu.cf/2016/08/LDE-On-Windows-With-Chinese/) using VcXsrv. However, since [Microsoft update Ubuntu to 16.04 since build 14936](http://www.omgubuntu.co.uk/2016/10/windows-10-linux-subsystem-ubuntu-16-04), this method no longer work. However, with the correct setup and awesome tools, Bash on Ubuntu on Windows 10(WSL) can be still as awesome as you will expected.

![My Personal Setup](https://cdn.patrickwu.space/posts/dev/wsl/mine.png)
<!--more-->

## WSLtty

<https://github.com/mintty/wsltty>

WSLtty is a sub-project by MinTTY. This tool use minTTY as a terminal of Bash on Ubuntu on Windows/WSL.

![WSLtty](https://cdn.patrickwu.space/posts/dev/wsl/WSLtty.png)

Using WSLtty, you can:

- Replace command prompt with minTTY;
- Start a WSL bash in the current folder/directory with one click;
- Start a WSL bash in the WSL user home with one click;
- Start a WSL login bash with one click;
- Start a WSL bash in the Windows %USERPROFILE% home with one click;


## wsl-terminal

<https://github.com/goreliu/wsl-terminal>

An alternatives to WSLtty. it is more simple compared to WSLtty.

![wsl-terminal](https://raw.githubusercontent.com/wiki/goreliu/wsl-terminal/images/wsl-terminal-3.png)

Using wsl-terminal, you can:

- Open a WSL terminal in current directory using `open-wsl.exe`;
- Add a `Open WSL Here` context menu to explorer.exe using `tools/add-open-wsl-here-menu.js` (and run `tools/remove-open-wsl-here-menu.js` to remove it);
- Can run any .sh (and any others like .py/.pl/.php) script files in wsl-terminal using `run-wsl-file.exe`, support `Open With` context menu in explorer.exe;
- Open any text files in vim (in wsl-terminal) using `vim.exe`, support `Open With` context menu in explorer.exe. `vim.exe` can be renamed to `emacs.exe/nvim.exe/nano.exe/...` to open files in `emacs/nvim/nano/...`.

## WSLBridge


<https://github.com/rprichard/wslbridge>

wslbridge is a Cygwin program that allows connecting to the WSL command-line environment over TCP sockets, as with ssh, but without the overhead of configuring an SSH server.

WSLtty actually uses it to achieve the connection.

## cbwin

<https://github.com/xilun/cbwin>

cbwin is a tool to launch Windows programs from "Bash on Ubuntu on Windows" (WSL).

Using cbwin, you can:

- Win32 command line tools in the console, invoked from WSL
- Win32 command line tools with redirections to WSL (stdin/stdout/stderr to or from pipe/file)
- suspend/resume propagation (Ctrl-Z suspends the Win32 processes, `fg` resumes them)
- exit codes propagation
- launch "detached" GUI Windows programs (uses `start` of `cmd`)

## alwsl

<https://github.com/alwsl/alwsl>

I am a extreme fan of Arch, so as you can see, I feel pretty disappointed when WSL is using Debian. However, this will be no longer a problem by using alwsl. alwsl is a tool to install Arch Linux as the WSL (Windows Subsystem for Linux) host.

![alwsl](http://imgur.com/1T2dyE5.png)

## WSL-Distribution-Switcher

https://github.com/RoliSoft/WSL-Distribution-Switcher

This is an set of python Scripts to replace the distribution behind Windows Subsystem for Linux with any other Linux distribution published on Docker Hub. It support a wild range of Distribution including Fedora, Debian, CentOS and more. 

![Windows Subsystem for Linux Distribution Switcher](https://lab.rolisoft.net/images/wslswitcher/install.png)

## Jekyll Installer For WSL

<https://github.com/patrick330602/Jekyll-Installer-For-WSL>

Install Jekyll can be hard in WSL, since Ruby cannot be installed properly using rvm or apt-get. I personally wrote this script for install Jekyll easier with one line of code.

![Jekyll Installer For Windows Subsystem for Linux](https://cdn.patrickwu.space/posts/dev/wsl/jiwsl.png)

## Hexo Installer For WSL

<https://github.com/patrick330602/Hexo-Installer-For-WSL>

Just like Jekyll, Hexo also cannot be installed using normal method, with nvm or apt-get. Again, I personally wrote this script for install Hexo easier with one line of code. But be careful that this script is not supported in Build 15031 due to a known bug.

![Hexo Installer For Windows Subsystem for Linux](https://cdn.patrickwu.space/posts/dev/wsl/hiwsl.png)