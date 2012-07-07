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
    public function getUserId()
    {
      return $this->getByKey('userId');
    }

    /**
     * @return string
     */
    public function getMoodTag()
    {
      return $this->getByKey('moodTag');
    }

    /**
     * @return string
     */
    public function getMemory()
    {
      return $this->getByKey('memory');
    }
  }
