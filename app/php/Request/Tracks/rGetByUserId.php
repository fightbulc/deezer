<?php

  namespace App\Request\Tracks;

  class rGetByUserId extends \Simplon\Abstracts\AbstractVo implements \App\Request\Tracks\rInterface\iGetByUserId
  {
    /**
     * @return string
     */
    public function getUserId()
    {
      return $this->getByKey('userId');
    }
  }
