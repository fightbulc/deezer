<?php

  namespace App\Manager\DeezerUsers;

  class UserManager extends \Simplon\Abstracts\AbstractCacheQueryManager
  {
    /**
     * @param $accessToken
     * @return \App\Vo\UserVo
     */
    public function getUserVo($accessToken)
    {
      if (!array_key_exists('userDetails', $_SESSION))
      {
        $_SESSION['userDetails'] = $this->setUserDetails($accessToken);
      }

      $userVo = new \App\Vo\UserVo();
      $userVo->setData($_SESSION['userDetails']);

      return $userVo;
    }

    // ##########################################

    /**
     * @param $accessToken
     * @return array
     */
    public function setUserDetails($accessToken)
    {
      $userApiUrl = 'http://api.deezer.com/2.0/user/me?access_token=' . $accessToken;

      $rawResponse = \Simplon\Vendor\CURL\CURL::init($userApiUrl)
        ->setReturnTransfer(TRUE)
        ->execute();

      return \Simplon\Lib\Helper\FormatHelper::jsonDecode($rawResponse);
    }
  }
