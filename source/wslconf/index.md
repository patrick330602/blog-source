---
title: "Scripting with WSL interoperability: Tips & Tricks"
tags:
- WSLConf
- WSL
- WSL2
lang: en
lang_switch: 'false'
---

This is the resource page for things I have talked about or not talked about on the session ***Scripting with WSL interoperability: Tips & Tricks***. 

You can get the ppt here: <https://cdn.patrickwu.space/wslconf/wslconf-wsl-interop.pptx>.

You can find the source code here: <https://github.com/patrick330602/wslconf>.

## WSL Feature Availability

| | 1607 | 1703 | 1709 | 1803, 1809 | 1903, 1909 | 2004 |
|---:|:---:|:---:|:---:|:---:|:---:|:---:|
| lxrun.exe | ✓ | ✓ | ✓ | | | |
| bash.exe/wslconfig.exe | ✓ | ✓ | ✓\*1 | ✓\*1 | ✓\*1 | ✓\*1 |
| \<distro\>.exe | | |✓|✓|✓|✓|
| wsl.exe | | | ✓ | ✓ | ✓\*2 | ✓\*2 |
| WSL Interoperability | |✓|✓|✓|✓|✓|
| DrvFS Mounting| | |✓|✓|✓|✓|
|wsl.conf | | | | ✓ | ✓\*2|✓\*2|
| $WSL_DISTRO_NAME| | | | |✓|✓|
| $WSLENV| | | |✓|✓|✓|
| \\\\wsl\$\\ | | | | |✓|✓|
| WSL2| | | | | |✓|
| /mnt/wsl/| | | | | |✓|

> *1: Kept for backward compatibility
> *2: feature of the function extended in these builds


## Check WSL Interoperability
`grep ^enabled /proc/sys/fs/binfmt_misc/WSLInterop` will print `enabled` if WSL interoperability is enabled, otherwise it will be a empty output.

Also, you can use the file `/proc/sys/fs/binfmt_misc/WSLInterop` to check whether the script/program is running in WSL or Linux.

## Using `wsl.conf` to get Windows mounting location and system drive location

You can find reference for the `wsl.conf` [here](https://docs.microsoft.com/en-us/windows/wsl/wsl-config#set-wsl-launch-settings).

To get the mount location, you can use `root` in option in `[automount]` section to get the location. By default, it will use `/mnt/`, and the logic can be something like this:
```
If /etc/wsl.conf exists and if root under [automount] is defined
	use the root as the mount location
Else
	use the /mnt/ as the mount location
End
```

After finding the mount location, you can scan through the already mounted drive to find where the Windows install location is, as %SystemRoot% is sometimes not defined properly.

```
For already mounted drive in mount location
	If found Windows install location
		use the location
	End
End

If not found:
	fallback to C Drive, the default location
End
```

## Executing Windows commands on WSL

It is suggested to running them using the full path in case the Windows Path does not append properly on the Windows side.

### individual Tools (e.g., reg.exe)

**Pros**:
- straight forward and fast

**Cons**:
- Possibly problematic with UNC path(which includes WSL file access, which locates at `\\wsl$\DistroName`)but no error will possibly going to be shown.

### cmd.exe

Commands can be executed using `cmd.exe /C “Command”`.

**Pros**:
- `cmd.exe` script support (such as `echo`, `dir`)
- Run under Windows environment, easier to pass environment variables

**Cons**:
- UNC path is not supported and an error will be shown as the following one when executing:
S
  ```
  ‘\\just\a\UNC\path’
  CMD.EXE was started with the above path as the current directory.
  UNC paths are not supported.  Defaulting to Windows directory.
  ```
  
	To solve this, you can just move to a Windows folder in WSL before executing the command, for example, your C Drive (`/mnt/c` by default)
  
### powershell.exe

- Allows you to execute `powershell` commands and scripts
- Windows PowerShell is suggested not PowerShell Core for Maximum compatibility

Execute commands/scripts using `powershell.exe -NoProfile -NonInteractive -Command "<Command>"` for faster speed.

Include `-ExecutionPolicy ByPass` if you want to execute the command. 

For the raster font problem, you can refer to my article [here](https://patrickwu.space/2019/08/03/wsl-powershell-raster-font-problem/) to fix the problem.

### Performance

These three options have some speed difference. 
Running individual tools directly are usually the fastest; and then the `cmd.exe`; and `powershell.exe` is slowest. Here is a benchmark video:

{% youtuber video LaPB8GRqKUk %}
{% endyoutuber %}

## Executing WSL commands on Windows Side

### bash.exe

This is the legacy of the origin of WSL (**Bash** on Ubuntu on Windows). `bash.exe` will start a **bash** login shell of your default WSL Linux distribution. 

`bash.exe` uses the Linux bash command-line arguments, and just like linux bash, you need to execute a command like the following:

```
bash.exe -c "<COMMAND>"
```

### wsl.exe

Merged feature from `wslconfig.exe`, `wsl.exe` is the one that allows you to run and manage WSL distros. It also has allows you to run WSL with different distro with different users: `wsl [-d <distro>] [-u <user>] [-e] <Command>`:

`-e` is the interesting part here: 
- `wsl.exe` executes commands **with the default login shell** of your WSL distribution.
- `wsl.exe -e` executes commands on your WSL distribution **without any shell**.

{% youtuber video uP5YR3GIFzo %}
{% endyoutuber %}

### <distro>.exe

`<distro>.exe` allows you to interact with a specific distro. However, it has some interesting behaviors that you need to pay attention:
- `<distro>.exe` with no arguments launches the user's default shell in the user's home directory;
- `<distro>.exe run` launches the user's default shell in the current working directory;
- `<distro>.exe run [Command]` run the provided command line in the current working directory with user's default shell.

But there are some issues:
- Imported distributions don’t have `<distro>.exe`
- <distro>.exe can be different from distribution registered name.

## Bonus Section

I am not able to cover everything in WSLConf, so here are some additional tips and tricks you might be interested in:

### Start WSL GUI apps without command prompt

I have posted a post about this topic before. You can view it [here](https://patrickwu.space/2018/04/20/wsl-shortcut-without-cmd-window/).

### WSL-related Registry: a small dive

The section is moved to [this post](https://patrickwu.space/2020/07/19/wsl-related-registry/) for better visibility.

### Execute Windows executables using Administrator in WSL

You can start using the `Start-Process` command in powershell, which will become something like the following:

```
powershell.exe -NonInteractive -NoProfile -Command "Start-Process -FilePath powershell.exe -WorkingDirectory \"$(wslpath -w $(pwd))\" -ArgumentList "-NonInteractive -NoProfile -Command <Command>" -Verb RunAs"
```

You might also want to tweak the line for your needs.

If you find this is a bit too long or it is a bit complicated, you can use the following script from scoop creator Luke Sampson to do the thing: <https://github.com/lukesampson/psutils/blob/master/sudo.ps1>. To use this one, `-ExecutionPolicy ByPass` is suggested to use.