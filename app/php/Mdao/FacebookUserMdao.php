<?php

  namespace App\Mdao;

  class FacebookUserMdao extends \Simplon\Abstracts\AbstractMdao
  {
    protected $id = 'id';
    protected $userName = 'username';
    protected $firstName = 'first_name';
    protected $lastName = 'last_name';
    protected $fullName = 'name';
    protected $locale = 'locale';
    protected $email = 'email';
    protected $gender = 'gender';
    protected $link = 'link';

    // ########################################

    public function setId($value)
    {
      $this->setByKey($this->id, $value);
    }

    public function getId()
    {
      return $this->getByKey($this->id);
    }

    // ########################################

    public function setUserName($value)
    {
      $this->setByKey($this->userName, $value);
    }

    public function getUserName()
    {
      return $this->getByKey($this->userName);
    }

    // ########################################

    public function getFullName()
    {
      return $this->getByKey($this->fullName);
    }

    // ########################################

    public function setFirstName($value)
    {
      $this->setByKey($this->firstName, $value);
    }

    public function getFirstName()
    {
      return $this->getByKey($this->firstName);
    }

    // ########################################

    public function setLastName($value)
    {
      $this->setByKey($this->lastName, $value);
    }

    public function getLastName()
    {
      return $this->getByKey($this->lastName);
    }

    // ########################################

    public function setEmail($value)
    {
      $this->setByKey($this->email, $value);
    }

    public function getEmail()
    {
      return $this->getByKey($this->email);
    }

    // ########################################

    public function setLocale($value)
    {
      $this->setByKey($this->locale, $value);
    }

    public function getLocale()
    {
      return $this->getByKey($this->locale);
    }

    // ########################################

    public function setender($value)
    {
      $this->setByKey($this->gender, $value);
    }

    public function getGender()
    {
      return $this->getByKey($this->gender);
    }

    // ########################################

    public function setLink($value)
    {
      $this->setByKey($this->link, $value);
    }

    public function getLink()
    {
      return $this->getByKey($this->link);
    }
  }
