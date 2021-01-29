---
title: "Microsft build 2019: My Tiny Little Summary"
date: 2019-05-10 07:32:43
tags:
- C#
- WSL
- WSL2
- Microsoft Build
---

## TL; DR

This is a summary for some of the topics that I am interested in Build 2019. Build 2019 is a fun experience for me, where I met different people, speak here, and most importantly, learn new ideas and new changes in the Windows ecosystem.

And here is a photograph of me with Scott Hanselman ;)

![A photograph of me with Scott Hanselman](https://cdn.patrickwu.space/posts/dev/with-scott-hanselman.jpg)

## WSL 2

WSL 2 is one of the most import parts of Build 2019 for me. I got some teases of WSL 2 before Build 2019 and already really got excited about it, and Hayden and I got an early demo at the booth too, which literally blew my mind. So, in the following part, I will just throw facts from the build from the sessions and the question asked:

Highlights:
1. WSL 2 is fast. Really fast.
2. Nearly all system call is working, which means:
    1. `systemd`/FUSE/Docker now works properly.
    2. Sound support is not on the schedule.
    3. x32 support.
    4. `mount` command works too, which means you can mount Linux partition in this method.
3. Uses a modified version of Linux Kernel 2.19 and changes to WSL will also available upstream.
4. You can switch between WSL 1 and WSL 2 using `wsl --set-version <distro> <wsl version>`.
5. WSL 2 uses 9P protocol to access Windows file in WSL and access WSL file in Windows.
6. the filesystem for each distro will be `ext4` instead of `WslFS`.
7. Yes, it's using hypervisor; but no, it's not Hyper-V. it's a lightweight virtual machine using Windows Virtual Machine Platform that can be run along with Hyper-V, its boot time will be around 1-2 seconds.

Drawbacks:
1. Currently WSL 2 will have a different IP address currently; the fix is still on the way.
2. The final WSL 2 might not ship in the release; even it is shipped, WSL 1 will remain as default and requires people to switch to WSL 2 manually.

Schedules:
1. GPU support is in working(High priority).
2. GUI support is in working(High priority).
3. Startup Support is in medium priority, not in working.
4. Sound support is not on schedule (for now).

## Windows Terminal

Another exciting thing on Build this year is Windows Terminal. they even made a video for it! (Also our product Pengwin also made a cameo)

Highlights:
1. It is already open sourced. The decision to open source only made several days ago, and it is being pushed during the Build 2019.
2. Tab support!
3. Full Unicode support! (CJK, Emoji, Powerline, Nerd)
4. Ligature Support!
5. A new font for Windows Terminal!
6. Fluent background!
7. The scrollbar can now be hidden!
8. `Ctrl + Scroll` to change the font size.
9. `Ctrl + Shift + Scroll` to change transparency.
10. The new rendering engine is using DirectX/GPU powered.
11. Each WSL distribution will have its own theme in the future.
12. In the future, there will be extension support using `wt`;
13. Some changes in Windows Terminal will come to `conhost.exe` too.
14. Deliver via store; first version in the summer 2019 and first release in the winter 2019.

## WinUI

Highlight:
- Windows UI Library is live at <https://github.com/Microsoft/Microsoft-ui-xaml>.
- Fluent Design can be used in Win32, WinForms, and WPF with XAML Islands.
- Fluent Design is expanding to Web, Android, and iOS with UI Fabric.
- Xamarin and React Native will gain support for it.
- Fabric Core is based on JS available on npm and NuGet.
- Fabric React is available for React Native.


Suggestions:
- Using NavigationView control instead of custom controls in UWP would be better. (Using it in UWP DevKit would be great)

## Progress Web App

It seems that PWA is a new trend in building apps, so I watched the session live as I am attending ML.NET Session. Here are some highlights:

- Web Template Studio Extensions on VSCode help create PWA apps
- PWA requires HTTPS, Web App Manifest, Service Worker at Minimum
- Can be also built online using the help from [PWA Builder](https://pwabuilder.com)
- PWA can be now delivered in Windows Store instead of traditional WinJS and Web Host Apps.
- Microsoft Graph Toolkit can be used in the Progress Web Apps.

## C# 8.0

As a C# Developer, although I am not really active right now, I am still interested in what's new in C#.

- Nullable Reference Type is available(with `?`) which means Null will possibly be returned in this type:
   ```csharp
    Account? account = await LookupAccountAsync(license);
   ```
   Now, if type will possibly be nullable, Visual Studio will warn you.

- Hat operator `^`:
  ```csharp
  var a = b[^2..] // the last 2 character till the end
  ```

- Updated case pattern matching from this:
  ```csharp
  public static string Categorizer(object test){
    switch(test)
    {
        case Car c when c.Passengers = 0:
            return "car0";
        case Car c when c.Passengers = 1:
            return "car1";
        case Car c when c.Passengers = 2:
            return "car2";
        case Car _:
            return "car";
    
        case Bus b when ((Double)b.Riders / b.Capacity) < 0.5:
            return "bus1";
        case Bus b when ((Double)b.Riders / b.Capacity) < 0.9:
            return "bus2";
        case Bus _:
            return "bus";
    }
    throw new Exception();
  }
  ```
  to this:
  ```csharp
  public static string Categorizer(object test){
    switch(test)
    {
        case Car { Passengers: 0}:
            return "car0";
        case Car { Passengers: 1}:
            return "car1";
        case Car { Passengers: 2}:
            return "car2";
        case Car _:
            return "car";

        case Bus (var r, var c) when (r / c) < 0.5:
            return "bus1";
        case Bus (var r, var c) when (r / c) < 0.9:
            return "bus2";
        case Bus _:
            return "bus";
    }
    throw new Exception();
  }
  ```
  Then it can even be simplified more:
  ```csharp
   public static string Categorizer(object test)
    => test switch
       {
        Car { Passengers: 0} => "car0",
        Car { Passengers: 1} => "car1",
        Car { Passengers: 2} => "car2",
        Car _ => "car",

        Bus (var r, var c) when (r / c) < 0.5 => "bus1",
        Bus (var r, var c) when (r / c) < 0.9 => "bus2",
        Bus _ => "bus",

        _ => throw new Exception();
        };
  ```
  Which can be simplified even more with nested switch:
  ```csharp
   public static string Categorizer(object test)
    => test switch
       {
        Car c => c.Passengers switch
        {
            0 => "car0",
            1 => "car1",
            2 => "car2",
            _ => "car"
        },

        Bus (var r, var c) when (r / c) < 0.5 => "bus1",
        Bus (var r, var c) when (r / c) < 0.9 => "bus2",
        Bus _ => "bus",

        _ => throw new Exception();
        };
  ```

- You can now await with `foreach`:
   ```csharp
   await foreach (var v in ARndomClass.aRandomAsync()){
       // Do Something
   }
   ```

- You can inherit Interface now:
   ```csharp
   public Interface IStuff
   {
       void anAction(var someInput, var someOtherInput);
       void anotherAction(var moreInput) => anAction(moreInput, "action");
   }
   ```

## Other Notes

- DTrace on Windows will be more useful than I thought, can use it more frequently as it can help debug on release
- Azure Pipelines: it is actually pretty good after watching the session. I will start migrating my UWP apps back to Azure Pipeline.
- ML.NET 1.0 is out and it would be great to try out with C# application and .NET Core applications.

