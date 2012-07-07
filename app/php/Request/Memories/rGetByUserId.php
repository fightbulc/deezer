<?php

  namespace App\Request\Memories;

  class rGetByUserId extends \Simplon\Abstracts\AbstractVo implements \App\Request\Memories\rInterface\iGetByUserId
  {
    /**
     * @return string
     */
    public function getUserId()
    {
      return $this->getByKey('userId');
    }
  }
