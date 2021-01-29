---
title: 'Microsoft Build 2020: My Tiny Little Summary'
tags:
  - Microsoft Build
  - 'C#'
  - WSL
  - WSL2
date: 2020-05-31 11:30:37
---


## TL; DR

Another year, another Microsoft Build; but this year, it's all virtual due to coronavirus. Last year's build is such a fun experience for me; I even have written a summary for that [here](https://patrickwu.space/2019/05/10/build-2019/). And this year's build is not only unique but also free, and it is the 48-hour never-ending fun.

Also, here is my setup for the build 2020: 
![My Setup for Build 2020](https://cdn.patrickwu.space/posts/dev/build-2020-setup.jpeg)

## WSL

As WSL2 will be available for everyone on Windows 10 version 2004, 3 new features for WSL is also announced for WSL, and got highlighted by Satya Nadella and Scott Hanselman:

- Added support for graphics processing unit (GPU) compute workflows;
- Support for Linux graphical user interface (GUI) apps with Audio Support;
- Support a simplified install experience by running the command `wsl.exe – install`. 


For **GPU Support**, Microsoft uses a special kernel driver `dxgkrnl` to expose `/dev/dxg` in user mode Linux that allows mimicking the service layer provided on Windows. This method made the following things possible to run on WSL:
- DxCore (`libdxcore`) & D3D12 (`libd3d12`);
- DirectML (`libdirectml`) based on D3D12;
- OPENGL/OpenCL/Vulkan (`mesa`) based on D3D12;
- NVIDIA CUDA (`libcuda`) based on DxCore.

There are also some discussions happening on [Linux Kernel Mailing List](https://lkml.org/lkml/2020/5/19/742) and [Direct Rendering Modules Mailing List](https://lists.freedesktop.org/archives/dri-devel/2020-May/266629.html). The feature is coming very soon in June for Insiders.

A more detailed article can be found here: [DirectX ❤ Linux](https://devblogs.microsoft.com/directx/directx-heart-linux/)

For **GUI support**, according to the session, the GUI layer for WSL will be a Wayland layer implemented as a daemon called `wlwsld` that will (possibly) land as a startup daemon. It uses a custom-built RDP client (`rdclientwsl.exe`) [Xwayland](https://wayland.freedesktop.org/xserver.html), FreeRDP v2.1.0 and a Wayland compositor [Weston](https://github.com/wayland-project/weston) (`libweston`) currently from the demo:

![Sneak peek of the daemon](https://cdn.patrickwu.space/posts/dev/build-2020-wsl-wayland-gui.png)

It is right now proposed to be released in December with the **audio support**.

However, `systemd` support is not likely to come any soon since it is still being working on.

## Windows Terminal

Windows Terminal is finally getting 1.0! It finally gets [proper documentation](https://aka.ms/terminal-docs), and now stable and preview release are separated: [Stable](https://aka.ms/terminal) and [Preview](https://aka.ms/terminal-preview). Also during the session, it showed new features like coloring tab and renaming tab, which will be coming in a few weeks; they also demoed the coming Terminal Settings, which is still super beta:

{% twitter https://twitter.com/callmepkwu/status/1262924009047560193?s=21 %}

## Project Reunion

[Project Reunion](https://github.com/microsoft/ProjectReunion) is an exciting new concept, since I am previously a UWP/Windows Phone developer, and merging the win32 and UWP API is finally started to happen. Project Reunion includes WinUI 3 for unified controls, .NET MAUI for unifying all system UI (Although currently there seems to be a legal issue for the name that caused confliction with KDE MAUI and the Linux community).

As a webmaster during my university time, I am also super interested on what will the new WebView (WebView2) brings for developers and the latest status of Microsoft Edge. For WebView2 and he new Chromium-based Microsoft Edge, we not only got a sneak peek of Microsoft Edge on Linux on Azure session; but WebView2 will also have a plan to be brought to Linux on the long-term roadmap.

## C# 9.0

In Microsoft Build 2019, they announced C# 8.0 and showed what's next. In Build 2020, they shared about what will be coming for C# 9.0. Here are just some takeaways.

You can initial a new object using the With-expressions:

```csharp
var b = a with {con = "some other content"};
```


In Microsoft Build 2019, they demonstrated a lot of awesome improvements in pattern matching. There are some further improvements in pattern matching, including: 
    - Type patterns
    - Relational patterns (`<=`)
    - Logical patterns (`not`)

Now in C# 9.0, there is a new target-typed "new" so you can init an object using the following:

```csharp
a_random_type x = new ("a", "b");
```

Also, it now allows parameter null checking inline with `!`:

```sharp
Public a_random_type(string name!, string con!) { ... }
```

There is also a feature of covariant returns such that you can ovreride a feature to return another object easily:

```sharp
public virtual a_random_type GetStuff() { ... }
public override another_random_type GetStuff() { ... }
```

There is also a more detailed article about what's new, which a lot of them are not mentioned in the session: [Welcome to C# 9.0](https://devblogs.microsoft.com/dotnet/welcome-to-c-9-0/)

## Other 

- [Windows Package Manager](https://github.com/microsoft/winget-cli); This sure is a surprise to me. Windows is finally getting an official package manager, although it is pretty rough for now. It is also confirmed it will be deeply integrated with Microsoft Store, and you can build your repository besides the [main one](https://github.com/microsoft/winget-pkgs), which is sure exciting
- GitHub Actions integration with Azure DevOps coming, but it is not ready right now

