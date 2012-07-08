<?php

  namespace App\Request\Collections;

  class rGetByTrackId extends \Simplon\Abstracts\AbstractVo implements \App\Request\Collections\rInterface\iGetByTrackId
  {
    /**
     * @return string
     */
    public function getTrackId()
    {
      return $this->getByKey('trackId');
    }
  }
