---
title: Setup LAMP Server on Ubuntu on Windows 10 the Right Way
date: 2017-08-17 16:04:06
tags:
- WSL
- LAMP
- PHP
---

I have tried to find a proper guide online to set up the LAMP environement on WSL, but most of them are incompelete or outdated, so here I decided to make a tutorial on how lamp can be properly setup on Bash On Windows aka WSL.

# But wait... What is LAMP? 

LAMP is a term for a software bundle, that is :

> **L**inux **A**pache **M**ySQL **P**HP

LAMP is commonly used as the dafult setup of most of the server, and there are more alternatives available to them. More specifically, a LAMP server structure contains a server operating system(Linux/Unix/FreeBSD), a http server(Apache/Nginx), a database management system(MySQL/MariaDB/Drizzle) and a scripting language(PHP/Perl/Python).

![LAMP structure explained](https://cdn.patrickwu.space/posts/dev/LAMP.svg)
<!--more-->

# Let's begin!

1. Keep everything up-to-date:
   ```bash
   sudo apt-get update
   sudo apt-get upgrade
   ```
2. Install LAMP server:
   ```bash
   sudo apt-get install lamp-server^
   ```
   This will install Apache2, MySQL and Perl, but usually we use PHP, so we will install it later.
   During the installation, MySQL will ask to set the default password for `root` account.
3. Install PHP:
   ```bash
   sudo add-apt-repository ppa:ondrej/php
   sudo apt-get update
   sudo apt-get install
   ```
4. Setup MySQL security option:
   ```bash
   sudo mysql_secure_installation
   ```
   something like this will be popup:
   ```
   
   Securing the MySQL server deployment.

   Enter password for user root:

   VALIDATE PASSWORD PLUGIN can be used to test passwords
   and improve security. It checks the strength of password
   ...
   ```
   I suggest you to choose wisely, I mean, really.
5. Change the listening port if you want:
   If you are also using IIS on Windows, you might also want to change the port. to change the port, change the line `listen 80` to `listen <the port you want>` in file `/etc/apache2/ports.conf`.

6. Start server:
   ```bash
   sudo service apache2 start
   sudo service mysql start
   ```