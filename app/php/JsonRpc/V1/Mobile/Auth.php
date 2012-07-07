<?php

  namespace App\JsonRpc\V1\Mobile;

  class Auth extends \Simplon\Abstracts\AbstractClass
  {
    public function __construct(array $request)
    {
      // init facebook
      $this->initMobileAccessToken();

      // get facebookUserId
      $facebookUserId = \Simplon\Lib\Facebook\FacebookLib::getInstance()
        ->getUserId();

      // try to get cached user data
      $facebookUserManager = new \App\Manager\FacebookUsers\FacebookUserManager();
      $facebookUserVo = $facebookUserManager->getByFacebookId($facebookUserId);

      // create new user
      if (!$facebookUserVo->getId())
      {
        // get user data from facebook
        $userData = \Simplon\Lib\Facebook\FacebookLib::getInstance()
          ->getUserProfile('/me');

        // set in VO
        $facebookUserVo->setData($userData);

        // write in sql
        $facebookUserManager->registerUser($facebookUserVo);
      }

      return TRUE;
    }

    // ##########################################

    public function initMobileAccessToken()
    {
      // get facebook config
      $facebookAppId = \App\AppContext::getInstance()
        ->getFacebookConfig()
        ->getAppId();

      // mobile access token
      $cookieAccessKey = 'fbat_' . $facebookAppId;

      if (array_key_exists($cookieAccessKey, $_COOKIE))
      {
        // set access token
        \Simplon\Lib\Facebook\FacebookLib::getInstance()
          ->getFacebookSdk()
          ->setAccessToken($_COOKIE[$cookieAccessKey]);
      }
    }
  }