<?php

  namespace App\Request\Stories\rInterface;

  interface iCreateStory extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getTrackId();
    public function getStory();
  }