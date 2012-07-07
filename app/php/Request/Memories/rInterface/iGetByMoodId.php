<?php

  namespace App\Request\Memories\rInterface;

  interface iGetByMoodId extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getMoodId();
  }