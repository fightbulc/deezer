<?php

  require(__DIR__ . '/../../../../library/Simplon/Bootstrap.php');
  require('api.php');

  $gtw = new App\JsonRpc\V1\Mobile\Gateway;
  $gtw->load($api);