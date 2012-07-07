<?php

  namespace App\Request\Memories\rInterface;

  interface iGetByTrackId extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getTrackId();
  }