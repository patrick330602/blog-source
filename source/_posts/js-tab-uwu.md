---
title: Implement a "tab" in JavaScript
tags:
  - Javascript
date: 2020-11-09 15:53:25
---


## TL;DR

This article is a quick guide on building a simple tab on a webpage using vanilla JavaScript.

## WHY?

If you saw this post, You will know that I already remove the tab on my homepage. I decided that I don't want the tab on my site anymore; so I decided to write an article about this to archive it. Here is how it works, and it is all happening on the same page:

![demonstration of Tab](https://cdn.patrickwu.space/posts/dev/js-tab.gif)

Some people might be already shouting at me for using vanilla JavaScript; To be honest, I am not that kind of JavaScript framework guy; I like to keep thing simple (but most of the time they turn out to be spaghetti) like this site (Like, I literally inherited the CSS stylesheet from [bettermotherf**kingwebsite.com](http://bettermotherfuckingwebsite.com); more details [here](https://patrickwu.space/2020/01/04/a-even-better-motherfing-website/)).

Well, less talking more writing.

## The real part

<script async src="//jsfiddle.net/callmepk/oqht7k1c/1/embed/js,html,css,result/dark/"></script>

I can just that there and call it for a day, you know. Well, I will not. I will, at least, explain some parts of it.

First, we got the function `openSection()` that we used in `onclick` property:

```js
function openSection(SecName) {
 var i, tabcontent;
 tabcontent = document.getElementsByClassName("tabcontent");
 for (i = 0; i < tabcontent.length; i++) {
 tabcontent[i].style.display = "none";
. }
 document.getElementById(SecName).style.display = "block";
}
```

This function will try to find all tag with class name `tabcontent` and set their `display` to `none`. Then, find the `tabcontent` with the name passed from `SecName` and set only that to `block`.

For the `window.onload` event, this allows you to load the default tab by id.

```js
window.onload = function() {
 document.getElementById('default-tab').click()
}
```

You might be curious with the CSS part, since it looks useless:

```css
.tabcontent {
 display: none;
}
```

However, this is very important. This small piece of ~~spaghetti~~ CSS prevents the situation in some browser that, for some reason, it is not fast enough to set the display to none, causing the web page to look bad when loading.

## Conclusion

This article is just about a tiny piece of JavaScript. I hope you find it useful, but I knew most of you already know about this ;) Also, check out your browser console if you haven't.

