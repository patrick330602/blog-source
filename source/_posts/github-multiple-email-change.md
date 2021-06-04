---
title: How to change multiple emails in Your Repos
date: 2016-07-13 11:08:56
tags:
- GitHub
- Shell
---
 Recently, I am programming an app on GitHub (Yes, it is WeCode as you have guessed), but I suddenly realized the commits are not properly shown in my dashboard:
<!--more-->

![GitHub Dashboard](https://cdn.patrickwu.space/posts/dev/github-bug.PNG)

 As a GitHuber, I hate that not all my commits are not shown. As I browse the Git History in Visual Studio, I find that Microsoft changed my email address:

![Well Done, Microsoft](https://cdn.patrickwu.space/posts/dev/well-done-microsoft.png)

 Well done, Microsoft.

 GitHub actually provides you with a feature to [change author info ](https://help.github.com/articles/changing-author-info/). However, I found it misleading and confusing to the newbies, while only support only one email address. So I decided to write a new instruction for newbies for better understanding.

## Changing author info

To change the name and/or email address recorded in existing commits, you must rewrite the entire history of your Git repository.

> This action is destructive to your repository's history. If you're collaborating on a repository with others, it's considered bad practice to rewrite published history. You should only do this in an emergency.

### Changing the Git history of your repository using a script
We've created a script that will change any commits that previously had the old email address in its author or committer fields to use the correct name and email address.

> Running this script rewrites history for all repository collaborators. After completing these steps, any person with forks or clones must fetch the rewritten history and rebase any local changes into the rewritten history.

Before running this script, you'll need:

- The old email address that appears in the author/committer fields that you want to change
- The correct name and email address that you would like such commits to be attributed to

### Instruction:
1. Open Git Bash.

2. Create a fresh, bare clone of your repository:
   ```shell
   git clone --bare https://github.com/*user*/*repo*.git
   cd *repo*.git
   ```

3. Copy and paste the script in a text editor, replacing the following variables based on the information you gathered:
   - `OLD_EMAIL`
   - `CORRECT_NAME`
   - `CORRECT_EMAIL`

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

4. Save the script in the repository folder just created as **git-author-rewrite.sh**.

5. In Git Bash, run following code:
   ```shell
   sh git-author-rewrite.sh
   ```

6. Review the new Git history for errors.

7. Push the corrected history to GitHub:
   ```shell
   git push --force --tags origin 'refs/heads/*'
   ```

8. Clean up the temporary clone:
   ```shell
   cd ..
   rm -rf *repo*.git
   ```
