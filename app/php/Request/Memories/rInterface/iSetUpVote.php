<?php

  namespace App\Request\Memories\rInterface;

  interface iSetUpVote extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getId();
  }