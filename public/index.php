<?php

  session_start();

  // app settings
  require(__DIR__ . '/../app/config/frontend.config.php');

// ############################################

  // app maintenance mode
  $app['maintenance']['enabled'] = FALSE;
  $app['maintenance']['password'] = 'coffeeMirrorTesting';
  $app['maintenance']['validIps'] = array();

  if (array_key_exists('code', $_GET) && $_GET['code'] == $app['maintenance']['password'])
  {
    $_SESSION['maintenance_pass'] = 1;
    header('Location: ' . $app['url']['domain']);
    exit;
  }

  if (array_key_exists('maintenance_pass', $_SESSION))
  {
    $app['maintenance']['enabled'] = FALSE;
  }

// ############################################

  // handle crawler requests
  require(__DIR__ . '/page/crawler.metas.php');
  require(__DIR__ . '/page/crawler.php');

// ############################################

  // load template/assets
  require(__DIR__ . '/page/loader.php');

  // load app or maintenance
  echo loadApp();