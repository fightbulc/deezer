<?php

  namespace App\Request\Tracks;

  class rGetByMoodName extends \Simplon\Abstracts\AbstractVo implements \App\Request\Tracks\rInterface\iGetByMoodName
  {
    /**
     * @return string
     */
    public function getMoodName()
    {
      return $this->getByKey('moodName');
    }
  }
