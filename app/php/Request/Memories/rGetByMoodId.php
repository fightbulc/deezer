<?php

  namespace App\Request\Memories;

  class rGetByMoodId extends \Simplon\Abstracts\AbstractVo implements \App\Request\Memories\rInterface\iGetByMoodId
  {
    /**
     * @return string
     */
    public function getMoodId()
    {
      return $this->getByKey('moodId');
    }
  }
