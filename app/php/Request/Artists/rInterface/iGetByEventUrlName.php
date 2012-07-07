<?php

  namespace App\Request\Artists\rInterface;

  interface iGetByEventUrlName extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getEventUrlName();
  }