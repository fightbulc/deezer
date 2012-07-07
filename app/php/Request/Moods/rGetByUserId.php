<?php

  namespace App\Request\Moods;

  class rGetByUserId extends \Simplon\Abstracts\AbstractVo implements \App\Request\Moods\rInterface\iGetByUserId
  {
    /**
     * @return string
     */
    public function getUserId()
    {
      return $this->getByKey('userId');
    }
  }
