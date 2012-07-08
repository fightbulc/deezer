<?php

  namespace App\Vo;

  class MemoryVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getId()
    {
      return $this->getByKey('id');
    }

    // ##########################################

    public function getUserId()
    {
      return $this->getByKey('user_id');
    }

    // ##########################################

    public function getTrackId()
    {
      return $this->getByKey('track_id');
    }

    // ##########################################

    public function getMoodTag()
    {
      return $this->getByKey('mood_tag');
    }

    // ##########################################

    public function getMemory()
    {
      return $this->getByKey('memory');
    }

    // ##########################################

    public function getCreated()
    {
      return $this->getByKey('created');
    }
  }
