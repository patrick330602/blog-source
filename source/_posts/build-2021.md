---
title: 'Microsoft Build 2021: My Tiny Little Summary'
date: 2021-06-03 22:20:13
tags:
  - Microsoft Build
  - 'C#'
  - WSL
  - WSLg
---

## TL; DR

Oh, look, a wild Microsoft Build appeared! The Microsoft Build this year was, you know, all virtual again. As a person who is more interested in .NET and WSL development, I find it disappointing that not much of Windows development is mentioned in the entire conference. It made me miss [Build 2019](https://patrickwu.space/2019/05/10/build-2019/). While I am still not recovered from my master's study, my laptop died right during the Build conference; I can only provide a straightforward summary of this year's Build. Well, I think let's dive in.

{% twitter https://twitter.com/callmepkwu/status/1397623325560504329?s=21 %}

## WSL

To my disappointment, there is not much new in this build. This build generally recapped everything we have so far, such as WSLg and ``wsl --install`. The only new thing I noticed is Visual Studio's ability to debug using the browser in WSL using WSLg.

{% twitter https://twitter.com/callmepkwu/status/1397585583459868673?s=21 %}

## .NET

The sessions about .NET is packed with information, although there is nearly 1-digit number of sessions about it. .NET Upgrade Assistant (Help upgrade .NET project to latest version) would be possibly a great tool for me to migrate my old projects to newer version, and .NET 6 with its Single stack (only one SDK, BCL, Toolchain for cross-platform) structure made it looks really promising.  Here is a quick sum up from the [session](https://docs.microsoft.com/en-us/events/build-may-2021/azure/breakouts/od485/?ocid=AID3032299):

- C# 10
  - Syntactic Simplications
    - In-line comparison (Equals in `{}` in Writeline can be simplified to just `==`)
  - Record structs
     - record class/struct
  - Improvements to lambdas and auto-properties
    - `init` for simple initialization
    - `required` option for `init` items
    - `=>` for `init`, `get` and `set`
    - `!!` for check incoming variable
    - `global using` for all dependency accorss 
    - `namespace <name>;`can be used instead of the old`namespace <name> {}`
- Web API (minimal)

- .NET MAUI
  - `dotnet new maui`
  - Speacial Resources type (Maui*)
  - Would be really great for some projects
- Blazor
 - WebAssembly ahead-of-time compilation
 - Error boundaries
 - Razor component type inference & generic type constraints
 - Dynamic Components
 - Blazor state persistence during prendering
 - Fluent UI
- ASP.NET Core
  - Runtime
    - HTTP/3
    - HTTP logging middleware
    - Shadow copying for IIS deployment
    - OpenTelementary support
  - API
    - Minimal APIs
      - `start.cs` is now optional
      - lambda support
      - provide experience similar to NodeJS
    - Async streaming
    - `IAsyncDisposable` support
    - gRPC client retries & load balancing
  - Web UI
    - CSS isolation for Pages & Views
    - Improved SPA support


# Other
- [winget 1.0](https://devblogs.microsoft.com/commandline/windows-package-manager-1-0/?utm_source=isaacl&utm_medium=twitter&utm_campaign=link&WT.mc_id=link-twitter-isaacl)
- Visual Studio Hot Reload; this will save a lot of times for me
- Project Reunion 0.8
  - Provides UWP-like interface
  - MAUI+WinUI
  - 1.0 will come with the notification feature

