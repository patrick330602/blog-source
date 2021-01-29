---
title: Fix the apt warning on Kali Linux on Windows 10
date: 2018-04-12 19:52:37
tags:
- WSL
- Kali Linux
---
When Running Kali Linux Subsystem on Windows 10, using `apt` will usually produce the following warning like in the graph:

![Issue](https://cdn.patrickwu.space/posts/dev/wsl/wsl-kali-fix.png)

This warning will not prevent you from using using `apt`, but it is still really annoying. After some browsing and checking, this is due to the reason that the kernel doesn't support `seccomp` as WSL is an emulation layer for Windows to Linux kernel. 
Solving the problem is really easy - Disable the seccomp warning using the following command:

```sh
echo 'apt::sandbox::seccomp "false";' | sudo tee /etc/apt/apt.conf.d/999seccomp
```

After that, the `apt` will run normally without the annoying warning.

