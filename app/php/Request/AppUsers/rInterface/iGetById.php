<?php

  namespace App\Request\AppUsers\rInterface;

  interface iGetById extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getId();
  }