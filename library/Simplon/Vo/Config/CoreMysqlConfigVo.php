<?php

  namespace Simplon\Vo\Config;

  class CoreMysqlConfigVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getServer()
    {
      return $this->getByKey('server');
    }

    // ##########################################

    public function getDatabase()
    {
      return $this->getByKey('database');
    }

    // ##########################################

    public function getUserName()
    {
      return $this->getByKey('username');
    }

    // ##########################################

    public function getPassword()
    {
      return $this->getByKey('password');
    }
  }
