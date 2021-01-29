---
title: "Collection of Continuing Updating Notes On Linux"
date: 2019-08-20 00:00:10
tags:
- Linux
- Ubuntu
- SSH
- Antergos
---
# What?

Well, I always forget about small tricks on get something done on linux and sometimes even when I keep it locally, I forget about where the heck they are and search them again. But these small tips are just so tiny that they cannot be kept as articles. So now I will keep everything here so I won't forget about them.

## Give sudo password a star

You first `visudo`, then append `,pwfeedback` on the line `Defaults     env_reset`, and then it's done.

## Disable Error Reporting in Ubuntu

```sh
sudo service apport stop
sudo gedit /etc/default/apport
```

change `enabled=1` to `enabled=0`

## Fix Time Differences in Ubuntu & Windows

In previous Ubuntu editions, you can edit the config file `/etc/default/rcS` to disable UTC.

In Ubuntu 16.04, open terminal (**Ctrl+Alt+T**) and run the command below instead:

`timedatectl set-local-rtc 1 --adjust-system-clock`

To check out if your system uses Local time, just run:

`timedatectl`

you’ll the local time zone is in use in the Warning section.

## SSH folder permission

 - `.ssh` folder: `700`
 - `*.pub`: `644`
 - pravate keys, config, known_hosts: `600`

## How to display Asian character poroperly in Antergos

I tried multiple ways of installing Chinese font like `ttf-arphic-uming`, `adobe-source-han-sans-otc-fonts` and `noto-fonts-cjk`. This packages solved the problem in system, but not in browsers like Chrome and Firefox.

Reference: [this post](https://bbs.archlinux.org/viewtopic.php?id=158897)

1. Remove `ttf-google-fonts`(Google Web Font)
2. Install one of them: `ttf-arphic-uming`, `adobe-source-han-sans-otc-fonts`, `noto-fonts-cjk`
3. rebuild the font cache using `sudo fc-cache -f -v` to regenerate font cache. 

To keep it safe, Here is a Ubuntu font config:

| Font Location |     Font  Name |
| :------------ | -------------: |
| Window Titles | Cantarell Bold |
| Interface     |         Ubuntu |
| Documents     |           Sans |
| Monospace     |    Ubuntu Mono |

## Manually Install Linux kernel in Ubuntu

Download Latest Kernel Here:
<http://kernel.ubuntu.com/~kernel-ppa/mainline/>

### x86

Download `headers-all`, `headers-i386`, `image-i386` and install all the deb.

### x64

Download `headers-all`, `headers-amd64`, `image-amd64` and install all the deb.