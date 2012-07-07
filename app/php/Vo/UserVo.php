<?php

  namespace App\Vo;

  class UserVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getId()
    {
      return $this->getByKey('id');
    }

    // ##########################################

    public function getAmount()
    {
      return $this->getByKey('amount');
    }

    // ##########################################

    public function getName()
    {
      return $this->getByKey('name');
    }
  }
