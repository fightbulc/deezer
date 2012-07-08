<?php

  namespace App\Request\Stories;

  class rGetById extends \Simplon\Abstracts\AbstractVo implements \App\Request\Stories\rInterface\iGetById
  {
    /**
     * @return string
     */
    public function getId()
    {
      return $this->getByKey('id');
    }
  }
