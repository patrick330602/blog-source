---
title: Windows Phone URI 方案
tags:
  - 'C#'
  - WP8
  - WP8.1
date: 2014-07-06 11:08:23
lang: zh
---

| URI scheme                         | Description                              |
| ---------------------------------- | ---------------------------------------- |
| `ms-battery`                       | Launches the Usage tab of the Battery Saver app. |
| `ms-wallet`                        | Launches Wallet app.                     |
| `ms-settings-camera`               | Launches the Photos + Camera Settings app. |
| `ms-settings-networkprofileupdate` | Launches the Network Profile Update app.*這貨不知道幹啥用的 |
| `ms-settings-nfctransactions`      | Launches the NFC Settings app.<sup><span style="font-size:12px;">1</span></sup> |
| `ms-settings-notifications`        | Launches the Notifications + Actions Settings app. |
| `ms-settings-proximity`            | Launches the NFC Settings app.<sup><span style="font-size:12px;">1</span></sup> |
| `ms-settings-uicctoolkit`          | Launches a SIM Applications related app. UICC appears to stand for Universal Integrated Circuit Card.<sup><span style="font-size:12px;">2</span></sup> |
| `ms-settings-workplace`            | Launches the Workplace Settings app.     |

`Windows.System.Launcher.LaunchUriAsync( Uri(“ms-settings-bluetooth:”));`
<!--more-->

託管的應用 和 Direct3D 應用 都支援此內建應用啟動方法。

## URI 方案列表

* * *

下表列出了用於啟動內建應用的 URI 方案。


| URI 方案                                   | 說明                                       |
| ---------------------------------------- | ---------------------------------------- |
| `http:[URL]`                             | 啟動 Web 瀏覽器並導航到特定的 URL。                   |
| `mailto:[email address]`                 | 啟動電子郵件應用並使用“收件人”一行上特定的電子郵件地址建立新郵件。請注意，電子郵件不會傳送直到使用者點按傳送。 |
| `ms-settings-airplanemode：`              | 啟動飛航模式設定應用。                              |
| `ms-settings-bluetooth：`                 | 啟動藍芽設定應用。                                |
| `ms-settings-cellular：`                  | 啟動手機網路設定應用。                              |
| `ms-settings-emailandaccounts：`          | 啟動電子郵件和帳戶設定應用。                           |
| `ms-settings-location：`                  | 啟動位置設定應用。                                |
| `ms-settings-lock：`                      | 啟動鎖屏設定應用。                                |
| `ms-settings-power：`                     | 啟動節電模式設定應用。                              |
| `ms-settings-screenrotation：`            | 啟動螢幕旋轉設定應用。                              |
| `ms-settings-wifi：`                      | 啟動 Wi-Fi 設定應用。                           |
| `zune:navigate?appid=[app ID]`           | 啟動 Windows Phone 商店 並顯示特定應用的詳細資訊頁面。      |
| `zune:reviewapp`                         | 啟動 商店 並顯示呼叫應用的檢視頁面。                      |
| `zune:reviewapp?appid=app[app ID]`       | 啟動 商店 並顯示特定應用的檢視頁面。請注意，您必須在指定應用的 ID 前面加上“app”。例如，檢查 ID 為 fdf05477-814e-41d4-86cd-25d5a50ab2d8 的應用時，URI 為 `zune:reviewapp?appid=appfdf05477-814e-41d4-86cd-25d5a50ab2d8` |
| `zune:search?keyword=[search keyword]&amp;publisher=[publisher name]&amp;contenttype=app` | 啟動 商店 並搜尋特定的內容。所有參數都是可選的。指定“contenttype=app”將限制對應用的搜尋。省略此參數將搜尋所有內容。 |
| `zune:search?keyword=[search keyword]&amp;contenttype=app` | 啟動 商店 並按關鍵字搜尋應用。                         |
| `zune:search?publisher=[publisher name]` | 啟動 商店 並按釋出者名稱搜尋項目。                       |

有兩種保留 URI 方案名稱：為 Windows Phone 內建應用保留的 URI 方案名稱和為作業系統保留的 URI 方案名稱。如果 URI 包含為內建應用保留的 URI 方案名稱，那麼啟動該 URI 時，只有內建應用會啟動。任何利用該 URI 方案名稱註冊應用的企圖都會被忽略。同樣，任何利用為作業系統保留的 URI 方案名稱註冊應用的企圖也會被忽略。

### 為內建應用保留的 URI 方案名稱

Windows Phone 為內建應用保留以下 URI 方案名稱。

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
| maps                         | *地圖。需要加上位置 |
| onenote                      |            |
| ms-excel                     |            |
| tel                          |            |
| ms-powerpoint                |            |
| wallet                       |            |
| ms-settings-airplanemode     |            |
| xbls                         | 遊戲中心       |
| ms-settings-bluetooth        |            |
| zune                         | 音樂         |
| ms-settings-cellular         |            |