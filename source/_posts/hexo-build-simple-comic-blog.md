---
title: Build a static comic blog using Hexo
tags:
  - Hexo
  - JavaScript
date: 2022-04-03 22:45:17
---

## TL;DR

This is an article about building a simple static comic blog that behaves like [xkcd](https://xkcd.com) with `ejs` as template engine and vanilla JavaScriptt.

## Self-hosted and static

I have been wanting to build my own comic blog for... (checks my last comic) a year now. One thing I always trying to achieve is to make it simple and static, and self-hosted. 

The first thing I thought is to use ComicPress on WordPress that being used by a lot of people including the famous [xkcd](xkcd.com). After I tried to setup WordPress I realised the ComicPress no longer exist on the plugin store for the newer version, and the existing ones like Comic Easel is not what I expected, since they all tried to load comic on a subpage like `domain/comic/`.

This leads me to go back to Hexo. Unfortunately, there is **no** information about building comic website on Hexo. Only one I found is a theme called [funnies](https://github.com/rudism/hexo-theme-funnies) and it is broken in the latest version of Hexo. This leads me to the idea of building my own comic site using Hexo.

## To the latest comic!

Firstly, I tried to redirect the index page(`index.ejs`) to the latest comic:

```ejs index.ejs
<meta http-equiv='refresh' content="0;URL='<%- url_for(site.posts.sort('date').toArray().slice(-1)[0].path) %>'" />
```

This will redirect index page to the latest comic page, a.k.a. `<%- url_for(site.posts.sort('date').toArray().slice(-1)[0].path) %>`. 

This one-liner is pretty simple: get all posts, sort by date, convert to Array, get the last item, and then get the `path` property and pass to the Hexo built-in function `url_for()` to get the URL. 

Also, you should also consider the people who block auto-redirect by include a link to the latest comic too:

```ejs index.ejs
<b>If auto-redirect is not working, click <a href="<%- url_for(site.posts.sort('date').toArray().slice(-1)[0].path) %>">here</a>.</b>
```

## The navigation

The navigation I am referring to is the thing like this:

![The comic navigation bar in xkcd.com](https://cdn.patrickwu.space/posts/dev/hexo-comic/1.png)

Yes, that one in the red rectangle: *First*(`|<`), *Prev*, *Random*, *Next* and *Last*(`>|`).

For the *First*, *Prev*, *Next* and *Last*(`>|`), the code will look like the following:

```ejs navigation.ejs
<nav>
  <% var sorted = site.posts.sort('date').toArray() %>
  <% if (page.next){ %>
    <a class="post-nav-title" href="<%- url_for(sorted[0].path) %>">First</a>&nbsp;&nbsp;
    <a class="post-nav-title" href="<%- url_for(page.next.path) %>">Prev</a>&nbsp;&nbsp;
  <% } else { %>
    First&nbsp;&nbsp;
    Prev&nbsp;&nbsp;
  <% } %>
<a class="post-nav-title" id="random" href="#">Random</a>&nbsp;&nbsp;
  <% if (page.prev){ %>
    <a class="post-nav-title" href="<%- url_for(page.prev.path) %>">Next</a>&nbsp;&nbsp;
    <a class="post-nav-title" href="<%- url_for(sorted.slice(-1)[0].path) %>">Last</a>
  <% } else { %>
    Next&nbsp;&nbsp;
    Last
  <% } %>
</nav>
```

Let's not talk about that *Random* link for now. As you can see, it is really similar to the redirection code, since they are just like the standard pagination.

You possibly noticed that the text for `page.next` and `page.prev` are actually **Prev** and **Next**. This is because the default sorting used in `page.next` and `page.prev` is from oldest to newest (a.k.a. The order of `site.posts.sort('date')`), and that is why it is named like this.

## Random: the mix of JS and EJS

*Random* is possibly the most interesting part of code, since it is a mix of JavaScript and EJS. 

```ejs header.ejs
...

<script type="text/javascript">
  //<![CDATA[
    <% var tmp_list = "";
    site.posts.toArray().forEach((i, ind, array)=>{
      if (i.path !== page.path) {
        tmp_list = tmp_list + '"'+url_for(i.path)+'"';
        if (ind < array.length-1) tmp_list = tmp_list + ',';
      }
    }) %>
  
  (function() {
      window.onload = function() {
        var a = document.getElementById("random");
        a.onclick = function() {
          var posts = [<%- tmp_list %>];
          var random = Math.floor(Math.random() * posts.length);
          window.location.href = posts[random];
          return;
        }
      }
  }());
  //]]>
  </script>

...
```

As you can see, it consists of two part: Line 3 - 11 is the ejs part, and the rest is JavaScript part. The ejs part will generate a string  named `tmp_list` that looks like a JavaScript list content for the paths of all comic, and then we pass the output to the JavaScript part of the `posts` variable, and we will perform the normal randomize process using JavaScript.

## `comic` tag in posts; and RSS

This part is rather simple: add following code in your `post.ejs`:

```ejs post.ejs
...

<% if (page.comic) { %>
		<img src="<%= page.comic %>" alt="<%= page.title %>" />
<% } %>
		
...
```

However, if you generate RSS feed, this would be problematic since there will be no comic in your RSS xml. 

So firstly, add following content to your custom RSS template:

```xml rss.xml
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0"
  xmlns:atom="http://www.w3.org/2005/Atom"
  xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title>{{ config.title }}</title>
    <link>{{ url }}</link>
    {% if icon %}
    <image>
      <url>{{ icon }}</url>
      <title>{{ config.title }}</title>
      <link>{{ url }}</link>
    </image>
    {% endif %}
    <atom:link href="{{ feed_url | uriencode }}" rel="self" type="application/rss+xml"/>
    {% if config.feed.hub %}<atom:link href="{{ config.feed.hub | uriencode }}" rel="hub"/>{% endif %}
    <description>{{ config.description }}</description>
    <pubDate>{% if posts.first().updated %}{{ posts.first().updated.toDate().toUTCString() }}{% else %}{{ posts.first().date.toDate().toUTCString() }}{% endif %}</pubDate>
    <generator>http://hexo.io/</generator>
    {% for post in posts.toArray() %}
    <item>
      <title>{{ post.title }}</title>
      <link>{{ post.permalink | uriencode }}</link>
      <guid>{{ post.permalink | uriencode }}</guid>
      <pubDate>{{ post.date.toDate().toUTCString() }}</pubDate>
      <description>{{ '<p><img src="' + post.comic + '"/></p>' + post.content }}</description>
      {% for category in post.categories.toArray() %}
      <category domain="{{ url + category.path | uriencode }}">{{ category.name }}</category>
      {% endfor %}
      {% for tag in post.tags.toArray() %}
      <category domain="{{ url + tag.path | uriencode }}">{{ tag.name }}</category>
      {% endfor %}
    </item>
    {% endfor %}
  </channel>
</rss>
```
 
Then, add or change the following content in our `_config.yml`:

```yml _config.yml
...

exclude:
- 'path/to/rss.xml'

...

feed:
  template:
    - path/to/rss.xml

...
```

Please keep in mind: do not include any `type` under `feed`, since they will overwrite the custom template. Then, you should have a properly generated RSS!

## Endnote

With researching and implementing everything, it only took me about 6 hours to complete the theme. I bellieve that now you have all the resources you need, you can definitely build a similar site faster than me. I am looking forward for your own comic site on Hexo!
