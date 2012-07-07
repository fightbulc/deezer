<?php

  namespace App\Vo\Config;

  class ThirdPartyConfigVo extends \Simplon\Vo\Config\CoreThirdPartyConfigVo
  {
    public function getSoundcloudConfig()
    {
      return $this->getByKey('soundcloud');
    }
  }
