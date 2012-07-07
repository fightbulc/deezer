<?php

  namespace App\Request\Memories\rInterface;

  interface iGetByUserId extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getUserId();
  }