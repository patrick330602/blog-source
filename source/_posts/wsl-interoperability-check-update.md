---
title: Update on WSL Interoperability Check
date: 2022-03-19 12:54:52
tags:
- WSL
- WSL2
---

Well Well Well... I messed up it seems. Checking whether WSL Interoperability is enabled or not is not that simple.

![Searching "check wsl interoperability" will suggest my way of checking WSL Interoperability](https://cdn.patrickwu.space/posts/dev/wsl/wsl-wrong-interop.png)

Because of this, here is a more precise guide on how to check whether WSL Inteoperabilityis enabled or not. Right now, there are three ways to disable WSL interoperability:

1. Disable using `/proc/sys/fs/binfmt_misc/WSLInterop`;
2. Disable using `/etc/wsl.conf`; and
3. Disable using `WSL_DISTRIBUTION_FLAGS_ENABLE_INTEROP`.

For the first one, users can disable interoperability **temporarily** by running `echo 0 > /proc/sys/fs/binfmt_misc/WSLInterop` as root or users with equivalent permission. Notice it is **temporarily**, which means that in the current running session, the inteoperability will be disabled until a distribution shutdown and restart. This means for the first way, the old checking method will work; however, this also means `/proc/sys/fs/binfmt_misc/WSLInterop` will not reflect other ways, so that if you set using `/etc/wsl.conf`, `/proc/sys/fs/binfmt_misc/WSLInterop` will still show `enabled`, which caused the detection to fail. 

For the second way, there is not a good way other than checking the `/etc/wsl.conf` itself. For this way, users disable interoperability by setting `enabled` to `false` under `[interop]` section. So, to do the checking, you just have to check whether `/etc/wsl.conf` exists and the corresbonding setting is set to `false`. 

For the last one, right now I have no way to check it inside the distribution. This is a system level setting that can be only accessible when calling the [`WslConfigureDistribution` function](https://docs.microsoft.com/en-us/windows/win32/api/wslapi/nf-wslapi-wslconfiguredistribution) in `wslapi.h`:

```cpp
HRESULT WslConfigureDistribution(
  PCWSTR                 distributionName,
  ULONG                  defaultUID,
  WSL_DISTRIBUTION_FLAGS wslDistributionFlags
);
```

To disable interoperability, users should not pass one of the flag called `WSL_DISTRIBUTION_FLAGS_ENABLE_INTEROP` in the [`WSL_DISTRIBUTION_FLAGS` enumeration](https://docs.microsoft.com/en-us/windows/win32/api/wslapi/ne-wslapi-wsl_distribution_flags) to `wslDistributionFlags` when registering the distribution. One way to check it outside wsl is to check its registry key `Flags` under `HKEY_CURRENT_USER\SOFTWARE\Microsoft\Windows\CurrentVersion\Lxss\<Desired-Distro-GUID>` whether it has the flag value for `WSL_DISTRIBUTION_FLAGS_ENABLE_INTEROP` or not (You can check out [my article about WSL-related registry](https://patrickwu.space/2020/07/19/wsl-related-registry/) for more details). However, this way is rarely used so in daily cases, you do not have to check this one.

Hopefully this article clears up about how to check whether WSL Interoperability is enabled or not. 