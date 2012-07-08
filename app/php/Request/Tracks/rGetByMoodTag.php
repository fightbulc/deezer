<?php

  namespace App\Request\Tracks;

  class rGetByMoodTag extends \Simplon\Abstracts\AbstractVo implements \App\Request\Tracks\rInterface\iGetByMoodTag
  {
    /**
     * @return string
     */
    public function getMoodTag()
    {
      return $this->getByKey('moodTag');
    }
  }
