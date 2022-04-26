---
title: Switching Images when Changing System Theme
tags:
  - HTML
date: 2022-04-26 14:25:19
---


Following system color scheme in HTML easily achieved since the availability of `prefers-color-scheme` in CSS. This website also uses it to automatically switch themes. But right now I need to switch images according to the color scheme. 

A quick search online reveals that most solutions are using JavaScript to achieve the goal, which would be not ideal. This is because:

1. My major goal is to reduce the usage of JavaScript on this site, so user can still visit the website without losing experience;
2. Some places requires no JapaScripts, thus unable to implement.

After some more digging, I discovered [this article on WebKit blog](https://webkit.org/blog/8840/dark-mode-support-in-webkit/). As mentioned in this article, we can use `<picture>` to achieve the goal:

```html 
<picture>
    <source srcset="dark.jpg" media="(prefers-color-scheme: dark)">
    <img src="light.jpg">
</picture>
```
    
Just simply replace `light.jpg` and `dark.jpg` to the desired image path and it works like a charm.

One thing I am worried about is whether it supports other browsers like chromium-based ones. After testing in Microsoft Edge and Brave, the code do work on both chromium-based browsers.

