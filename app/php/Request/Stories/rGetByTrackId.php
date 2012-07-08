<?php

  namespace App\Request\Stories;

  class rGetByTrackId extends \Simplon\Abstracts\AbstractVo implements \App\Request\Stories\rInterface\iGetByTrackId
  {
    /**
     * @return string
     */
    public function getTrackId()
    {
      return $this->getByKey('trackId');
    }
  }
