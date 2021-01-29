---
title: Run periodic tasks using crontab
date: 2017-11-16 16:02:49
tags:
- Server
- Ubuntu
---
To schedule tasks on Linux, We need a tool called `crontab`. It can be found in all the Unix-like operating systems such as Ubuntu, Mac OS X, etc. It is used to schedule commands to be executed periodically.
Crontab is short for **cron table**. A cron table contains a list of commands that will be executed periodically. These processes are called **cronjobs**. 

There are two kinds of cronjobs: **evelated** and **non-evelated**, which the only difference is that the first one will be executed by `root`.
<!--more-->
## Commands

To see what cronjobs are currently running on your system, you can open a terminal and run: 
```bash
crontab -l
```
To edit the list of elevated cronjobs, you need to run:
```bash
crontab -e
```
To remove your crontab file:
```bash
crontab -r
```
To display the last time you edited your crontab file:
```bash
crontab -v
```

One important thing is, **DO NOT** open crontab file on your own, which will possibly break the `crontab` deamon. Also, the `crontab` will begin running as soon as it is properly edited and saved.
## Syntax

`crontab` has syntax of following:
```
min hr dd mo wk com
```
Where:

- `min`: minute (0-59)
- `hr`: hour (0-23, 0 = midnight)
- `dd`: day (1-31)
- `mo`: month (1-12)
- `wk`: weekday (0-6, 0 = Sunday)
- `com`: command to be executed

An asterisk (*) can be used so that every instance (every hour, every weekday, every month, etc.) of a time period is used.

Comma-separated values can be used to run more than one instance of a particular command within a time period. Hyphen-separated values can be used to run a command continuously. 

You may want to run a script some number of times per time unit, use `*/n` where it runs every time that is divisible by `n`.

Also, it is recommended that you use the full path to the desired commands as shown in the above examples. 

## Exmaples

```
21 06 12 3 1 python /usr/bin/myfolder/myscript.py
```

The above example will run `python /usr/bin/myfolder/myscript.py` at 6:21 AM on March 12 plus every Monday in March.

```
21 06 * * * python /usr/bin/myfolder/myscript.py
```

The above example will run `python /usr/bin/myfolder/myscript.py` at 6:21 AM on every day of every month.


```
01,31 04,05 1-15 1,6 * python /usr/bin/myfolder/myscript.py
```

The above example will run `python /usr/bin/myfolder/myscript.py` at 01 and 31, past the hours of 4:00am and 5:00am on the 1st through the 15th of every January and June.

```
*/10 * * * * python /usr/bin/myfolder/myscript.py
```

The above example will run `python /usr/bin/myfolder/myscript.py` every 10 minutes. 

## Special words

For the first (minute) field, you can also put in a keyword instead of a number:

- `@reboot`: Run once, at startup
- `@yearly`: Run once a year "0 0 1 1 *"
- `@annually`: (same as @yearly)
- `@monthly`: Run once a month "0 0 1 * *"
- `@weekly`: Run once a week "0 0 * * 0"
- `@daily`: Run once a day "0 0 * * *"
- `@midnight`: (same as @daily)
- `@hourly`: Run once an hour "0 * * * *"


## Environment

We should know that `cron` invokes the command from the user’s HOME directory with the shell, (/usr/bin/sh). cron supplies a default environment for every shell, defining:

```bash
HOME=user’s-home-directory
LOGNAME=user’s-login-id
PATH=/usr/bin:/usr/sbin
SHELL=/usr/bin/sh
```

Users who desire to have their `.profile` executed must explicitly do so in the crontab entry or in a script called by the entry.

## Storing Output

add `>> /var/log/<the name you want>.log 2>&1` to the end of your command.

## Disable Email

By default, cronjobs sends a email to the user account executing the cronjob. If this is not needed, then put `>/dev/null 2>&1` at the end of the cron job line. You can also configure crontab to forward all output to a real email address by starting your crontab with `MAILTO="myname@somedomain.com"`.

## Storing Logs

To collect the cron execution execution log in a file:

```
30 18 * * * rm /home/someuser/tmp/* > /home/someuser/cronlogs/clean_tmp_dir.log
```