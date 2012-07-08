<?php

  namespace App\Request\Collections;

  class rGetByMoodTag extends \Simplon\Abstracts\AbstractVo implements \App\Request\Collections\rInterface\iGetByMoodTag
  {
    /**
     * @return string
     */
    public function getMoodTag()
    {
      return $this->getByKey('moodTag');
    }
  }
