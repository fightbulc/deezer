<?php

  namespace App\Request\Memories;

  class rSetUpVote extends \Simplon\Abstracts\AbstractVo implements \App\Request\Memories\rInterface\iSetUpVote
  {
    /**
     * @return string
     */
    public function getId()
    {
      return $this->getByKey('id');
    }

    /**
     * @return string
     */
    public function getUserId()
    {
      return $this->getByKey('userId');
    }
  }
