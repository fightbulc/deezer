<?php

  namespace Simplon\Lib\Facebook;

  class FacebookLib extends \Simplon\Abstracts\AbstractClass
  {
    /**
     * @var FacebookLib
     */
    private static $_instance;

    /**
     * @var \Simplon\Vendor\Facebook\Facebook
     */
    private $_facebookSdk;

    // ##########################################

    /**
     * @static
     * @return FacebookLib
     */
    public static function getInstance()
    {
      if (!FacebookLib::$_instance)
      {
        FacebookLib::$_instance = new FacebookLib();
      }

      return FacebookLib::$_instance;
    }

    // ##########################################

    /**
     * @return array
     */
    public function getFacebookConfig()
    {
      return $this->getConfigByKey(array(
        'thirdParty',
        'facebook'
      ));
    }

    // ##########################################

    /**
     * @return \Simplon\Vendor\Facebook\Facebook
     */
    public function getFacebookSdk()
    {
      if (!$this->_facebookSdk)
      {
        $facebookConfig = $this->getFacebookConfig();
        $this->_facebookSdk = new \Simplon\Vendor\Facebook\Facebook($facebookConfig);
      }

      return $this->_facebookSdk;
    }

    // ##########################################

    /**
     * @return string
     * @throws \Exception
     */
    public function getUserId()
    {
      try
      {
        return $this
          ->getFacebookSdk()
          ->getUser();
      }
      catch (\Exception $e)
      {
        throw new \Exception('Cannot access facebookUserId: ' . $e);
      }
    }

    // ##########################################

    /**
     * @param $fbId
     * @return mixed
     * @throws \Exception
     */
    public function getUserProfile($fbId)
    {
      try
      {
        return $this
          ->getFacebookSdk()
          ->api('/' . $fbId);
      }
      catch (\Exception $e)
      {
        throw new \Exception('Authentication with Facebook failed:' . $e);
      }
    }

  }
