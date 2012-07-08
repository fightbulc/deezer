<?php

  namespace App\Vo;

  class StoryVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getId()
    {
      return $this->getByKey('id');
    }

    // ##########################################

    public function getTrackId()
    {
      return $this->getByKey('track_id');
    }

    // ##########################################

    public function getUserId()
    {
      return $this->getByKey('user_id');
    }

    // ##########################################

    public function getStory()
    {
      return $this->getByKey('story');
    }

    // ##########################################

    public function getVotes()
    {
      return $this->getByKey('votes');
    }

    // ##########################################

    public function getCreated()
    {
      return $this->getByKey('created');
    }
  }
