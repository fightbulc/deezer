<?php

  namespace App\Vo;

  class UserVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getId()
    {
      return $this->getByKey('id');
    }

    // ##########################################

    public function getFirstName()
    {
      return $this->getByKey('firstname');
    }

    // ##########################################

    public function getLastName()
    {
      return $this->getByKey('lastname');
    }

    // ##########################################

    public function getFullName()
    {
      return $this->getByKey('name');
    }

    // ##########################################

    public function getGender()
    {
      return $this->getByKey('gender');
    }

    // ##########################################

    public function getCountry()
    {
      return $this->getByKey('country');
    }

    // ##########################################

    public function getLanguage()
    {
      return $this->getByKey('lang');
    }

    // ##########################################

    public function getProfileUrl()
    {
      return $this->getByKey('link');
    }

    // ##########################################

    public function getAvatar()
    {
      return $this->getByKey('picture');
    }
  }
