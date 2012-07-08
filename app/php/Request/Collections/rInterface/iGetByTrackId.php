<?php

  namespace App\Request\Collections\rInterface;

  interface iGetByTrackId extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getTrackId();
  }