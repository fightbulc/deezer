<?php

  namespace App\Request\Stories\rInterface;

  interface iSetUpVote extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getStoryId();
  }