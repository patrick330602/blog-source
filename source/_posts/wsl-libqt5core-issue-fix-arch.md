---
title: fix Octave LibQt5Core issue in WSL Arch Linux
date: 2018-11-05 10:43:24
tags:
- WSL
- Arch Linux
---
Recently I started to do things in the Arch Linux since some people request to bring my [wslu](https://github.com/wslutilities/wslu) to ArchWSL(https://github.com/yuk7/ArchWSL). I found there is an issue when using Octave when it brings this error:
```
error while loading shared libraries: libQt5Core.so.5: cannot open shared object file: No such file or directory
```

This is due to the fact that WSL kernel do not support `renameat2()` system call.

To fix this specific issue, run:
```bash
sudo strip --remove-section=.note.ABI-tag /usr/lib64/libQt5Core.so.5
```

To fix all issue related to this, run:
```
find /lib /usr/lib /usr/libexec -name '*.so' | xargs strip --remove-section=.note.ABI-tag
```

But be careful, this action will be dangerous.

After executing, this problem will be solved.


> Credit: 
> <https://superuser.com/questions/1347723/arch-on-wsl-libqt5core-so-5-not-found-despite-being-installed#1348051>
> <https://github.com/Microsoft/WSL/issues/3023>