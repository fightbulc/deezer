<?php

  namespace App\Request\Memories;

  class rGetByTrackId extends \Simplon\Abstracts\AbstractVo implements \App\Request\Memories\rInterface\iGetByTrackId
  {
    /**
     * @return string
     */
    public function getTrackId()
    {
      return $this->getByKey('trackId');
    }
  }
