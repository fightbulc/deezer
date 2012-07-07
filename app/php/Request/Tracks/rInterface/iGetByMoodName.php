<?php

  namespace App\Request\Tracks\rInterface;

  interface iGetByMoodName extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getMoodName();
  }