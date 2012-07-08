<?php

  namespace App\Vo;

  class TrackVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getId()
    {
      return $this->getByKey('id');
    }

    // ##########################################

    public function getArtistName()
    {
      return $this->getByKey('artist_name');
    }

    // ##########################################

    public function getTrackTitle()
    {
      return $this->getByKey('track_title');
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
