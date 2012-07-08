<?php

  namespace App\Request\Stories\rInterface;

  interface iGetById extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getId();
  }