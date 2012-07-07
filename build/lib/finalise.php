<?php

  require '../app/config/common.config.php';

  $BUNDLES_FOLDER = '../public/bundles';
  $JS_BOOTSTRAP_FILE = getFile("./optimized.js");
  $CSS_BOOTSTRAP_FILE = getFile("../public/assets/app/css/bootstrap.css");
  $HTML_BOOTSTRAP_FILE = getFile("../public/page/app.html");
  $addToMd5Hash = time();
  $minifyCss = TRUE;
  $minifyJs = FALSE;

#
# INJECT CDN
#
  echo "INJECT CDN...\n\n";

  foreach (array('production') as $ENV)
  {
    echo "Environment: " . strtoupper($ENV) . "\n";
    echo "---------------------------\n\n";

    #
    # Create bundles folders
    #
    $path = $BUNDLES_FOLDER;
    if (!file_exists($path))
    {
      mkdir($path);
    }

    $path = $BUNDLES_FOLDER . '/' . $ENV;
    if (!file_exists($path))
    {
      mkdir($path);
    }

    #
    # JS
    #
    echo "INJECTION INTO JS...\n";
    $fileContent = $JS_BOOTSTRAP_FILE;

    if (!empty($app[$ENV]['url']['cdn']))
    {
      echo "-> SET CDN IN JS\n";
      $fileContent = str_replace('/assets', $app[$ENV]['url']['cdn'], $fileContent);
    }

    // create md5 file name
    $md5 = md5($fileContent) . $addToMd5Hash;
    $jsBuildFile = 'application-' . $md5 . '.js';
    $jsBuildFileMin = str_replace('.js', '.min.js', $jsBuildFile);
    $filePath = $BUNDLES_FOLDER . '/' . $ENV . '/' . $jsBuildFile;

    // write file
    $fh = fopen($filePath, 'w');
    fwrite($fh, $fileContent);
    fclose($fh);

    // minify
    $filePathMin = str_replace('.js', '.min.js', $filePath);

    if ($minifyJs)
    {
      echo "-> Minifying...";
      $optLevel = 'SIMPLE_OPTIMIZATIONS';
      $exec = 'java -jar ' . __DIR__ . '/closure-compiler.jar --js ' . $filePath . ' --compilation_level ' . $optLevel . ' --warning_level QUIET > ' . $filePathMin;
      exec($exec);
    }
    else
    {
      exec('cp ' . $filePath . ' ' . $filePathMin);
    }

    echo "DONE ($filePathMin)\n\n";

    #
    # CSS
    #
    echo "INJECTION INTO CSS...\n";
    $fileContent = $CSS_BOOTSTRAP_FILE;

    if (!empty($app[$ENV]['url']['cdn']))
    {
      echo "-> SET CDN IN CSS\n";
      $fileContent = str_replace('/assets', $app[$ENV]['url']['cdn'], $fileContent);
    }

    // create md5 file name
    $md5 = md5($fileContent) . $addToMd5Hash;
    $cssBuildFile = 'application-' . $md5 . '.css';
    $cssBuildFileMin = str_replace('.css', '.min.css', $cssBuildFile);
    $filePath = $BUNDLES_FOLDER . '/' . $ENV . '/' . $cssBuildFile;

    // write file
    $fh = fopen($filePath, 'w');
    fwrite($fh, $fileContent);
    fclose($fh);

    // minify
    $filePathMin = str_replace('.css', '.min.css', $filePath);

    if ($minifyCss)
    {
      echo "-> Minifying...";
      $exec = 'java -jar ' . __DIR__ . '/yuicompressor-2.4.7.jar --type css ' . $filePath . ' -o ' . $filePathMin;
      exec($exec);
    }

    echo "DONE ($filePathMin)\n\n";

    #
    # SET FILES IN APP.HTML
    #
    echo "SET CORRECT JS/CSS PATHS...\n";
    $fileContent = $HTML_BOOTSTRAP_FILE;
    $fileContent = str_replace('/assets/vendor/requirejs/require.js', '/bundles/' . $ENV . '/' . $jsBuildFile, $fileContent);
    $fileContent = str_replace('/assets/app/css/bootstrap.css', '/bundles/' . $ENV . '/' . $cssBuildFileMin, $fileContent);

    if (!empty($app[$ENV]['url']['cdn']))
    {
      echo "-> SET CDN IN HTML\n";
      $fileContent = str_replace('/assets', $app[$ENV]['url']['cdn'], $fileContent);
    }

    $filePath = $BUNDLES_FOLDER . '/' . $ENV . '/app.html';
    $fh = fopen($filePath, 'w');
    fwrite($fh, $fileContent);
    fclose($fh);

    echo "-> DONE\n\n";
  }

#
# Helper
#
  function getFile($path)
  {
    return trim(join('', file($path)));
  }