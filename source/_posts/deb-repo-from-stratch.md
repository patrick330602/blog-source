---
title: 'Build Your Own Debian Repository: The quick way'
tags:
  - Linux
  - Linux Packaging
  - Ubuntu
  - Debian
date: 2020-06-07 12:15:08
---


## TL;DR

This is a quick little article to get you started with the Debian repository. I have been setting up Debian repositories for `wslu` and some other packages. You can check out my Debian and Kali Linux repository for wslu [here](https://access.patrickwu.space/wslu/debian/) and [here](https://access.patrickwu.space/wslu/kali/).

## Setup

We will set up the repository using `reprepro`. You can install `reprepro` using your distro's package manager, for example,  `sudo apt install reprepro` for debian-based operating systems. 

Now, you can create Create a directory for your repository. Here, I will call it `repo`. Create a folder `conf` under the `repo` folder, and under `conf` folder, create a file called `distributions` with the following content:

```
Origin: repo.example.com
Label: repo.example.com
Codename: trusty
Architectures: i386 amd64 source
Components: main
Description: example repo
SignWith: Yes
```

Here is a quick reference of this file:
- **Origin**: This field is used to identify where this repository is coming from. Usually, it is a URL.
- **Label**: Similar to Origin
- **Codename**: This is usually the codename for the distribution like *focal*, *buster* and *kali-rolling*.
- **Architectures**: The architectures.
- **Components**: The names of the components that packages can be imported into. Usually, it is `main`.
- **Description**: This field is optional, but useful for end-users. 
- **SignWith**: This field can be either `yes`, `default`, or have the GPG key ID of a GPG key that should be used to sign the repository metadata. If “yes” or “default” are specified, reprepro will use the default GPG key available when signing repository metadata. 

More detailed documentation can be found [here](https://manpages.debian.org/jessie/reprepro/reprepro.1.en.html#conf/distributions), or check `man reprepro` under **conf/distributions** section.

Now, you have complete the Debian repository. Now Let's try to import a Debian package.

## Sign and Import

You need to get your private key and Debian package ready. I have an article talking about a simple way you can build a Debian package: [Packaging .deb using a simple script: Explained](https://patrickwu.space/2018/05/25/make-deb-packing-simple/)

Now let's prepare our signing environment by running `export GPG_TTY=$(tty)`, or include this line in your shell configuration file. This allows GPG-signing running in the terminal without throw out errors.

Now, import your GPG key using `‌gpg --import <key>`. You can now sign packages with `dpkg-sig -k <key> --sign builder /path/to/package.deb` or `dpkg-sig --sign builder /path/to/package.deb` if your signing the package with default key. 

Now you can import packages using `reprepro -S <category> -b repo/ includedeb <codename> /path/to/package.deb`. `-S` is always required.