<?php

  namespace App\Request\Memories\rInterface;

  interface iRemoveVote extends \Simplon\Abstracts\iAbstractRequest
  {
    public function getId();
  }