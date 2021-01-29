---
title: Setup Protonmail on UWP Mail
tags:
  - Windows 10
date: 2020-06-14 15:06:20
---

## TL;DR

This is a quick guide on how you can setup your Protonmail Bridge with UWP version of Mail app. There is some issues such as email content cannot be downloaded, but I heard that ProtonMail is investigating the issue.

## Prepare

Firstly, you need to download [ProtonMail Bridge](https://protonmail.com/bridge/install), of course. Install and setup ProtonMail Bridge, and get all your Protonmail Configuration Information:

![](https://cdn.patrickwu.space/posts/dev/protonmail/1.png)

Also, remember to unlock the Mail app for Local loopback using the following command in PowerShell with Admin privilege:

```
checknetisolation loopbackexempt -a -n="(Get-AppxPackage *windowscommunications*).PackageFamilyName"
```

![](https://cdn.patrickwu.space/posts/dev/protonmail/2.png)

## Adding account

Now, open your UWP mail, and go to Settings -> Manage Accounts -> Add account -> Advanced Setup -> Internet Email. Input Information as follows:

| Field | Input |
| --- | --- |
| Email Address | ***Username** from Protonmail Configuration* |
| Username | ***Username** from Protonmail Configuration* |
| Password | ***Password** from Protonmail Configuration* |
| Account name | *whatever you like* |
| Send your messages using this name | *whatever you like* |
| Incoming email server | 127.0.0.1:1143 |
| Account type | IMAP4 |
| Outgoing (SMTP) email server | 127.0.0.1:1025 |

Also keep **Outgoing server requires authentification** and **Use the same username and password for sending emails**, and uncheck **Rrequire SSL for incoming emails** and **Requie SSL for outgoing emails**.

And now, you have setup your ProtonMail account on UWP Mail client!

