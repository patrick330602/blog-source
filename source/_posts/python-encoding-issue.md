---
title: Encoding problem in Python and how to solve it
date: 2017-11-09 12:20:45
tags:
- Python
---
I recently meet a bug that really annoys me when trying to make a web spider. The issue is that the received html data is returned value is incorrectly processed. One example is the non-ascii right-single quotation mark'’ '. The expected value should be something like this:

`{"description": "The New Topographic photographers acknowledged people\u2019s interaction with...`

However, I got this:

`{"description": "The New Topographic photographers acknowledged people\u00e2\u0080\u0099s interaction with...`
<!--more-->
This is because Python parse string data into byte arrays, for example, **People’ s** will be converted to a bytestring of `['\u0070', '\u0065', '\u006f', '\u0070', \u006c', '\u0065', '\u00e2\u0080\u0099', '\u0073']`. When people try to read the bytestring as string, Python will not consider `\u00e2\u0080\u0099` as a single character but three seperate character. 

To Solve this problem, we need to identify "UTF-8-like" Unicode sequences and process them into the correct Unicode character using regular expressions:

```python
import re

'''python2'''
output_string = re.sub(ur'[\xc2-\xf4][\x80-\xbf]+',lambda m: m.group(0).encode('latin1').decode('utf8'),input_string)

'''python3'''
output_string = re.sub(r'[\xc2-\xf4][\x80-\xbf]+',lambda m: m.group(0).encode('latin1').decode('utf8'),input_string)
```