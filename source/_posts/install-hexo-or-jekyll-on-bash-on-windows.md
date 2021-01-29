---
title: Install Hexo and Jekyll on Bash On Windows 10
date: 2016-08-03 11:54:28
tags:
- WSL
- Hexo
- Jekyll
---

Recently, I try to migrate my Hexo and Jekyll system to Bash On Windows, and find it hard to be done as I have expected. 

<!--more-->

I therefore made two SH script for Bash for easy Installation:

[jekyll.sh]( https://raw.githubusercontent.com/patrick330602/jekyll-wsl-installer/master/jekyll.sh ) [hexo.sh]( https://raw.githubusercontent.com/patrick330602/hexo-wsl-installer/master/hexo.sh )

**Update:**

I made a even easier way to install:

To Install Hexo, run:	
`bash <(wget -qO- https://raw.githubusercontent.com/patrick330602/hexo-wsl-installer/master/hexo.sh)`

To Install Jekyll, run:	
`bash <(wget -qO- https://raw.githubusercontent.com/patrick330602/jekyll-wsl-installer/master/jekyll.sh)`

![Script Running](https://cdn.patrickwu.space/posts/dev/haj.png)



But if you want to install by yourself, you can try the following steps.

## Steps to install the latest version of the Jekyll static site/blog generator on Ubuntu on Windows

### Steps

**Step 0: Add the brightbox repository**
`sudo apt-add-repository ppa:brightbox/ruby-ng`
`sudo apt-get update`
**Step 1: Install ruby 2.3 and -dev package**
`sudo apt-get install ruby2.3 ruby2.3-dev`
Verify the install by running `ruby -v`
You should get something similar to
*ruby 2.3.1p112 (2016-04-26 revision 54768)*
**Step 2: update ruby gems**
`sudo gem update --system`
**Step 3: install build-essential**
`sudo apt-get install build-essential --no-install-recommends`
**Step 4: install jekyll itself**
`sudo gem install jekyll`
Verify the install by running `jekyll -v`
You should get something similar to
*jekyll 3.1.6*

### Bonus steps

* If you’re using pagination:

  `sudo gem install jekyll-paginate`

* To save yourself some typing add the following to your .bashrc

  `alias jek='jekyll serve --force_polling --incremental'`

That’s all - happy blogging!

### Anti-steps

* Don’t `apt-get install jekyll` - it’s version 0.11.2 from 2011, while the latest gem is 3.1.6 as of May 2016.
* Don’t `apt-get install ruby` - it’s version 1.9 and Jekyll 3 requires ruby >= 2.0.
* Don’t run jekyll with –watch - inotify is not (yet) working properly. 

### Credits

<https://www.brightbox.com/blog/2016/01/06/ruby-2-3-ubuntu-packages/>
<https://talk.jekyllrb.com/t/error-failed-to-build-gem-native-extension/997>
<https://github.com/Microsoft/BashOnWindows/issues/433>

Retrieve from:<http://biserkov.com/blog/2016/06/04/Steps-to-install-Jekyll-on-Ubuntu-on-Windows/>

## Steps to install the latest version of Hexo on Ubuntu on Windows

### Steps
**Step 1: Install node.js and npm from apt-get**
`sudo apt-get install nodejs npm`
**Step 2:Create a symbolic link for node**
`sudo ln -s /usr/bin/nodejs /usr/bin/node`
**Step 3:Update Node Package Manager**
`sudo npm install npm -g`
*Note: You might need to run the code twice*
**Step 4:Install Module n**
`sudo npm install n -g`
**Step 5:Update Node.js using module n**
`sudo n stable`
**Step 6:Finally, you can install Hexo without problem**
`sudo npm install -g hexo-cli`

### Anti-steps

Don't run hexo server or run hexo  with -watch - inotify is not (yet) working properly.

### Credits

<http://stackoverflow.com/questions/10075990/upgrading-node-js-to-latest-version>
<https://docs.npmjs.com/getting-started/installing-node>