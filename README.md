PATCH
======

This is the working repo for our WordPress.com theme Patch.

Getting Started
--------

First some prep work:

1. Make local clones of both this Git repo and the SVN repo, into separate directories (if you haven't got them already - make sure they are up to date though):
```
git clone  https://github.com/pixelgrade/patch.git patch-git
svn co https://ext-premium-themes.svn.automattic.com/patch patch-svn
```
2. Copy all the contents of the SVN directory (including the all important `.svn` hidden folder) **into** the Git directory:
`cp -R patch-svn/ patch-git/`
3. Make sure that you ignore the .svn directory on Git (add a new line into `patch-git/.gitignore` containing `.svn`)
4. You can now delete the previously cloned SVN repo as we now have a 2-in-1 directory that is both a Git repo and SVN repo:
`rm -Rf patch-svn`
5. Test that everything is working accordingly (everything should be up-to-date):
```
cd patch-git
git status
svn status
```

Now that you have a working directory, **all work** will be done on Git/Github. The SVN side of things will **only** be used to make genuine commits to WordPress.com to be reviewed and hopefully accepted by the theme wranglers.

Workflow
-------

So let me walk you through the workflow we have envisioned for the hybrid Git - SVN:
- All work will be done on branches (no code edits and/or commits to the master *patch* branch)
- Each branch will represent a separate, independent feature and/or fix/improvement
- More then one can work at once on a branch
- Once all the people working on a branch agree that it is finished, a *pull request* will be made to be merged with the *master*
- The pull request message will feature all the work that has been done (this message will be used as the SVN commit message)
- Preferably only a few people will handle pull requests
- The one that handles a pull request will do a code review and accept or reject it as further work is needed
- The one that accepts a pull request will **do the commit to the SVN repo** and **delete the branch** (use `git pull --prune` locally to sync your local branches with the one deleted on GitHub)

That's it. Simple right?!