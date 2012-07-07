<?php

  namespace App\JsonRpc\V1\Open;

  class Auth extends \Simplon\Abstracts\AbstractClass
  {
    public function __construct(array $request)
    {
      // get users facebookId or fail
      $facebookUserId = \Simplon\Lib\Facebook\FacebookLib::getInstance()->getUserId();

      // is user registered
      $facebookUserMdao = \App\Factory\FacebookUserFactory::factory($facebookUserId);

      // new user
      if (!$facebookUserMdao->hasData())
      {
        // write in sql
        $facebookUserManager = new \App\Manager\Facebook\FacebookUserManager();
        $facebookUserManager->registerUser($facebookUserId);

        // write in cache
        $userData = \Simplon\Lib\Facebook\FacebookLib::getInstance()->getUserProfile('me');
        $facebookUserMdao->setData($userData);
        $facebookUserMdao->writeInCache();
      }
    }
  }