---
title: Lazy Way of making a Simple C++ Project
date: 2017-04-17 22:04:02
tags:
  - 'C++'
  - Makefile
---

Sometimes, when I try to do a lab project, I have to copy the makefile from each previous project to make my life easier and more lazy. However, I think I should be more lazy-so what I do is modified my Makefile a little bit, so that it can be easier, that I don't really need to modify the code any more, I just need to copy makefile and build my project ;)
<!--more-->
Here is the code for my makefile, it is actually pretty simple:

```makefile
TARGET=$(shell basename $(PWD))
create:
        g++ -g -o $(TARGET) *.cpp *.h

clean:
        /bin/rm -f $(TARGET)
        clear
```

![](https://cdn.patrickwu.space/posts/dev/cpp-makefile-lazy.png)