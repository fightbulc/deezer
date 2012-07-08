<?php

  namespace App\Request\Stories\rInterface;

  interface iRemoveVote extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getStoryId();
  }