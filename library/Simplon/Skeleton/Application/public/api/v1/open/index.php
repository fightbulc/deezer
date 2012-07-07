<?php

  require(__DIR__ . '/../../../../library/simplon/bootstrap.php');

  $gtw = new App\JsonRpc\V1\Open\Gateway;

  require('api.php');
  $gtw->load($api);