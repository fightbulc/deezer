<?php

  namespace App\JsonRpc\V1\Open;

  class Gateway extends \Simplon\Gateway
  {
    public function __construct()
    {
      $this->namespace = __NAMESPACE__;
    }
  }