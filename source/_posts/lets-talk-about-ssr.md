---
title: Let's talk about Shadowsocks R
tags:
  - SSR
  - Server
date: 2018-06-30 15:44:24
---
# TL;DR

This article is about how to configure Shadowsocks R properly in Windows 10 Linux Subsystem, UWP and more, and some extra stuff I want to talk about.

<!--more-->

## Some key notes first

### Install

```bash
wget --no-check-certificate https://raw.githubusercontent.com/teddysun/shadowsocks_install/master/shadowsocksR.sh
chmod +x shadowsocksR.sh
./shadowsocksR.sh 2>&1 | tee shadowsocksR.log
```

### Uninstall

./shadowsocksR.sh uninstall

### Commands

- **Start** - `/etc/init.d/shadowsocks start`
- **Stop** - `/etc/init.d/shadowsocks stop`
- **Restart** - `/etc/init.d/shadowsocks restart`
- **Status** - `/etc/init.d/shadowsocks status`

### Path
- **Configuration File** - `/etc/shadowsocks.json`
- **Log File** - `/var/log/shadowsocks.log`
- **Installation Location** - `/usr/local/shadowsocks`

### Sample Configuration

```json
{
"server":"0.0.0.0",
"server_ipv6": "[::]",
"local_address":"127.0.0.1",
"local_port":1080,
"port_password":{
    "8989":"password1",
    "8990":"password2",
    "8991":"password3"
},
"timeout":300,
"method":"aes-256-cfb",
"protocol": "origin",
"protocol_param": "",
"obfs": "plain",
"obfs_param": "",
"redirect": "",
"dns_ipv6": false,
"fast_open": false,
"workers": 1
}
```

### Clients

- [Windows(ShadowsocksR)](https://github.com/shadowsocksrr/shadowsocksr-csharp/releases/) 
- [Android(SSRR)](https://github.com/shadowsocksrr/shadowsocksr-android/releases/) 
- [iOS(Shadowrocket)](https://itunes.apple.com/us/app/shadowrocket/id932747118)

## Some fixes

### UWP

Most of UWP apps cannot use proxy to connect with SSR since they are not allowed to connect to `locahost`, In order to remove the Loopback restriction, install **Fiddler4**, and when you open the app and UWP Loopback is not enabled, there will be a notification to tell you enable, or if not showing up, you can clicking the `WinConfig`, or you can directly run it from `C:\Users\<username>\AppData\Local\Programs\Fiddler\EnableLoopback.exe`. Click `Exempt All` and then click `Save Changes`. Now UWP can use localhost proxy without any issues.

### Windows 10 Linux Subsystem

Windows 10 Linux Subsystem can use loopback without issue of course, but the proxy is not properly applied. In order to do that, add this to your configuration files such as `.bashrc`:

```bash
alias socks='ALL_PROXY=socks5://127.0.0.1:1080/ \
        http_proxy=http://127.0.0.1:1080/ \
        https_proxy=http://127.0.0.1:1080/ \
        HTTP_PROXY=http://127.0.0.1:1080/ \
        HTTPS_PROXY=http://127.0.0.1:1080/'
```

source the file, and you can run `socks` to set all application to bypass proxy, or you can run things like `socks wget` to use it per command.

## And some more things

Now, let's talk about Shadowsocks R and GFW. GFW is banning more and more IP, and my Google Cloud Server did not survive too. It is harder and harder to use VPN in China, and more are banned, even include the VPN and VDI of my school (It is now fine, seems they just pollute the IP). I have been never this angry. If they really have to blame westerners not giving them the latest technology, just look at themselves. They block CRAN. They block Tensorflow. They corrupt Ubuntu, Debian, PiPY and OpenSUSE's server. Programmers, engineers, data scientists and students like me need a reliable connection to reach latest technologies, and you know what? They block them, how funny it is. What? You say using Ali or PKU server? No, I will not. They are unsafe, with limited packages. Ubuntu archive server in China is not even with the Official archive server. It's NOT reliable. Deepin? They even cannot survive. CEO just quit several months ago, and using Deepin makes you fell into the dependency hell. "Azure Powered By 21Vianet", "iCloud in Guizhou", "China LinkedIn", big corporation compromised. But for small individuals, we have a choice. Shadowsocks R(R) is still the most reliable way to bypassing GFW, sadly Shadowsocks R is so dead. Shadowsocks is saved by community, and the author of SSRR is criticized because a propaganda the author made. It's hard to bypass GFW, but you know what? Life is also hard. deal with it.
