---
title: "Packaging .deb using a simple script: Explained"
date: 2018-05-25 17:58:25
tags:
- Linux Packaging
- Ubuntu
- Debian
---
# TL;DR

This post goes over the creation of a debian package containing only executives using the only the built-in commands and `dpkg` to build packages. I created this article since I have read several articles like [this](https://blog.packagecloud.io/eng/2016/12/15/howto-build-debian-package-containing-simple-shell-scripts/) trying to simplify the process of packaging, but as I encountered the [script used by celebrate](https://github.com/alternize/wabashd-shell/blob/master/make-package), it actually interested me and I managed to simplified the function to minimum, and thus created this script. The script posted belongs to my project [wslu](https://github.com/patrick330602/wslu/blob/master/scripts/builder-deb.sh).

<!--more-->

## the script

```bash make-deb.sh
#!/usr/bin/env bash
BUILD_DIR=`mktemp --tmpdir --directory wslu-build-debian.XXXX`
BUILD_VER=`grep 'version=' ../src/wslu | cut -d'=' -f 2 | xargs`
CURRENT_DIR=`pwd`

mkdir $BUILD_DIR/{DEBIAN/,usr/,usr/bin/}

touch $BUILD_DIR/DEBIAN/control

cat <<EOF >>$BUILD_DIR/DEBIAN/control
Package: wslu
Architecture: all
Maintainer: patrick330602 <wotingwu@live.com>
Depends: bc, wget, unzip, lsb-release
Recommends: git
Suggests: ppa-purge, build-essential
Priority: optional
Version: $BUILD_VER
Description: A collection of utilities for Windows 10 Linux Subsystem
 This is a collection of utilities for Windows 10 Linux Subsystem, such as enabling sound in WSL or creating your favorite linux app shortcuts on Windows 10 Desktop. Requires Windows 10 Creators Update and higher.
EOF

cp ../src/wsl* $BUILD_DIR/usr/bin/

cd $BUILD_DIR
find . -type f ! -regex '.*.hg.*' ! -regex '.*?debian-binary.*' ! -regex '.*?DEBIAN.*' -printf '%P ' | xargs md5sum > DEBIAN/md5sums

find $BUILD_DIR -type d -exec chmod 0755 {} \;
find $BUILD_DIR/usr/bin -type f -exec chmod 0555 {} \;

cd $CURRENT_DIR/../release/debian

sudo dpkg -b $BUILD_DIR/ wslu-${BUILD_VER}.deb

rm -rf $BUILD_DIR
cd $CURRENT_DIR
```

build script by executing the command: `sudo ./make-deb.sh`.

## Explained

### Set variables and create build structure

```bash
BUILD_DIR=`mktemp --tmpdir --directory wslu-build-debian.XXXX`
BUILD_VER=`grep 'version=' ../src/wslu | cut -d'=' -f 2 | xargs`
CURRENT_DIR=`pwd`

mkdir $BUILD_DIR/{DEBIAN/,usr/,usr/bin/}
```

`BUILD_DIR` will create a temp dolder and pass the full path to `BUILD_DIR`.

`BUILD_VER` will get the version number from my script. You can set your own version here.

`CURRENT_DIR` will get the current folder just because I still don't know how `popd` and `pushd` work.(Just... don't blame me here)

`mkdir` command will create the build structure in the temp folder.

### Create `control` file

```bash

touch $BUILD_DIR/DEBIAN/control

cat <<EOF >>$BUILD_DIR/DEBIAN/control
Package: wslu
Architecture: all
Maintainer: patrick330602 <wotingwu@live.com>
Depends: bc, wget, unzip, lsb-release
Recommends: git
Suggests: ppa-purge, build-essential
Priority: optional
Version: $BUILD_VER
Description: A collection of utilities for Windows 10 Linux Subsystem
 This is a collection of utilities for Windows 10 Linux Subsystem, such as enabling sound in WSL or creating your favorite linux app shortcuts on Windows 10 Desktop. Requires Windows 10 Creators Update and higher.
EOF
```

these lines are creating `control` file. Actually, you can ignore the first `touch` line, as my bash have a problem making complaints that file doesn't exist. for the content of `control`, you can check [the offical Debian packing guide](https://www.debian.org/doc/manuals/maint-guide/dreq.en.html#control) to complete this part.

### copying executables and creating `md5sums` file

```bash
cp ../src/wsl* $BUILD_DIR/usr/bin/

cd $BUILD_DIR
find . -type f ! -regex '.*.hg.*' ! -regex '.*?debian-binary.*' ! -regex '.*?DEBIAN.*' -printf '%P ' | xargs md5sum > DEBIAN/md5sums
```

the `cp` command is obiviously copying the executables to `$BUILD_DIR/usr/bin/`.

the next two line will enter the build folder and output their `md5sum` to `DEBIAN/md5sums`.

### Change permission

```bash
find $BUILD_DIR -type d -exec chmod 0755 {} \;
find $BUILD_DIR/usr/bin -type f -exec chmod 0555 {} \;
```

This is to change all folder to permission `755` and the executables to permission `555`.

### Build and cleanup

```bash
cd $CURRENT_DIR/../release/debian

sudo dpkg -b $BUILD_DIR/ wslu-${BUILD_VER}.deb

rm -rf $BUILD_DIR
cd $CURRENT_DIR
```

This is also pretty obivious. It will go to a desired output location, build the package, remove the temp build folder, and go back to original location.
