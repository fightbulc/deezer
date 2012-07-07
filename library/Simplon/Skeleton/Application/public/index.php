<?php

  session_start();

  // app settings
  require(__DIR__ . '/../app/config/frontend.config.php');

  // ############################################

  // app maintenance mode
  $app['maintenance']['enabled'] = FALSE;
  $app['maintenance']['validIps'] = array();

  // ############################################

  // handle crawler requests
  require(__DIR__ . '/_init/crawler.metas.php');
  require(__DIR__ . '/_init/crawler.php');

  // ############################################

  // load template/assets
  require(__DIR__ . '/_init/loader.php');

  // load app or maintenance
  echo loadApp();