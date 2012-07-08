<?php

  namespace App\Request\Stories;

  class rCreateStory extends \Simplon\Abstracts\AbstractVo implements \App\Request\Stories\rInterface\iCreateStory
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
    public function getStory()
    {
      return $this->getByKey('story');
    }
  }
