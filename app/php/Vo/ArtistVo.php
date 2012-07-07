<?php

  namespace App\Vo;

  class ArtistVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getId()
    {
      return $this->getByKey('id');
    }

    // ##########################################

    public function getUrlName()
    {
      return $this->getByKey('urlname');
    }

    // ##########################################

    public function getName()
    {
      return $this->getByKey('name');
    }

    // ##########################################

    public function getRealName()
    {
      return $this->getByKey('real_name');
    }

    // ##########################################

    public function getCountry()
    {
      return $this->getByKey('country');
    }

    // ##########################################

    public function getUrlSoundcloud()
    {
      return $this->getByKey('url_soundcloud');
    }

    // ##########################################

    public function getUrlSoundcloudParts()
    {
      $soundcloudUrl = $this->getUrlSoundcloud();
      $urlWithoutSoundcloud = preg_replace('/^.*?soundcloud.com\//i', '', $soundcloudUrl);
      $urlParts = explode('/', $urlWithoutSoundcloud);
      return $urlParts;
    }

    // ##########################################

    public function getUrlFacebook()
    {
      return $this->getByKey('url_facebook');
    }

    // ##########################################

    public function getUrlAvatar()
    {
      return $this->getByKey('url_avatar');
    }

    // ##########################################

    public function getDataSource()
    {
      return $this->getByKey('data_source');
    }

    // ##########################################

    public function getStatus()
    {
      return $this->getByKey('status');
    }

    // ##########################################

    public function getGenres()
    {
      return $this->getByKey('genres');
    }

    // ##########################################

    public function getCreated()
    {
      return $this->getByKey('created');
    }

    // ##########################################

    public function getUpdate()
    {
      return $this->getByKey('updated');
    }
  }
