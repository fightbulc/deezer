<?php

  namespace App\Factory;

  class FacebookUserFactory
  {
    /**
     * @var array
     */
    private static $_collection = array();

    // ##########################################

    /**
     * @static
     * @param $facebookUserId
     * @return \App\Mdao\FacebookUserMdao
     */
    public static function factory($facebookUserId)
    {
      $item = NULL;

      if (array_key_exists($facebookUserId, FacebookUserFactory::$_collection))
      {
        $item = FacebookUserFactory::$_collection[$facebookUserId];
      }

      if (is_null($item))
      {
        $mdao = new \App\Mdao\FacebookUserMdao();
        $mdao->setCacheId('facebookUser_' . $facebookUserId);
        $mdao->getFromCache();

        FacebookUserFactory::$_collection[$facebookUserId] = $mdao;
      }

      return FacebookUserFactory::$_collection[$facebookUserId];
    }
  }
