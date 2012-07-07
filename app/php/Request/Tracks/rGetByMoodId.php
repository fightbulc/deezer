<?php

  namespace App\Request\Tracks;

  class rGetByMoodId extends \Simplon\Abstracts\AbstractVo implements \App\Request\Tracks\rInterface\iGetByMoodId
  {
    /**
     * @return string
     */
    public function getMoodId()
    {
      return $this->getByKey('moodId');
    }
  }
