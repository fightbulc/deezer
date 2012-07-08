<?php

  namespace App\Request\Tracks\rInterface;

  interface iGetByMultipleMoodTags extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getMoodTags();
  }