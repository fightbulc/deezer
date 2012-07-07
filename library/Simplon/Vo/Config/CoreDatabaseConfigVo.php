<?php

  namespace Simplon\Vo\Config;

  class CoreDatabaseConfigVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getMysqlConfig()
    {
      return $this->getByKey('mysql');
    }

    // ##########################################

    public function getCouchbaseConfig()
    {
      return $this->getByKey('couchbase');
    }
  }
