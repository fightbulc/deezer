<?php

  namespace App\Request\Moods;

  class rGetByTrackId extends \Simplon\Abstracts\AbstractVo implements \App\Request\Moods\rInterface\iGetByTrackId
  {
    /**
     * @return string
     */
    public function getTrackId()
    {
      return $this->getByKey('trackId');
    }
  }
