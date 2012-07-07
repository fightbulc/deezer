<?php

  namespace App\Request\Moods\rInterface;

  interface iGetByUserId extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getUserId();
  }