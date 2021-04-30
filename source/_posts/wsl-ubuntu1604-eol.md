---
title: 'Ubuntu 16.04 End-Of-Life on WSL: What you should do'
date: 2021-04-30 19:47:43
tags:
- Ubuntu
- WSL
---

Ubuntu 16.04 LTS reached End-Of-Life today. This also means Ubuntu 16.04 on Windows also will get off from the Microsoft Store. For people who still use Ubuntu 16.04 on WSL, unless you have purchased [ESM (Extended Security Maintainance)](https://ubuntu.com/security/esm) as part of Ubuntu Advantage, you will not be able to upgrade packages on Ubuntu 16.04 LTS. If you still have a WSL instance running Ubuntu 16.04 LTS, you can take the following actions.

## Move Ubuntu 16.04 images to another Ubuntu UWP

As Ubuntu 16.04 is removed from the Microsoft Store, it is suggested to move your WSL system to another supported UWP version of Ubuntu:

- [Ubuntu 18.04 LTS](https://www.microsoft.com/store/apps/9N9TNGVNDL3Q)
- [Ubuntu 20.04 LTS](https://www.microsoft.com/store/apps/9n6svws3rx71)
- [Ubuntu](https://www.microsoft.com/store/apps/9NBLGGH4MSV6)

Download one of them, open it once, and proceed to the following steps:

### Export and Remove Ubuntu 16.04 LTS

Export your current distro to a tarball:

```
wsl --export Ubuntu-16.04 ubuntu1604.tar
```

It would be best if you also used this as a copy of your original Ubuntu 16.04 backup.

### Install

#### For Ubuntu 20.04 LTS

Execute the following: 

```
wsl --unregister Ubuntu-20.04
wsl --import Ubuntu-20.04 C:\Users\<Your Windows Username>\AppData\Local\Packages\CanonicalGroupLimited.Ubuntu20.04onWindows_79rhkp1fndgsc\LocalState .\ubuntu1604.tar
ubuntu2004.exe config --default-user <Your WSL Username>
```
#### For Ubuntu 18.04 LTS

Execute the following: 

```
wsl --unregister Ubuntu-18.04
wsl --import Ubuntu-18.04 C:\Users\<Your Windows Username>\AppData\Local\Packages\CanonicalGroupLimited.Ubuntu18.04onWindows_79rhkp1fndgsc\LocalState .\ubuntu1604.tar
ubuntu1804.exe config --default-user <Your WSL Username>
```
#### For Ubuntu

Execute the following: 

```
wsl --unregister Ubuntu
wsl --import Ubuntu C:\Users\<Your Windows Username>\AppData\Local\Packages\CanonicalGroupLimited.UbuntuonWindows_79rhkp1fndgsc\LocalState .\ubuntu1604.tar
ubuntu.exe config --default-user <Your WSL Username>
```

And then you are done! Now we should upgrade your system to a supported one:

## Upgrading Ubuntu 16.04 to the latest supported version

You should still be able to upgrade to the latest supported version when this article is written. But if you miss the deadline, you should follow the following method to perform the release upgrade.

### Update sources.list

To begin the upgrade, make sure you have a `sources.list` like the following:

```
# Required
deb http://old-releases.ubuntu.com/ubuntu/ xenial main restricted universe multiverse
deb http://old-releases.ubuntu.com/ubuntu/ xenial-updates main restricted universe multiverse
deb http://old-releases.ubuntu.com/ubuntu/ xenial-security main restricted universe multiverse

# Optional
#deb http://old-releases.ubuntu.com/ubuntu/ CODENAME-backports main restricted universe multiverse
```

You can use `-backports` and or `-proposed` if you want. 

### Dependencies
It would help if you also made sure meta-package `ubuntu-wsl` is installed so the upgrade can continue without problems.

### Run the upgrade
After you've done the above, run the updates and then the upgrade as usually:

```
sudo apt-get update
sudo apt-get dist-upgrade
sudo do-release-upgrade
```

