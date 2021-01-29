---
title: Enable sound in the Linux Subsystem in Windows 10
date: 2017-05-10 21:59:23
tags:
- WSL
---
Linux Subsystem of Windows is awesome, but it seems missing something... Yes, it misses sound, and several apps I am developing requires the sound to be played. Luckily, someone have found a solution to solve this problem by creating a link of pulseaudio between Windows 10 and Linux Subsystem. Here I got a summary of the solution, since the original is a mess to be read.

![Music can be played normally thorugh CMUS](https://cdn.patrickwu.space/posts/dev/wsl/music_on_bash.png)
<!--more-->
On the Linux Subsystem side:

1. Add repository by command`add-apt-repository ppa:aseering/wsl-pulseaudio`
2. If you are running Ubuntu 16.04 instead of 14.04, open `/etc/apt/sources.list.d/aseering-ubuntu-wsl-pulseaudio-xenial.list` and change `xenial` to `trusty`
3. Install pulseaudio by command `sudo apt-get update && sudo apt-get install pulseaudio`

On Windows side:
1. Download PulseAudio [here](http://bosmans.ch/pulseaudio/pulseaudio-1.1.zip) on Windows.
2. Extract all the files to `%AppData%\PulseAudio`.
3. Press Win+R, type `shell:startup` and create a file named `start_pulseaudio.vbe` with following contents:
```vb
set ws=wscript.createobject("wscript.shell") 
ws.run "C:\Users\Patrick\AppData\Roaming\PulseAudio\bin\pulseaudio.exe --exit-idle-time=-1",0 
```
4. Open `%AppData%\PulseAudio\etc\pulse\default.pa`, and add this line to the end of the file:
`load-module module-native-protocol-tcp auth-ip-acl=127.0.0.1 auth-anonymous=1`
5. Run `start_pulseaudio.vbe` and do not allow 'pulseaudio' access to any of your networks sine it doesn't need access.

Now everything is done. Enjoy the music!