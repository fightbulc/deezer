<?php

  namespace App\Request\Tracks\rInterface;

  interface iGetByUserId extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getUserId();
  }