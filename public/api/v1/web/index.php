<?php

  session_start();

  require(__DIR__ . '/../../../../library/Simplon/Bootstrap.php');
  require('api.php');

  $gtw = new App\JsonRpc\V1\Web\Gateway;
  $gtw->load($api);