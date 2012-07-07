<?php

  namespace App\Request\Memories;

  class rGetByMoodName extends \Simplon\Abstracts\AbstractVo implements \App\Request\Memories\rInterface\iGetByMoodName
  {
    /**
     * @return string
     */
    public function getMoodName()
    {
      return $this->getByKey('moodName');
    }
  }
