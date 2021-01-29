---
title: "Format drive larger than 2TiB in Alicloud"
date: 2019-08-28 11:02:12
tags:
- Linux
- Server
- Ubuntu
---
# TL;DR

This is a quick reference for formatting Alicloud cloud drive that is higher than 2 TiB because the original guide is messy.

## Steps

1. Check `parted` and `e2fsprogs` are both installed.
2. Usually cloud drive is automatically mounted at `/dev/vdb`, `/dev/vdc`, etc. check using `fdisk -l`.
3. Run `parted /dev/vdx`.
4. In new prompt, run `mklabel gpt`.
5. Create partition.
   - For **ext4**: `mkpart primary ext4 0 -1`.
   - For **xfs**: `mkpart primary xfs 0 -1`.
6. `print` to check partition then exit by `exit`.
7. Run `partprobe` to make system re-read the partition table.
8. Set property to partion.
   - For **ext4**: `mke2fs -O 64bit,has_journal,extents,huge_file,flex_bg,uninit_bg,dir_nlink,extra_isize /dev/vdb1`.
   - For **xfs**: `mkfs -t xfs /dev/vdb1`.
9.  `mkdir /storage && mount /dev/vdb1 /storage` to mount at `/storage`.
10. `umount /storage` to un-mount.

## Startup Setup

1. Backup fstab by `cp /etc/fstab /etc/fstab.bak`.
2. `echo /dev/vdb1 /storage ext4 defaults 0 0 >> /etc/fstab` for ext4 and `echo /dev/vdb1 /storage xfs defaults 0 0 >> /etc/fstab` for xfs.
3. Verify by `cat /etc/fstab`.