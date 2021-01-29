---
title: Start apps without systemd in WSL
date: 2018-08-14 13:36:34
tags:
- WSL
- SQL
---

# TL;DR

This is an article explaining how to start some service without `systemd` on WSL and some thoughts.

<!--more-->

## The problem

I recently have a nodejs server with Redis and MySQL to set up and thinking that using ubuntu will be a lot easier to set up than doing it in Windows, I tried to set up on WSL. When I tried to set up using MySQL, I realized that MySQL now uses `systemd` to start server instead of good ol' `service`, and WSL has not yet supported systemd and [the first issue report about systemd](https://github.com/Microsoft/WSL/issues/994) is still open and not solved. This means we cannot use anything related to `systemd` or `systemctl`. Same thing happened when I try to setup the openSSH Server on openSUSE, as openSUSE do not provide `service` version of openSUSE Server startup script.

## The solution

the solution is simple, just use its still existed `init.d` service on system, that is:

```bash
#mysql
sudo /etc/init.d/mysql start

#opensuse openSSH server
sudo /usr/sbin/sshd
```

but this reminds me that as the adoption of `systemd` in Debian and Ubuntu in the late 2013, it becomes increasingly controversy, with bug exploited, and system abandon it, `systemd` is just not friendly. Software should have at least two methods (`init.d`/`systemd`) for service control so that all *inx can be properly used. 