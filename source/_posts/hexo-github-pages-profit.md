---
title: Hexo + GitHub Pages + ??? = Profit
date: 2017-06-27 09:11:10
tags:
- Hexo
- Shell
---

## Introduction

Hexo is an easy(nope) generator for Hexo blog, with help of GitHub Pages, it will be a perfect platform for blogging. However, to newbies, Hexo is still hard to use and configure, so I am here to claim first:

1. Hexo is hard, you need to patience to learn;
2. You have also be willing to learn and solve problems;
3. You don't need to understand HTML, actually.

## Create GitHub Pages

1. Open <github.com>, choose the plus icon at top left corner and choose "new repository";

![](https://cdn.patrickwu.space/posts/dev/hg/1.png)

2. Type "<yourname>.github.io" in Repository Name. if you name is `dalao`, you should type dalao.github.io, tick "Initialize this repository with a README", and the click "Create Repository";

![](https://cdn.patrickwu.space/posts/dev/hg/2.png)

3. Then you repository is ready. Choose "Settings", untick the "Wikis", "Issues" and "Projects" under "Feature".

![](https://cdn.patrickwu.space/posts/dev/hg/3.png)

Now, your GitHub Pages is ready.

## Install Hexo

1. Download [GitHub Client](https://desktop.github.com) and install, open it and log in first;

![](https://cdn.patrickwu.space/posts/dev/hg/4.png)

2. Go to [NodeJS Website](https://nodejs.org/),Download and install Current or LTS version;

![](https://cdn.patrickwu.space/posts/dev/hg/5.png)

3. Open a command prompt or Powershell with administration rights, type `npm install -g hexo-cli` and waiting for it to complete;
4. Create a folder for your website base, and copy its address, like *C:\Files\Desktop\website*;
5. Open a command prompt or Powershell with administration rights,type`cd "your copied address"` and type enter, then type `hexo init`and then `npm install`to create the website.

![](https://cdn.patrickwu.space/posts/dev/hg/6.png)

## Hexo Framework

Once initialised, here’s what your project folder will look like:

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

Site [configuration](https://hexo.io/docs/configuration.html) file. You can configure most settings here.

### package.json

Application data. The [EJS](http://embeddedjs.com/), [Stylus](http://learnboost.github.io/stylus/) and [Markdown](http://daringfireball.net/projects/markdown/) renderers are installed by default. If you want, you can uninstall them later.

```json
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

[Scaffold](https://hexo.io/docs/writing.html#Scaffolds) folder. When you create a new post, Hexo bases the new file on the scaffold.

### source

Source folder. This is where you put your site’s content. Hexo ignores hidden files and files or folders whose names are prefixed with `_` (underscore) - except the `_posts` folder. Renderable files (e.g. Markdown, HTML) will be processed and put into the `public` folder, while other files will simply be copied.

### themes

[Theme](https://hexo.io/docs/themes.html) folder. Hexo generates a static website by combining the site contents with the theme.

## Configure your Websites



You can modify site settings in `_config.yml` or in an [alternate config file](https://hexo.io/docs/configuration.html#Using-an-Alternate-Config).

### Site

| Setting       | Description                              |
| ------------- | ---------------------------------------- |
| `title`       | The title of your website                |
| `subtitle`    | The subtitle of your website             |
| `description` | The description of your website          |
| `author`      | Your name                                |
| `language`    | The language of your website. Use a [2-lettter ISO-639-1 code](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes). Default is `en`. |
| `timezone`    | The timezone of your website. Hexo uses the setting on your computer by default. You can find the list of available timezones [here](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones). Some examples are `America/New_York`, `Japan`, and `UTC`. |

### URL

| Setting              | Description                              | Default                     |
| -------------------- | ---------------------------------------- | --------------------------- |
| `url`                | The URL of your website                  |                             |
| `root`               | The root directory of your website       |                             |
| `permalink`          | The [permalink](https://hexo.io/docs/permalinks.html) format of articles | `:year/:month/:day/:title/` |
| `permalink_defaults` | Default values of each segment in permalink |                             |

> Website in subdirectory
>
> If your website is in a subdirectory (such as `http://example.org/blog`) set `url` to `http://example.org/blog` and set `root` to `/blog/`.

### Directory

| Setting        | Description                              | Default          |
| -------------- | ---------------------------------------- | ---------------- |
| `source_dir`   | Source folder. Where your content is stored | `source`         |
| `public_dir`   | Public folder. Where the static site will be generated | `public`         |
| `tag_dir`      | Tag directory                            | `tags`           |
| `archive_dir`  | Archive directory                        | `archives`       |
| `category_dir` | Category directory                       | `categories`     |
| `code_dir`     | Include code directory                   | `downloads/code` |
| `i18n_dir`     | i18n directory                           | `:lang`          |
| `skip_render`  | Paths not to be rendered. You can use [glob expressions](https://github.com/isaacs/minimatch) for path matching |                  |

### Writing

| Setting             | Description                              | Default     |
| ------------------- | ---------------------------------------- | ----------- |
| `new_post_name`     | The filename format for new posts        | `:title.md` |
| `default_layout`    | Default layout                           | `post`      |
| `titlecase`         | Transform titles into title case?        | `false`     |
| `external_link`     | Open external links in new tab?          | `true`      |
| `filename_case`     | Transform filenames to `1` lower case; `2` upper case | `0`         |
| `render_drafts`     | Display drafts?                          | `false`     |
| `post_asset_folder` | Enable the [Asset Folder](https://hexo.io/docs/asset-folders.html)? | `false`     |
| `relative_link`     | Make links relative to the root folder?  | `false`     |
| `future`            | Display future posts?                    | `true`      |
| `highlight`         | Code block settings                      |             |

### Category & Tag

| Setting            | Description      | Default         |
| ------------------ | ---------------- | --------------- |
| `default_category` | Default category | `uncategorized` |
| `category_map`     | Category slugs   |                 |
| `tag_map`          | Tag slugs        |                 |

### Date / Time format

Hexo uses [Moment.js](http://momentjs.com/) to process dates.

| Setting       | Description | Default      |
| ------------- | ----------- | ------------ |
| `date_format` | Date format | `YYYY-MM-DD` |
| `time_format` | Time format | `HH:mm:ss`   |

### Pagination

| Setting          | Description                              | Default |
| ---------------- | ---------------------------------------- | ------- |
| `per_page`       | The amount of the posts displayed on a single page. `0` disables pagination | `10`    |
| `pagination_dir` | Pagination directory                     | `page`  |

### Extensions

| Setting  | Description                          |
| -------- | ------------------------------------ |
| `theme`  | Theme name. `false` disables theming |
| `deploy` | Deployment setting                   |

### Include/Exclude Files or Folders

In the config file, set the include/exlude key to make hexo explicitly process or ignore certain files/folders.

| Setting   | Description                              |
| --------- | ---------------------------------------- |
| `include` | Hexo defaultly ignore hidden files and folders, but set this field will make Hexo process them |
| `exclude` | Hexo process will ignore files list under this field |

Sample:

```
# Include/Exclude Files/Folders
include:
  - .nojekyll
exclude:
  - .DS_Store
```

### Using an Alternate Config

A custom config file path can be specified by adding the `--config` flag to your `hexo` commands with a path to an alternate YAML or JSON config file, or a comma-separated list (no spaces) of multiple YAML or JSON files.

```
# use 'custom.yml' in place of '_config.yml'
$ hexo server --config custom.yml

# use 'custom.yml' & 'custom2.json', prioritizing 'custom2.json'
$ hexo server --config custom.yml,custom2.json
```

Using multiple files combines all the config files and saves the merged settings to `_multiconfig.yml`. The later values take precedence. It works with any number of JSON and YAML files with arbitrarily deep objects. Note that **no spaces are allowed in the list**.

For instance, in the above example if `foo: bar` is in `custom.yml`, but `"foo": "dinosaur"` is in `custom2.json`, `_multiconfig.yml` will contain `foo: dinosaur`.

## Start Writing!
posts are located in `source/_post`as a markdown file. To create a new article, type `hexo new "your title"` to create one. the file can be devided into Front-matter and content.

### Front-matter

Front-matter is a block of YAML or JSON at the beginning of the file that is used to configure settings for your writings. Front-matter is terminated by three dashes when written in YAML or three semicolons when written in JSON.

**YAML**

```
title: Hello World
date: 2013/7/13 20:46:25
---
```

**JSON**

```
"title": "Hello World",
"date": "2013/7/13 20:46:25"
```
#### Settings & Their Default Values

| Setting      | Description                              | Default           |
| ------------ | ---------------------------------------- | ----------------- |
| `layout`     | Layout                                   |                   |
| `title`      | Title                                    |                   |
| `date`       | Published date                           | File created date |
| `updated`    | Updated date                             | File updated date |
| `comments`   | Enables comment feature for the post     | true              |
| `tags`       | Tags (Not available for pages)           |                   |
| `categories` | Categories (Not available for pages)     |                   |
| `permalink`  | Overrides the default permalink of the post |                   |

#### Categories & Tags

Only posts support the use of categories and tags. Categories apply to posts in order, resulting in a hierarchy of classifications and sub-classifications. Tags are all defined on the same hierarchical level so the order in which they appear is not important.

**Example**

```
categories:
- Sports
- Baseball
tags:
- Injury
- Fight
- Shocking
```

### Content
content can be enriched by Markdown and tag plugin.You can learn tag plugin [here](https://hexo.io/docs/tag-plugins.html) and Markdown [here](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet).

## Deploy

The easiest way to deploy is to use the offical plugin: `npm install hexo-deployer-git --save`

Edit `_config.yml`

```
deploy:
  type: git
  repo: <repository url>
  branch: [branch]
  message: [message]
```

| Option    | Description                              |
| --------- | ---------------------------------------- |
| `repo`    | GitHub/Bitbucket/Coding/GitLab repository URL |
| `branch`  | Branch name. The deployer will detect the branch automatically if you are using GitHub or GitCafe. |
| `message` | Customize commit message (Default to `Site updated: \{\{ now('YYYY-MM-DD HH:mm:ss') \}\}`) |

and then type `hexo deploy` to deploy the website.