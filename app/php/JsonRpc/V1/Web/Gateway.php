<?php

  namespace App\JsonRpc\V1\Web;

  class Gateway extends \Simplon\Gateway
  {
    public function __construct()
    {
      $this->namespace = __NAMESPACE__;
    }
  }