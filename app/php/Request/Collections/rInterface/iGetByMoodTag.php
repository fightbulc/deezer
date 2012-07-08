<?php

  namespace App\Request\Collections\rInterface;

  interface iGetByMoodTag extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getMoodTag();
  }