<?php

  namespace App\Request\Artists\rInterface;

  interface iGetTracksDataByUrlName extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getUrlName();
  }