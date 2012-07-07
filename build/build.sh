#!/bin/bash

clear

echo ""
echo ""
echo "R.JS - PROJECT MINIFICATION/OPTIMIZATION"
echo "----------------------------------------"
echo ""
echo ""

#
# Variables
#
OPTFILE="optimized.js"
BUNDLESFOLDER="../public/bundles"

CLOSURE_OPT_LEVEL="SIMPLE_OPTIMIZATIONS"
JS_BOOTSTRAP_FILE_RAW="$OPTFILE/assets/app/js/bootstrap.js"
JS_BOOTSTRAP_FILE_MIN="$OPTFILE/assets/app/js/bootstrap.min.js"
CSS_BOOTSTRAP_FILE_RAW="$OPTFILE/assets/app/css/bootstrap.css"
CSS_BOOTSTRAP_FILE_MIN="$OPTFILE/assets/app/css/bootstrap.min.css"

echo "REMOVING OLD FILES..."

#
# Delete old data
#
if [ -e "$OPTFILE" ]; then
  rm $OPTFILE
  echo ""
fi

if [ -d "$BUNDLESFOLDER" ]; then
  rm -R $BUNDLESFOLDER
fi

#
# Build optimized version
#
echo "MINIFYING PROJECT..."
echo ""
node lib/r.js -o app.build.js
echo ""

#
# Inject CDN URL
#
echo "FINALISE BUILD PROCESS..."
echo ""
php lib/finalise.php

#
# Delete old optimized data
#
if [ -e "$OPTFILE" ]; then
  rm $OPTFILE
fi

#
# Optimize JS with google closure
#
# echo "RUNNING CLOSURE FOR MIN.JS..."
# echo ""
# echo "java -jar lib/closure-compiler.jar --js $JS_BOOTSTRAP_FILE_RAW --compilation_level $CLOSURE_OPT_LEVEL --warning_level QUIET > $JS_BOOTSTRAP_FILE_MIN"
# echo ""

# #
# # Optimize CSS with YUI compressor
# #
# echo "RUNNING YUI COMPRESSOR FOR MIN.CSS..."
# echo ""
# echo "java -jar lib/yuicompressor-2.4.7.jar --type css $CSS_BOOTSTRAP_FILE_RAW -o $CSS_BOOTSTRAP_FILE_MIN"
# echo ""

#
# END
#
echo "Thats all folks!"
echo ""
echo ""