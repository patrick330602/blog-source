---
title: Detect Browser in Javascript
date: 2018-01-18 22:03:25
tags:
- Javascript
---
Here is a easy way to find out what browser the user is using when they browse this page; supports 6 most commonly used browsers, but it might not work on mobile since operating systems like iOS will force browser to use their core. 
<!--more-->
```javascript
// Opera 8.0+
var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

// Firefox 1.0+
var isFirefox = typeof InstallTrigger !== 'undefined';

// Safari 3.0+ "[object HTMLElementConstructor]"
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 || (function (
    p) {
    return p.toString() === "[object SafariRemoteNotification]";
})(!window['safari'] || safari.pushNotification);

// Internet Explorer 6-11
var isIE = /*@cc_on!@*/ false || !!document.documentMode;

// Edge 20+
var isEdge = !isIE && !!window.StyleMedia;

// Chrome 1+
var isChrome = !!window.chrome && !!window.chrome.webstore;

var data = isOpera ? 'opera' :
    isFirefox ? 'firefox' :
    isSafari ? 'safari' :
    isChrome ? 'chrome' :
    isIE ? 'ie' :
    isEdge ? 'edge' :
    "browser";
```