<?php

  namespace App\Request\Memories\rInterface;

  interface iGetByMoodName extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getMoodName();
  }