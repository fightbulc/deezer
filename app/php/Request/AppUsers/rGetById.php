<?php

  namespace App\Request\AppUsers;

  class rGetById extends \Simplon\Abstracts\AbstractVo implements \App\Request\AppUsers\rInterface\iGetById
  {
    /**
     * @return string
     */
    public function getId()
    {
      return $this->getByKey('id');
    }
  }
