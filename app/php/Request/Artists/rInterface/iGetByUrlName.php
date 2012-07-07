<?php

  namespace App\Request\Artists\rInterface;

  interface iGetByUrlName extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getArtistUrlName();
  }