---
title: Windows Phone URI scheme
tags:
  - 'C#'
  - WP8
  - WP8.1
date: 2014-07-06 11:08:23
---

| URI scheme                         | Description                              |
| ---------------------------------- | ---------------------------------------- |
| `ms-battery`                       | Launches the Usage tab of the Battery Saver app. |
| `ms-wallet`                        | Launches Wallet app.                     |
| `ms-settings-camera`               | Launches the Photos + Camera Settings app. |
| `ms-settings-networkprofileupdate` | Launches the Network Profile Update app.*这货不知道干啥用的 |
| `ms-settings-nfctransactions`      | Launches the NFC Settings app.<sup><span style="font-size:12px;">1</span></sup> |
| `ms-settings-notifications`        | Launches the Notifications + Actions Settings app. |
| `ms-settings-proximity`            | Launches the NFC Settings app.<sup><span style="font-size:12px;">1</span></sup> |
| `ms-settings-uicctoolkit`          | Launches a SIM Applications related app. UICC appears to stand for Universal Integrated Circuit Card.<sup><span style="font-size:12px;">2</span></sup> |
| `ms-settings-workplace`            | Launches the Workplace Settings app.     |

`Windows.System.Launcher.LaunchUriAsync( Uri(“ms-settings-bluetooth:”));`
<!--more-->

托管的应用 和 Direct3D 应用 都支持此内置应用启动方法。

## URI 方案列表

* * *

下表列出了用于启动内置应用的 URI 方案。


| URI 方案                                   | 说明                                       |
| ---------------------------------------- | ---------------------------------------- |
| `http:[URL]`                             | 启动 Web 浏览器并导航到特定的 URL。                   |
| `mailto:[email address]`                 | 启动电子邮件应用并使用“收件人”一行上特定的电子邮件地址创建新邮件。请注意，电子邮件不会发送直到用户点按发送。 |
| `ms-settings-airplanemode：`              | 启动飞行模式设置应用。                              |
| `ms-settings-bluetooth：`                 | 启动蓝牙设置应用。                                |
| `ms-settings-cellular：`                  | 启动手机网络设置应用。                              |
| `ms-settings-emailandaccounts：`          | 启动电子邮件和帐户设置应用。                           |
| `ms-settings-location：`                  | 启动位置设置应用。                                |
| `ms-settings-lock：`                      | 启动锁屏设置应用。                                |
| `ms-settings-power：`                     | 启动节电模式设置应用。                              |
| `ms-settings-screenrotation：`            | 启动屏幕旋转设置应用。                              |
| `ms-settings-wifi：`                      | 启动 Wi-Fi 设置应用。                           |
| `zune:navigate?appid=[app ID]`           | 启动 Windows Phone 商店 并显示特定应用的详细信息页面。      |
| `zune:reviewapp`                         | 启动 商店 并显示调用应用的查看页面。                      |
| `zune:reviewapp?appid=app[app ID]`       | 启动 商店 并显示特定应用的查看页面。请注意，您必须在指定应用的 ID 前面加上“app”。例如，检查 ID 为 fdf05477-814e-41d4-86cd-25d5a50ab2d8 的应用时，URI 为 `zune:reviewapp?appid=appfdf05477-814e-41d4-86cd-25d5a50ab2d8` |
| `zune:search?keyword=[search keyword]&amp;publisher=[publisher name]&amp;contenttype=app` | 启动 商店 并搜索特定的内容。所有参数都是可选的。指定“contenttype=app”将限制对应用的搜索。省略此参数将搜索所有内容。 |
| `zune:search?keyword=[search keyword]&amp;contenttype=app` | 启动 商店 并按关键字搜索应用。                         |
| `zune:search?publisher=[publisher name]` | 启动 商店 并按发布者名称搜索项目。                       |

有两种保留 URI 方案名称：为 Windows Phone 内置应用保留的 URI 方案名称和为操作系统保留的 URI 方案名称。如果 URI 包含为内置应用保留的 URI 方案名称，那么启动该 URI 时，只有内置应用会启动。任何利用该 URI 方案名称注册应用的企图都会被忽略。同样，任何利用为操作系统保留的 URI 方案名称注册应用的企图也会被忽略。

### 为内置应用保留的 URI 方案名称

Windows Phone 为内置应用保留以下 URI 方案名称。

| URI                          | Note       |
| ---------------------------- | ---------- |
| bing                         | *特殊的bing   |
| ms-settings-emailandaccounts |            |
| callto                       |            |
| ms-settings-location         |            |
| dtmf                         |            |
| ms-settings-lock             |            |
| http                         |            |
| ms-settings-wifi             |            |
| https                        |            |
| ms-word                      |            |
| mailto                       |            |
| office                       |            |
| maps                         | *地图。需要加上位置 |
| onenote                      |            |
| ms-excel                     |            |
| tel                          |            |
| ms-powerpoint                |            |
| wallet                       |            |
| ms-settings-airplanemode     |            |
| xbls                         | 游戏中心       |
| ms-settings-bluetooth        |            |
| zune                         | 音乐         |
| ms-settings-cellular         |            |