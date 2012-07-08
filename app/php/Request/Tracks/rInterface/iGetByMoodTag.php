<?php

  namespace App\Request\Tracks\rInterface;

  interface iGetByMoodTag extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getMoodTag();
  }