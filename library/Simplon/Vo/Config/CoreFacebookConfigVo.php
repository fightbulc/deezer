<?php

  namespace Simplon\Vo\Config;

  class CoreFacebookConfigVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getAppId()
    {
      return $this->getByKey('appId');
    }

    // ##########################################

    public function getAppSecret()
    {
      return $this->getByKey('secrect');
    }
  }
