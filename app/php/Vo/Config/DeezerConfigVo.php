<?php

  namespace App\Vo\Config;

  class DeezerConfigVo extends \Simplon\Abstracts\AbstractVo
  {
    public function getClientId()
    {
      return $this->getByKey('clientId');
    }

    // ##########################################

    public function getClientSecret()
    {
      return $this->getByKey('clientSecrect');
    }
  }
