<?php

  namespace App\Request\Stories;

  class rRemoveVote extends \Simplon\Abstracts\AbstractVo implements \App\Request\Stories\rInterface\iRemoveVote
  {
    /**
     * @return string
     */
    public function getStoryId()
    {
      return $this->getByKey('storyId');
    }
  }
