<?php

  namespace App\Request\Stories\rInterface;

  interface iGetByTrackId extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getTrackId();
  }