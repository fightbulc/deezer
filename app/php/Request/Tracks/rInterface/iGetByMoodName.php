<?php

  namespace App\Request\Moods\rInterface;

  interface iGetByTrackId extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getTrackId();
  }