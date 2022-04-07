---
title: >-
  Solving Native Docker (Not Docker Desktop) unable to start on Ubuntu 20.10+ on
  WSL
tags:
  - Docker
  - WSL
date: 2021-03-09 15:47:28
---

Right now, you know you have two options to run docker on WSL2: You can

1. Install using the package manager version;
2. Install using Docker Desktop for Windows and enable the WSL2 support.

The option 2 would usually a great solution for users; but sometimes, we prefer option 1 to do some works more natively. 

However, Recently we got a [bug report](https://bugs.launchpad.net/ubuntu-wsl-integration/+bug/1908539) that people could not use Docker on UoWCP (Ubuntu on Windows Community Preview, based on Ubuntu Hirsute Hippo (21.04)) as they cannot start the daemon.

Some investigation leads to an interesting find: When trying to run `dockerd`, it will throw an error:

```
...
INFO[2021-03-09T15:06:20.839195000+08:00] Loading containers: start.
INFO[2021-03-09T15:06:20.885624800+08:00] stopping event stream following graceful shutdown  error="<nil>" module=libcontainerd namespace=moby
INFO[2021-03-09T15:06:20.885865900+08:00] stopping healthcheck following graceful shutdown  module=libcontainerd
INFO[2021-03-09T15:06:20.886012400+08:00] stopping event stream following graceful shutdown  error="context canceled" module=libcontainerd namespace=plugins.moby
failed to start daemon: Error initializing network controller: error obtaining controller instance: unable to add return rule in DOCKER-ISOLATION-STAGE-1 chain:  (iptables failed: iptables --wait -A DOCKER-ISOLATION-STAGE-1 -j RETURN: iptables v1.8.7 (nf_tables):  RULE_APPEND failed (No such file or directory): rule in chain DOCKER-ISOLATION-STAGE-1
 (exit status 4))
```

I noticed `iptables` being used is a `nftables` version. Turns out, Starting from version 20.10, Ubuntu switched the firewall system to `nftables` in like mentoned [here](https://net2.com/ubuntu-20-10-comes-with-nftables-as-firewall/); But unfortunately, using `nftables` natively requires Linux Kernel 5.8, where the latest Kernel version for WSL is 5.4. 

Fortunately, Ubuntu still have a legacy version of `iptables` kept in the system. To do it, you can simlpy use `update-alternatives --config iptables` to change it:

```
$ sudo update-alternatives --config iptables
There are 2 choices for the alternative iptables (providing /usr/sbin/iptables).

  Selection    Path                       Priority   Status
------------------------------------------------------------
* 0            /usr/sbin/iptables-nft      20        auto mode
  1            /usr/sbin/iptables-legacy   10        manual mode
  2            /usr/sbin/iptables-nft      20        manual mode

Press <enter> to keep the current choice[*], or type selection number: 1
update-alternatives: using /usr/sbin/iptables-legacy to provide /usr/sbin/iptables (iptables) in manual mode
```

Afterwards, restart the daemon and you will notice docker works normal again!