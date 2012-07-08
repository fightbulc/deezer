<?php

  namespace App\Vo;

  class TrackVo extends \Simplon\Abstracts\AbstractVo
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

    public function getMoodTag()
    {
      return $this->getByKey('mood_tag');
    }
  }
