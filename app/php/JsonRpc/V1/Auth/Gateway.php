<?php

  namespace App\JsonRpc\V1\Auth;

  class Gateway extends \Simplon\Gateway
  {
    public function __construct()
    {
      $this->namespace = __NAMESPACE__;
    }
  }