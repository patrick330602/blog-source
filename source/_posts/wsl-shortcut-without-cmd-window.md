---
title: Start WSL GUI apps without command prompt
date: 2018-04-20 13:32:51
tags:
- WSL
- GUI
---

When using [this method](https://www.patrickwu.ml/2017/05/10/Start-WSL-application-from-Windows-10-Desktop/) to start application from desktop, a command prompt will always started at the back:

![](https://cdn.patrickwu.space/posts/dev/wsl/wsl-window.png)

So there is a solution to the problem to stop command prompt to be shown along the apps.
<!--more-->
Firstly, create a file with name `runHidden.vbs` with following content (CRLF style required), and place it at some proper place:

```vb
' Simple command-line help.
select case WScript.Arguments(0)
case "-?", "/?", "-h", "--help"
  WScript.echo "Usage: runHidden executable [...]" & vbNewLine & vbNewLine & "Runs the specified command hidden (without a visible window)."
  WScript.Quit(0)
end select

' Separate the arguments into the executable name
' and a single string containing all arguments.
exe = WScript.Arguments(0)
sep = ""
for i = 1 to WScript.Arguments.Count -1
  ' Enclose arguments in "..." to preserve their original partitioning.
  args = args & sep & """" & WScript.Arguments(i) & """"
  sep = " "
next

' Execute the command with its window *hidden* (0)
WScript.CreateObject("Shell.Application").ShellExecute exe, args, "", "open", 0
```

Then you can launch your GUI application using the command below:

``` 
> wscript Address\to\runHidden.vbs bash -c <GUI application>
```

This feature will also be included in the future release of [wslu](https://github.com/patrick330602/wslu).

> Source: <https://stackoverflow.com/questions/41225711/wsl-run-linux-from-windows-without-spawning-a-cmd-window>
> Credit to [mklement0](https://stackoverflow.com/users/45375/mklement0)