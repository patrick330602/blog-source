---
title: An even better motherf**king website
date: 2020-1-4 16:07:25
tags:
- Web
- CSS
- HTML
---

The one thing I hate about the modern Internet is that it fills with too many fancy CSS elements, long-running JavaScripts, cookies to "*improve your browsing experiences*", random popup to "**SUBSCRIBE NOW!**" They just made my brain hurt. 

One day, I found a website called, uh, [Motherf**king Website](http://motherfuckingwebsite.com)[^1]. It's an extrmly simple website with only the following CSS:

[^1]: Censored f-words in this article for good. My blog is family friendly (well, technically, but this is not YouTube).

```css OwO whewe iws the css
⠀ 
```

Yeah, it doesn't even have a CSS[^2]! But it really did just work. Then, unsurprisingly, I found a sequel to that website called [Better Motherf**cking Website](http://bettermotherfuckingwebsite.com).

[^2]: I used a special space here called [BRAILLE PATTERN BLANK](https://en.wiktionary.org/wiki/⠀), `U+2800` if anyone need it

The heck?

But it did look better than the previous one. Well, I dig it although I know both of the sites are satire. Here is the original CSS source for the second one:

```css
body{
  margin:40px auto;
  max-width:650px;
  line-height:1.6;
  font-size:18px;
  color:#444;
  padding:0 10px
}
h1,h2,h3{
  line-height:1.2
}
```

However, as the author of the website stated:

> I love what the creator of this site's inspiration did. What I'm saying is that it's so, so simple to make sites easier to read. Websites are broken by default, they are functional, high-performing, and accessible, but they're also fucking ugly. You and all the other web designers out there need to make them not total shit.

How about... no?

Nah, actually he(or she)'s definitely right, it will need more CSS extension. But first, I need somewhere to test it first before putting on the main site, and the [Access](https://access.patrickwu.space) is the perfect place. It actually doesn't look bad! So I started to make a fork of this CSS. Here is what I have done:

For the base, I just add an extra `background-color` property in `body`, just for helping the dynamic dark/light theme on my website:

```css original.css
body {
  margin: 40px auto;
  max-width: 650px;
  line-height: 1.6;
  font-size: 18px;
  background-color: #DDD;
  color: #333;
  padding: 0 10px;
}

h1,
h2,
h3 {
  line-height: 1.2
}
```

To make heading more obvious, I did some content appending in CSS:

```css header.css
h1:before {
  content: "# ";
}

h2:before {
  content: "## ";
}

h3:before {
  content: "### ";
}

h4:before {
  content: "#### ";
}

h5:before {
  content: "##### ";
}

h6:before {
  content: "###### ";
}

h1 a:hover,
h1 a:active {
  text-decoration: none;
}
```

This part is the extension on link to make it less ugly compared to default:

```css link.css
a:link {
  text-decoration: underline;
  color: #555;
}

a:visited {
  text-decoration: none;
  color: #777;
}

a:hover,
a:active {
  text-decoration: underline;
  color: #77beec;
}
```
I also try to fix some distortedly-rendered element in Hexo:
```css general.css
img {
  outline: none;
  border: none;
  max-width: 100%;
}

table {
  width: 100%;
}

blockquote {
  border-left-width: 2px;
  border-left-color: gray;
  border-left-style: solid;
  padding: 0 10px 0;
}
```
I also made some improvements on post tag cloud on the homepage to better distinguish them:
```css tag.css 
.hometag {
  border: 1px solid #555;
  margin: 5px;
  padding: 0 5px;
  color: #555;
  border-radius: 5px;
  display: inline-block;
}
```

By default, these code block generated is distorted and not good for viewing. I styled them here:
```css codeblock.css
pre {
  font: 0.8em/1.0em "Courier New", Courier;
  color: #222;
}

code {
  font: "Courier New", Courier;
  color: #AAA;
  background: #444;
  padding: 4px;
  font-size: 16px;
}

.highlight {
  border-color: #555;
  border-style: solid;
  border-width: 0.5px;
  border-radius: 5px;
  overflow: auto;
  background: #DDD;
}

.highlight .gutter {
  border-right-color: #555;
  border-right-style: solid;
  border-right-width: 0.5px;
  padding-left: 10px;
  padding-right: 10px;
  text-align: right;
}

.highlight .code {
  width: 100%;
  padding-left: 10px;
}

.highlight figcaption {
  border-bottom-color: #555;
  border-bottom-style: solid;
  border-bottom-width: 0.5px;
}

.highlight figcaption span {
  margin: 5px;
}
```
This is to improve the look of scrollbar as the default one is problematic with dark theme I implemented later:
```css scrollbar
::-webkit-scrollbar {
  width: 10px;
  height: 10px
}

::-webkit-scrollbar-button {
  width: 0;
  height: 0
}

::-webkit-scrollbar-button:end:decrement,
::-webkit-scrollbar-button:start:increment {
  display: none
}

::-webkit-scrollbar-corner {
  display: block
}

::-webkit-scrollbar-thumb {
  border-radius: 8px;
  background-color: rgba(0, 0, 0, .2)
}

::-webkit-scrollbar-thumb:hover {
  border-radius: 8px;
  background-color: rgba(0, 0, 0, .5)
}

::-webkit-scrollbar-track:hover {
  background-color: rgba(0, 0, 0, .15)
}

::-webkit-scrollbar-thumb,
::-webkit-scrollbar-track {
  border-right: 1px solid transparent;
  border-left: 1px solid transparent;
}

::-webkit-scrollbar-button:end,
::-webkit-scrollbar-button:start {
  width: 10px;
  height: 10px
}
```
Lastly, I added dark/light theme support using `prefers-color-scheme`, and it looks good at night:
```css dynamic_theme.css
@media (prefers-color-scheme: dark) {
  body {
    background-color: #222;
    color: #CCC;
  }

  a:link {
    color: #AAA;
  }

  a:visited {
    color: #999;
  }

  pre {
    color: #999;
  }

  code {
    color: #222;
    background: #AAA;
  }

  .tag {
    border-color: #AAA;
    color: #AAA;
  }

  .highlight {
    border-color: #DDD;
    background: #222;
  }

  .highlight .gutter {
    border-right-color: #DDD;
  }

  .highlight figcaption {
    border-bottom-color: #DDD;
  }

  ::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, .2)
  }

  ::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, .5)
  }

  ::-webkit-scrollbar-track:hover {
    background-color: rgba(255, 255, 255, .15)
  }
}

@media (prefers-color-scheme: light) {
  body {
    background-color: #DDD;
    color: #333;
  }

  a:link {
    color: #555;
  }

  a:visited {
    color: #777;
  }

  pre {
    color: #222;
  }

  code {
    color: #999;
    background: #222;
  }

  .tag {
    border-color: #555;
    color: #555;
  }

  .highlight {
    border-color: #555;
    background: #DDD;
  }

  .highlight .gutter {
    border-right-color: #555;
  }

  .highlight figcaption {
    border-bottom-color: #555;
  }

  ::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, .2)
  }

  ::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, .5)
  }

  ::-webkit-scrollbar-track:hover {
    background-color: rgba(0, 0, 0, .15)
  }

}
```

Thanks for taking your time to read this random pointless article that possibly not going to help you ;)