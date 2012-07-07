<?php

  namespace App\Vo;

  class MoodVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getAmount()
    {
      return $this->getByKey('amount');
    }

    // ##########################################

    public function getTag()
    {
      return $this->getByKey('tag');
    }
  }
