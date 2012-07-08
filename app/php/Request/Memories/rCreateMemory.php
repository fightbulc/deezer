<?php

  namespace App\Request\Memories;

  class rCreateMemory extends \Simplon\Abstracts\AbstractVo implements \App\Request\Memories\rInterface\iCreateMemory
  {
    /**
     * @return string
     */
    public function getTrackId()
    {
      return $this->getByKey('trackId');
    }

    /**
     * @return string
     */
    public function getArtistName()
    {
      return $this->getByKey('artistName');
    }

    /**
     * @return string
     */
    public function getTrackTitle()
    {
      return $this->getByKey('trackTitle');
    }

    /**
     * @return string
     */
    public function getMoodTag()
    {
      return \Simplon\Lib\Helper\FormatHelper::stringUrlable($this->getByKey('moodTag'), '-');
    }
  }
