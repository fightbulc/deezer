<?php

  namespace Simplon\Vo\Config;

  class CoreThirdPartyConfigVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getFacebookConfig()
    {
      return $this->getByKey('facebook');
    }
  }
