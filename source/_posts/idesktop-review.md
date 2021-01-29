---
title: 'iSoft Desktop: What’s the deal?'
tags:
  - Linux
date: 2020-03-28 23:29:16
---


Some times earlier, I noticed a piece of news: iSoft Desktop (普华桌面操作系统) is now the required Linux Distro for learning the standards of Linux in Mainland China according to multiple resources [^1] [^2]. 

This news arouses my interest, as most so-called "Made-in-China Operating System" are based on Linux while being close-sourced. Good examples are StartOS and Redflag OS, where both the systems are Linux-based but not open-sourced. 

On the contrary, Linux distributions made by private corporations in Mainland China are not only well-made but also properly following open source licenses. Deepin Linux and Ubuntu Kylin (Ubuntu Kylin is the collaboration project between Kylin Inc. and Canonical China) are two great examples. 

[^1]: https://www.cnbeta.com/articles/tech/912899.htm
[^2]: https://linux.cn/article-11596-1.html

To find out, I tried to get this Linux distribution. It is tough to download this distribution. The download link for the distribution is hidden deep in the company website. 

As expected from a nation-backed product, I found that you need to provide the email, name, phone number, and location to download the image of the distribution, which is much information needed. 

What's worse, the download speed of the ISO is slow. This slowness doesn't depend on your local network speed; the culprit is their server bandwidth. So I made it easy for you and hosted it on my CDN. You can click [here](https://cdn.patrickwu.space/posts/dev/linux-review/idesktop/isoft-desktop-v4.1-x86_64.iso) to download the image. 

![You need to fill the information before downloading](https://cdn.patrickwu.space/posts/dev/linux-review/idesktop/1.png)

The latest version of iSoft Desktop is 4.1, and the image size is 3.6 GB. I used the automatic detection feature from Parallels Desktop to figure out its base distribution. Unfortunately, Parallels Desktop failed to do so, which means at least this is not just a simple fork of an existing Linux distribution (I was wrong here as I found later that it's based on Fedora). 

During installation, I notice a very interesting thing: It has the mention of GPLv2 in their EULA, while not providing the source code:

![Mention of GPLv2 during installation](https://cdn.patrickwu.space/posts/dev/linux-review/idesktop/2.png)

I won't comment on their behavior, but I believe this is a possible violation of the GPL License.  One evidence is undeniable in this possibility: They didn't disclose the source code.

Apart from that, the installation is straightforward and simple. After installing, you can see the distribution is using KDE environment:

![Desktop Environment](https://cdn.patrickwu.space/posts/dev/linux-review/idesktop/3.png)

This Linux distribution uses not software that can be normally found in common Linux distributions but its China alternatives; For example, WPS is used instead of LibreOffice that used in most distributions. Very interestingly, the distribution is fully translated to Simplified Chinese, unlike other nation-backed OS like Redflag OS, where a lot of English remains everywhere on the UI. The primary issue I have for the distribution that there is no package repository included in the system, while the package server is also down. 

![No Available repository](https://cdn.patrickwu.space/posts/dev/linux-review/idesktop/4.png)

It's a unique Linux distribution overall, and you can try it on your own, but always keep in mind: As a closed-source Linux distribution, although no clear evidence of spying, you should always use with caution.
