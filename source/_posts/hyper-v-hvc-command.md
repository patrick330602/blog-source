---
title: Make Hyper-V great again -- hvc command
date: 2019-02-16 21:21:05
tags:
  - Hyper-V
  - Linux
  - Windows
---

# TL;DR

Hyper-V is an awesome native hypervisor for Windows, and It works surprisingy well with not only Windows but also Linux, freeBSD and Docker. But when using Hyper-V, it used to be not easy when you want to get information about your VMs and interact them with SSH. But now, with `hvc` command, these problems can be solved really easily.

![My Virtual Machines](https://cdn.patrickwu.space/posts/dev/hvc/1.png)

## Before we start...

Not all feature is available to the platform. This tutorial only tested with Windows Server 2016, Windows Server 2019, Ubuntu and Debian. Please try yourself whether your VM works.

Also, use an elevated console when executing the command. The easiest why you can simplify this process is to download [scoop](https://scoop.sh) and install `sudo` using `scoop install sudo`. Now you can simply type `sudo hvc` instead of opening an elevated console.

## VM Imformation

`hvc` allows you to show basic information about your VMs. Using `hvc list` can show you a list of Virutal machines you have:

![VM information](https://cdn.patrickwu.space/posts/dev/hvc/2.png)

You can also get specific inforamtion about your VM such as VM ID, its IP address and its current status using `hvc id <Name of VM>`, `hvc ip <Name of VM>` and `hvc state <Name of VM>`: 

![VM specific information](https://cdn.patrickwu.space/posts/dev/hvc/3.png)

As for the name, if your VM have a name that contains space, you shoud quote it:

![VM information but with a complicated name](https://cdn.patrickwu.space/posts/dev/hvc/4.png)

## VM Mangement

You can complete some simple management of your VM such as start or stop using `hvc <command> <name of VM>`:

| Command | Description |
| ------- | ----------- |
| start | Starts a VM. |
| stop | Shuts down a VM. |
| kill | Powers off a VM. |
| restart | Reboots a VM. |
| reset | Issues a hard reset to a VM. |

But keep in mind that nothing will be shown if the action is successful.

![Successful and unsuccessful command execution](https://cdn.patrickwu.space/posts/dev/hvc/5.png)

## VM Connection

You can connect to your virtual machine using different ways via `hvc`, like socket or ssh.

You can connect to your VM's serial port using `hvc serial <VM>`; You can also connect to your VM via a socket connection using `hvc nc [options] <VM> <port>`; You can also directly open the VM Graphic Console using `hvc console <VM>`. 

Here I will mainly talk about its feature on SSH. `hvc` provides `ssh` and `scp`, which works just like normal `ssh` and `scp` but you can just input its VM name instead of its IP address. Here is a small demo:

![hvc SSH demo](https://cdn.patrickwu.space/posts/dev/hvc/6.png)

To use this command, the only thing you have to do is to setup SSH server on your VM. for Windows, you just have to install OpenSSH Server to start; for Linux, besides normal SSH server setup, don't forget that install Hyper-V tools before using the command. For example, Debian 9 need `hyperv-daemons` package for `hvc ssh` to work. For other Linux, please check [here](https://docs.microsoft.com/en-us/windows-server/virtualization/hyper-v/supported-linux-and-freebsd-virtual-machines-for-hyper-v-on-windows) for the supported Linux distros.

## Final thoughts

These command really simplified my life and I no longer need to pin Hyper-V Manager in the taskbar. Just have a try, you will love it.