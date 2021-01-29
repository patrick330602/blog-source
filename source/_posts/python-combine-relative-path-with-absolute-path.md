---
title: Combine relative path with absolute path in Python
date: 2017-08-12 17:18:08
tags:
- Python
---

Recently I need to combine a absolute link with a relative link, so I tried to hard code it but failed. After checking online, there is actually a lib in Python called `urlparse` that have a function to combine this kind of url. the use is also simple:

```python
from urlparse import urljoin
urljoin('http://mysite.com/foo/bar/x.html', '../../images/img.png')
#'http://mysite.com/images/img.png'
```