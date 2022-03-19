---
title: 如何批量更改commit的郵箱
date: 2016-07-13 11:08:56
tags:
- GitHub
- Shell
lang: zh
---
最近，我在編寫一款掛載在GitHub的程序，但我突然發現我的Git狀態好像不太對。。。
<!--more-->

![GitHub狀態](https://cdn.patrickwu.space/posts/dev/github-bug.PNG)

 作為一個GitHub上的碼農，這種事很頭疼啊！不過我看了下Visual Studio裏的Git歷史，我才發現這是微軟的鍋： 

![微軟：怎麼又是我的鍋](https://cdn.patrickwu.space/posts/dev/well-done-microsoft.png)

 厲害了我的軟。

 還好，GitHub 提供了一個叫[修改作者信息](https://help.github.com/articles/changing-author-info/)的功能。但是，這段文字看完後，萌新們可能會一臉蒙逼。而且，這個功能只支持一個郵箱。所以説呢，我就在這兒重寫了指引。

## 修改作者信息

為了修改已有的commit作者信息，你需要重寫你的Git Repo的全部歷史。

> 這一動作將會對你的Repo歷史造成毀滅性的修改。如果你與多人協作，這會並不是一個恰當的行為。只有在必須時才進行修改。

### 使用Script修改Git Repo的全部歷史
我們創建了一個Script以助於修改舊的作者信息至新的作者信息。

> 運行這段代碼會重寫所有歷史。完成後，其他協作的人必須fetch新的Repo以寫入數據。 

在運行前，你需要：

- 準備修改的目標郵箱
- 正確的作者信息

### 步驟:
1. 打開Git Bash.

2. 創建一個全新的bare clone:
   ```shell
   git clone --bare https://github.com/*user*/*repo*.git
   cd *repo*.git
   ```

3. 複製以下的代碼到一個編輯器，並修改以下常量：
   - `OLD_EMAIL`準備修改的目標郵箱
   - `CORRECT_NAME`修改後的名字
   - `CORRECT_EMAIL`修改後的郵箱

   ```sh
   #!/bin/sh
   # see https://help.github.com/articles/changing-author-info/

   git filter-branch --env-filter '

   OLD_EMAIL=(
       "your-old-email@example.com"
       "another-old-email@example.com"
       "your-git-email@users.noreply.github.com"
   )
   CORRECT_NAME="Your Correct Name"
   CORRECT_EMAIL="your-correct-email@example.com"

   for NEW_EMAIL in ${OLD_EMAIL[@]}; do
       if [ "$GIT_COMMITTER_EMAIL" == "$NEW_EMAIL" ]; then
           export GIT_COMMITTER_NAME="$CORRECT_NAME"
           export GIT_COMMITTER_EMAIL="$CORRECT_EMAIL"
       fi
       if [ "$GIT_AUTHOR_EMAIL" == "$NEW_EMAIL" ]; then
           export GIT_AUTHOR_NAME="$CORRECT_NAME"
           export GIT_AUTHOR_EMAIL="$CORRECT_EMAIL"
       fi
   done
   ' --tag-name-filter cat -- --branches --tags
   ```

4. 在剛剛克隆的文件夾裏保存為**git-author-rewrite.sh**。

5. 在Git Bash內運行以下代碼：
   ```shell
   sh git-author-rewrite.sh
   ```

6. 常看新的Git歷史是否有錯誤。

7. 推送修改後的歷史到GitHub：
   ```shell
   git push --force --tags origin 'refs/heads/*'
   ```

8. 移除這一臨時Clone：
   ```shell
   cd ..
   rm -rf *repo*.git
   ```