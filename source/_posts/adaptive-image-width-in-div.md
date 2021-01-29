---
title: DIV下圖片自適應解決方法
date: 2008-08-05 15:45:19
tags:
- Web
- HTML
- CSS
---
以前的解決方法主要是利用js來實現，但用過的人都知道該辦法有點繁瑣。還有一種是在外部容器定義`over-flow:hidden`。但這種辦法只會切割圖片而不會自動適用。

<!--more-->

## 固定像素適應
以下是引用片段：
```html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd";>
<html xmlns="http://www.w3.org/1999/xhtml";>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>css2.0 VS ie</title>
<style type="text/css">
<!--
body {
font-size: 12px;
text-align: center;
margin: 0px;
padding: 0px;
}
#pic{
  margin:0 auto;
  width:800px;
  padding:0;
  border:1px solid #333;
  }
#pic img{
    max-width:780px;
width:expression(document.body.clientWidth > 780? "780px": "auto" );
border:1px dashed #000;
}
-->
</style>
</head>
<body>
<div id="pic">
<img src="/articleimg/2006/03/3297/koreaad_10020.jpg"/>
</div>
</body>
</html>
```


## 百分比適應
以下是引用片段：
```html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd";>
<html xmlns="http://www.w3.org/1999/xhtml";>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>css2.0 VS ie</title>
<style type="text/css">
<!--
body {
font-size: 12px;
text-align: center;
margin: 0px;
padding: 0px;
}
#pic{
  margin:0 auto;
  width:800px;
  padding:0;
  border:1px solid #333;
  }
#pic img{
    max-width:780px;
width:expression(document.body.clientWidth>document.getElementById("pic").scrollWidth*9/10? "780px": "auto" );
border:1px dashed #000;
}
-->
</style>
</head>
<body>
<div id="pic">
<img src="/articleimg/2006/03/3297/koreaad_10020.jpg"/>
</div>
</body>
</html>
```

> 來自我的舊博客.
> 鏈接: <http://blog.163.com/woting402@126/blog/static/56171729200875345194/>