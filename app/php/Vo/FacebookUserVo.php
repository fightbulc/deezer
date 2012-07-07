<?php

  namespace App\Vo;

  class FacebookUserVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getId()
    {
      return $this->getByKey('id');
    }

    // ##########################################

    public function getFacebookId()
    {
      return $this->getByKey('fb_id');
    }

    // ##########################################

    public function getUserName()
    {
      return $this->getByKey('username');
    }

    // ##########################################

    public function getFirstName()
    {
      return $this->getByKey('first_name');
    }

    // ##########################################

    public function getLastName()
    {
      return $this->getByKey('last_name');
    }

    // ##########################################

    public function getFullName()
    {
      return $this->getFirstName() . ' ' . $this->getLastName();
    }

    // ##########################################

    public function getLocale()
    {
      return $this->getByKey('locale');
    }

    // ##########################################

    public function getGender()
    {
      return $this->getByKey('gender');
    }

    // ##########################################

    public function getProfileUrl()
    {
      return $this->getByKey('link');
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
