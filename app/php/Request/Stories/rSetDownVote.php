<?php

  namespace App\Request\Stories;

  class rSetDownVote extends \Simplon\Abstracts\AbstractVo implements \App\Request\Stories\rInterface\iSetDownVote
  {
    /**
     * @return string
     */
    public function getStoryId()
    {
      return $this->getByKey('storyId');
    }
  }
