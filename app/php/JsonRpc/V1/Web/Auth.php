<?php

  namespace App\JsonRpc\V1\Web;

  class Auth extends \Simplon\Abstracts\AbstractClass
  {
    public function __construct(array $request)
    {
      // init facebook
      $this->initAccessToken();

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

    public function initAccessToken()
    {

    }
      $token_url = "http://connect.deezer.com/oauth/access_token.php?app_id=" . $app_id . "&secret=" . $app_secret . "&code=" . $code;

      $response = file_get_contents($token_url);

      $params = NULL;
      parse_str($response, $params);
      $api_url = "http://api.deezer.com/2.0/user/me?access_token=" . $params['access_token'];

      $user = json_decode(file_get_contents($api_url));

      echo("Hello " . $user->name);
    }