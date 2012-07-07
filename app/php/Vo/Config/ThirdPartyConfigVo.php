<?php

  namespace App\Vo\Config;

  class ThirdPartyConfigVo extends \Simplon\Vo\Config\CoreThirdPartyConfigVo
  {
    public function getDeezerConfig()
    {
      return $this->getByKey('dezer');
    }
  }
