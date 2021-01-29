---
title: 'Packaging .rpm using a simple script: Explained'
tags:
	- Linux Packaging
  - OpenSUSE
date: 2018-05-26 09:41:35
---

# TL;DR

This post goes over the creation of a rpm package containing only executives using the only the built-in commands and `rpmbuild` to build packages. I created this article since I have read several articles like [this](https://stackoverflow.com/questions/880227/what-is-the-minimum-i-have-to-do-to-create-an-rpm-file) and [this](https://eureka.ykyuen.info/2009/12/28/opensuse-build-a-rpm-package/) trying to simplify the process of packaging, but as I created [my own script to create debian package](https://www.patrickwu.ml/2018/05/25/make-deb-packing-simple/), this inspired me to create one using this. The script posted belongs to my project [wslu](https://github.com/patrick330602/wslu/blob/master/scripts/builder-rpm.sh).

<!--more-->

## the script

```bash make-rpm.sh
#!/usr/bin/env bash
BUILD_VER=`grep 'version=' ../src/wslu | cut -d'=' -f 2 | xargs`
CURRENT_DIR=`pwd`

mkdir -p ~/rpm_wslu/{BUILD/,RPMS/,SOURCES/,SPECS/,SRPMS/}


mkdir ~/rpm_wslu/SOURCES/wslu-$BUILD_VER
cp ../src/wsl* ~/rpm_wslu/SOURCES/wslu-$BUILD_VER
cd ~/rpm_wslu/SOURCES
tar -czvf wslu-${BUILD_VER}.tar.gz wslu-$BUILD_VER
rm -rf tmp

cat <<EOF >> ~/rpm_wslu/SPECS/wslu-$BUILD_VER.spec
%define packager       patrick330602 <wotingwu@live.com>

%define _topdir        $HOME/rpm_wslu
%define _tmppath       /var/tmp
 
%define _rpmtopdir     %{_topdir}
%define _builddir      %{_rpmtopdir}/BUILD
%define _rpmdir        %{_rpmtopdir}/RPMS
%define _sourcedir     %{_rpmtopdir}/SOURCES
%define _specdir       %{_rpmtopdir}/SPECS
%define _srcrpmdir     %{_rpmtopdir}/SRPMS
Summary: Windows 10 Linux Subsystem Utilities
Name: wslu
Version: $BUILD_VER
Release: 1
Source: wslu-$BUILD_VER.tar.gz
Requires: bc lsb-release hostname wget unzip
URL: https://github.com/patrick330602/wslu/
License: GPL
%description
This is a collection of utilities for Windows 10 Linux Subsystem, such as enabling sound in WSL or creating your favorite linux app shortcuts on Windows 10 Desktop. Requires Windows 10 Creators Update and higher.
%prep
%setup
%build

%install
rm -rf \$RPM_BUILD_ROOT
mkdir -p \${RPM_BUILD_ROOT}/usr/bin
install -m 755 wsl* \${RPM_BUILD_ROOT}%{_bindir}
%clean
rm -rf \$RPM_BUILD_ROOT
%files
%defattr(-,root,root)
%attr(755,root,root) %{_bindir}/wslu
%attr(755,root,root) %{_bindir}/wslusc
%attr(755,root,root) %{_bindir}/wslfetch
%attr(755,root,root) %{_bindir}/wslpkg
%attr(755,root,root) %{_bindir}/wslsys
%attr(755,root,root) %{_bindir}/wslupath
%changelog
* Fri May 11 2018 patrick330602 <wotingwu@live.com>
- First rpm build of wslu.
EOF

cd ~/rpm_wslu/SPECS
sudo rpmbuild -ba wslu-$BUILD_VER.spec

cp ~/rpm_wslu/RPMS/x86_64/*.rpm $CURRENT_DIR/../release/rpm/
cp ~/rpm_wslu/SRPMS/*.rpm $CURRENT_DIR/../release/rpm/
sudo rm -rf ~/rpm_wslu/
cd $CURRENT_DIR
```

build script by executing the command: `sudo ./make-rpm.sh`.

## Explained

### Set variables and create build structure

```bash
BUILD_VER=`grep 'version=' ../src/wslu | cut -d'=' -f 2 | xargs`
CURRENT_DIR=`pwd`

mkdir -p ~/rpm_wslu/{BUILD/,RPMS/,SOURCES/,SPECS/,SRPMS/}
```
`BUILD_VER` will get the version number from my script. You can set your own version here.

`CURRENT_DIR` will get the current folder just because I still don't know how `popd` and `pushd` work.(Just... don't blame me here)

unlike building deb packages, we need a certain folder structures under your home folder. the `mkdir` command is doing that thing.

### Copy executables and create tarball for the package

```bash
mkdir ~/rpm_wslu/SOURCES/wslu-$BUILD_VER
cp ../src/wsl* ~/rpm_wslu/SOURCES/wslu-$BUILD_VER
cd ~/rpm_wslu/SOURCES
tar -czvf wslu-${BUILD_VER}.tar.gz wslu-$BUILD_VER
```

The first `mkdir` will tries to create a folder with app name and verion using the style of `name-version` under `SOURCES` folder.

Then, it will copy the executables under the folder and create a taarball using `tar` command.

### creating .spec file

```bash
cat <<EOF >> ~/rpm_wslu/SPECS/wslu-$BUILD_VER.spec
%define packager       patrick330602 <wotingwu@live.com>

%define _topdir        $HOME/rpm_wslu
%define _tmppath       /var/tmp
 
%define _rpmtopdir     %{_topdir}
%define _builddir      %{_rpmtopdir}/BUILD
%define _rpmdir        %{_rpmtopdir}/RPMS
%define _sourcedir     %{_rpmtopdir}/SOURCES
%define _specdir       %{_rpmtopdir}/SPECS
%define _srcrpmdir     %{_rpmtopdir}/SRPMS
Summary: Windows 10 Linux Subsystem Utilities
Name: wslu
Version: $BUILD_VER
Release: 1
Source: wslu-$BUILD_VER.tar.gz
Requires: bc lsb-release hostname wget unzip
URL: https://github.com/patrick330602/wslu/
License: GPL
%description
This is a collection of utilities for Windows 10 Linux Subsystem, such as enabling sound in WSL or creating your favorite linux app shortcuts on Windows 10 Desktop. Requires Windows 10 Creators Update and higher.
%prep
%setup
%build

%install
rm -rf \$RPM_BUILD_ROOT
mkdir -p \${RPM_BUILD_ROOT}/usr/bin
install -m 755 wsl* \${RPM_BUILD_ROOT}%{_bindir}
%clean
rm -rf \$RPM_BUILD_ROOT
%files
%defattr(-,root,root)
%attr(755,root,root) %{_bindir}/wslu
%attr(755,root,root) %{_bindir}/wslusc
%attr(755,root,root) %{_bindir}/wslfetch
%attr(755,root,root) %{_bindir}/wslpkg
%attr(755,root,root) %{_bindir}/wslsys
%attr(755,root,root) %{_bindir}/wslupath
%changelog
* Fri May 11 2018 patrick330602 <wotingwu@live.com>
- First rpm build of wslu.
EOF
```

This is actually a lot different from most of the .spec files online so be very careful in this part, as I combined several files into one.

This .spec file have two parts: **definition** and **specification**.

**Definition** is the `%define` part, as they are used for define the temp build folder location and the information of yours.

**Specification** is the real build part, and for this part I suggest you to read the [Fedora Quick Docs](https://docs.fedoraproject.org/quick-docs/en-US/creating-rpm-packages.html#con_rpm-spec-file-overview). There is just one more thing to be specified: `$` and `\\$` is different. `$` will pass the variable from the script to the .spec file, but if you want to use something like `$RPM_BUILD_ROOT`, please make sure you use `\\$` instead.


## Build and copy the rpm packages

```bash
cd ~/rpm_wslu/SPECS
sudo rpmbuild -ba wslu-$BUILD_VER.spec

cp ~/rpm_wslu/RPMS/x86_64/*.rpm $CURRENT_DIR/../release/rpm/
cp ~/rpm_wslu/SRPMS/*.rpm $CURRENT_DIR/../release/rpm/
sudo rm -rf ~/rpm_wslu/
cd $CURRENT_DIR
```

This is pretty obivious. you build and copy the rpm packages and copy them to the desired destination. Just one more thing to be noticed: there is two version of packages: binary packages under `SRPMS` and normal packages under `RPMS`.