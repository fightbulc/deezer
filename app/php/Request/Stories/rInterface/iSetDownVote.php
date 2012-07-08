<?php

  namespace App\Request\Stories\rInterface;

  interface iSetDownVote extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getStoryId();
  }