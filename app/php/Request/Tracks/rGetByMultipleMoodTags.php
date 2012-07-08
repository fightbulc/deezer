<?php

  namespace App\Request\Tracks;

  class rGetByMultipleMoodTags extends \Simplon\Abstracts\AbstractVo implements \App\Request\Tracks\rInterface\iGetByMultipleMoodTags
  {
    /**
     * @return string
     */
    public function getMoodTags()
    {
      return $this->getByKey('moodTags');
    }
  }
