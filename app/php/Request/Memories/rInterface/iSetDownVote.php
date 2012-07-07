<?php

  namespace App\Request\Memories\rInterface;

  interface iSetDownVote extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getId();
    public function getUserId();
  }