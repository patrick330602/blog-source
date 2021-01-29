---
title: Hexo + GitHub Pages + ??? = 賺了
date: 2017-06-27 09:11:10
tags:
- Hexo
- Web
---

## 前言

Hexo是一個簡單易懂(呸)的靜態Blog生成器，他與GitHub Pages配合，可以説是非常完美的免費Blog平台。但是，對於小白來説，Hexo的使用和配置還是很複雜，所以特此在前面聲明：

1. Hexo非常折騰，需要耐心；
2. 也需要一定的學習能力和鑽研精神；
3. 懂一些網頁基礎知識就行，但其實不懂也重要。
<!--more-->
## 創建GitHub Pages

1. 打開<github.com>，點擊右上角的加號，選擇“new repository”；

![](https://cdn.patrickwu.space/posts/dev/hg/1.png)

2. 在 Repository Name裏輸入“你的用户名.github.io”,如你的用户名是`dalao`，那麼你應該輸入dalao.github.io，然後勾選“Initialize this repository with a README”，點擊“Create Repository”；

![](https://cdn.patrickwu.space/posts/dev/hg/2.png)

3. 此時你的Repository已經創建好並彈出Repo的界面了。選擇“Settings”，取消勾選“Feature”下的“Wikis”，“Issues”和“Projects”。

![](https://cdn.patrickwu.space/posts/dev/hg/3.png)

至此，GitHub Pages就創建完畢了。

## 安裝Hexo

1. 下載[GitHub客户端](https://desktop.github.com)並安裝和登陸你的賬號；

![](https://cdn.patrickwu.space/posts/dev/hg/4.png)

2. 前往[NodeJS官網](https://nodejs.org/),下載Current或是LTS版都可以，然後打開安裝包安裝；

![](https://cdn.patrickwu.space/posts/dev/hg/5.png)

3. 安裝好後，用管理員身份打開CMD或者Powershell，輸入`npm install -g hexo-cli`,耐心等待完成；
4. 完成後，在你喜歡的地方新建一個文件夾作為網站基礎，並複製這個文件夾地址（如C:\Files\Desktop\website）；
5. 用管理員身份打開CMD或Powershell, 輸入` cd "文件夾地址"`，回車，然後再輸入`hexo init`來創建網站，這一步要耐心等待，可能有些花時間。完成後輸入`npm install`來完成安裝。

![](https://cdn.patrickwu.space/posts/dev/hg/6.png)

## Hexo架構

新建完成後，指定文件夾的目錄如下：

```
.
├── _config.yml
├── package.json
├── scaffolds
├── source
|   ├── _drafts
|   └── _posts
└── themes
```

### _config.yml
網站的**配置**信息，您可以在此配置大部分的參數。

### package.json
應用程序的信息。EJS, Stylus 和 Markdown renderer 已默認安裝，您可以自由移除。
```json
package.json
{
  "name": "hexo-site",
  "version": "0.0.0",
  "private": true,
  "hexo": {
    "version": ""
  },
  "dependencies": {
    "hexo": "^3.0.0",
    "hexo-generator-archive": "^0.1.0",
    "hexo-generator-category": "^0.1.0",
    "hexo-generator-index": "^0.1.0",
    "hexo-generator-tag": "^0.1.0",
    "hexo-renderer-ejs": "^0.1.0",
    "hexo-renderer-stylus": "^0.2.0",
    "hexo-renderer-marked": "^0.2.4",
    "hexo-server": "^0.1.2"
  }
}
```

### scaffolds
模版 文件夾。當您新建文章時，Hexo 會根據 scaffold 來建立文件。

Hexo的模板是指在新建的markdown文件中默認填充的內容。例如，如果您修改scaffold/post.md中的Front-matter內容，那麼每次新建一篇文章時都會包含這個修改。

### source
資源文件夾是存放用户資源的地方。除 _posts 文件夾之外，開頭命名為 _ (下劃線)的文件 / 文件夾和隱藏的文件將會被忽略。Markdown 和 HTML 文件會被解析並放到 public 文件夾，而其他文件會被拷貝過去。

### themes
主題 文件夾。Hexo 會根據主題來生成靜態頁面。

## 配置你的Hexo網站

您可以在 `_config.yml` 中修改大部份的配置。

### 網站

| 參數            | 描述                                       |
| ------------- | ---------------------------------------- |
| `title`       | 網站標題                                     |
| `subtitle`    | 網站副標題                                    |
| `description` | 網站描述                                     |
| `author`      | 您的名字                                     |
| `language`    | 網站使用的語言                                  |
| `timezone`    | 網站時區。Hexo 默認使用您電腦的時區。[時區列表](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones)。比如説：`America/New_York`, `Japan`, 和 `UTC` 。 |

其中，`description`主要用於SEO，告訴搜索引擎一個關於您站點的簡單描述，通常建議在其中包含您網站的關鍵詞。`author`參數用於主題顯示文章的作者。

### 網址

| 參數                   | 描述                                       | 默認值                         |
| -------------------- | ---------------------------------------- | --------------------------- |
| `url`                | 網址（輸入你的github.io地址，就是\*\*\*\*.github.io  |                             |
| `root`               | 網站根目錄                                    |                             |
| `permalink`          | 文章的 [永久鏈接](https://hexo.io/zh-cn/docs/permalinks.html) 格式 | `:year/:month/:day/:title/` |
| `permalink_defaults` | 永久鏈接中各部分的默認值                             |                             |

> 網站存放在子目錄
>
> 如果您的網站存放在子目錄中，例如 `http://yoursite.com/blog`，則請將您的 `url` 設為 `http://yoursite.com/blog` 並把 `root` 設為 `/blog/`。

### 目錄

| 參數             | 描述                                       | 默認值              |
| -------------- | ---------------------------------------- | ---------------- |
| `source_dir`   | 資源文件夾，這個文件夾用來存放內容。                       | `source`         |
| `public_dir`   | 公共文件夾，這個文件夾用於存放生成的站點文件。                  | `public`         |
| `tag_dir`      | 標籤文件夾                                    | `tags`           |
| `archive_dir`  | 歸檔文件夾                                    | `archives`       |
| `category_dir` | 分類文件夾                                    | `categories`     |
| `code_dir`     | Include code 文件夾                         | `downloads/code` |
| `i18n_dir`     | 國際化（i18n）文件夾                             | `:lang`          |
| `skip_render`  | 跳過指定文件的渲染，您可使用 [glob 表達式](https://github.com/isaacs/node-glob)來匹配路徑。 |                  |

> 提示
>
> 如果您剛剛開始接觸Hexo，通常沒有必要修改這一部分的值。

### 文章

| 參數                  | 描述                                       | 默認值       |
| ------------------- | ---------------------------------------- | --------- |
| `new_post_name`     | 新文章的文件名稱                                 | :title.md |
| `default_layout`    | 預設佈局                                     | post      |
| `auto_spacing`      | 在中文和英文之間加入空格                             | false     |
| `titlecase`         | 把標題轉換為 title case                        | false     |
| `external_link`     | 在新標籤中打開鏈接                                | true      |
| `filename_case`     | 把文件名稱轉換為 (1) 小寫或 (2) 大寫                  | 0         |
| `render_drafts`     | 顯示草稿                                     | false     |
| `post_asset_folder` | 啟動 [Asset 文件夾](https://hexo.io/zh-cn/docs/asset-folders.html) | false     |
| `relative_link`     | 把鏈接改為與根目錄的相對位址                           | false     |
| `future`            | 顯示未來的文章                                  | true      |
| `highlight`         | 代碼塊的設置                                   |           |

> 相對地址
>
> 默認情況下，Hexo生成的超鏈接都是絕對地址。例如，如果您的網站域名為`example.com`,您有一篇文章名為`hello`，那麼絕對鏈接可能像這樣：`http://example.com/hello.html`，它是**絕對**於域名的。相對鏈接像這樣：`/hello.html`，也就是説，無論用什麼域名訪問該站點，都沒有關係，這在進行反向代理時可能用到。通常情況下，建議使用絕對地址。

### 分類 & 標籤

| 參數                 | 描述   | 默認值             |
| ------------------ | ---- | --------------- |
| `default_category` | 默認分類 | `uncategorized` |
| `category_map`     | 分類別名 |                 |
| `tag_map`          | 標籤別名 |                 |

### 日期 / 時間格式

Hexo 使用 [Moment.js](http://momentjs.com/) 來解析和顯示時間。

| 參數            | 描述   | 默認值          |
| ------------- | ---- | ------------ |
| `date_format` | 日期格式 | `YYYY-MM-DD` |
| `time_format` | 時間格式 | `H:mm:ss`    |

### 分頁

| 參數               | 描述                    | 默認值    |
| ---------------- | --------------------- | ------ |
| `per_page`       | 每頁顯示的文章量 (0 = 關閉分頁功能) | `10`   |
| `pagination_dir` | 分頁目錄                  | `page` |

### 擴展

| 參數       | 描述                    |
| -------- | --------------------- |
| `theme`  | 當前主題名稱。值為`false`時禁用主題 |
| `deploy` | 部署部分的設置               |

## 開始寫文章！
文章位於`source/_post`文件夾內，以markdown為格式。要新建一篇文章，在網站根目錄中打開CMD或Powershell,輸入`hexo new "文章標題"`便可創建文章。文章分為Front-matter和內容。

### Front-matter

Front-matter 是文件最上方以 `---` 分隔的區域，用於指定個別文件的變量，舉例來説：

```
title: Hello World
date: 2013/7/13 20:46:25
---
```

以下是預先定義的參數，您可在模板中使用這些參數值並加以利用。

| 參數           | 描述         | 默認值    |
| ------------ | ---------- | ------ |
| `layout`     | 佈局         |        |
| `title`      | 標題         |        |
| `date`       | 建立日期       | 文件建立日期 |
| `updated`    | 更新日期       | 文件更新日期 |
| `comments`   | 開啟文章的評論功能  | true   |
| `tags`       | 標籤（不適用於分頁） |        |
| `categories` | 分類（不適用於分頁） |        |
| `permalink`  | 覆蓋文章網址     |        |

#### 分類和標籤

只有文章支持分類和標籤，您可以在 Front-matter 中設置。在其他系統中，分類和標籤聽起來很接近，但是在 Hexo 中兩者有着明顯的差別：分類具有順序性和層次性，也就是説 `Foo, Bar` 不等於 `Bar, Foo`；而標籤沒有順序和層次。

```yaml
categories:
- Diary
tags:
- PS3
- Games
```

> 分類方法的分歧
>
> 如果您有過使用WordPress的經驗，就很容易誤解Hexo的分類方式。WordPress支持對一篇文章設置多個分類，而且這些分類可以是同級的，也可以是父子分類。但是Hexo不支持指定多個同級分類。下面的指定方法：
>
> ```yaml
> categories:
> - Diary
> - Life
> ```
>
> 會使分類`Life`成為`Diary`的子分類，而不是並列分類。因此，有必要為您的文章選擇儘可能準確的分類。

### 內容
內容以Markdown和標籤(tag plugin)來輸入。此處附上一份[Markdown説明手冊](http://blog.leanote.com/post/freewalk/Markdown-語法手冊)和[官方的插件説明](https://hexo.io/zh-cn/docs/tag-plugins.html)給大家*其實是懶得打了（滑稽*。

## 發佈

寫完了，當然要發佈啦！最簡單的方法是安裝官方的git deploy插件：在網站根目錄輸入`npm install hexo-deployer-git --save`

然後修改_config.yml

```
deploy:
  type: git
  repo: <repository url>
  branch: [branch]
  message: [message]
```

| 參數        | 描述                                       |
| --------- | ---------------------------------------- |
| `repo`    | 庫（Repository）地址,不要輸入SSH地址！               |
| `branch`  | 分支名稱。如果您使用的是 GitHub 或 GitCafe 的話，程序會嘗試自動檢測。 |
| `message` | 自定義提交信息 (默認為 `Site updated: \{\{ now('YYYY-MM-DD HH:mm:ss') \}\}`) |

然後在網站根目錄輸入`hexo deploy`就可以發佈啦！
