<?php

  namespace App\Request\Stories;

  class rSetUpVote extends \Simplon\Abstracts\AbstractVo implements \App\Request\Stories\rInterface\iSetUpVote
  {
    /**
     * @return string
     */
    public function getStoryId()
    {
      return $this->getByKey('storyId');
    }
  }
