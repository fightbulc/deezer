<?php

  namespace Simplon\Vo\Config;

  class CoreCouchbaseConfigVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getServer()
    {
      return $this->getByKey('server');
    }

    // ##########################################

    public function getPort()
    {
      return $this->getByKey('port');
    }

    // ##########################################

    public function getBucket()
    {
      return $this->getByKey('bucket');
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
