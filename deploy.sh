#!/bin/bash

EXCLUDE='./deploy-exclude.txt'
TARGET='deezer@deezer.efides.com:/home/deezer/www'

if [ ! -f $EXCLUDE ] ; then
  echo "";
  echo "-- FAIL --";
  echo "make sure you are in the right directory and 'deploy-exclude.txt' is there!";
  echo "";
  echo "chdir to top of current project path!";
  echo "";
  exit;
fi

echo "";
echo "-------------- changes below --------------";
rsync -rauin --exclude-from=$EXCLUDE . $TARGET | grep '<'

echo "-------------- changes above --------------";
echo "";
echo "if the changes look good, you can do the rsync by copy and paste it here(!) into your terminal.";
echo "";
echo "rsync -raui --exclude-from=$EXCLUDE . $TARGET"
echo "";
