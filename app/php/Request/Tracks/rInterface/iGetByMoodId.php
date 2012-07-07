<?php

  namespace App\Request\Tracks\rInterface;

  interface iGetByMoodId extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getMoodId();
  }