<?php

  namespace App\Manager\Facebook;

  class FacebookUserManager extends \Simplon\Abstracts\AbstractCacheQueryManager
  {
    /**
     * @param string $facebookUserId
     *
     * @return bool
     */
    public function registerUser($facebookUserId)
    {
      $dbQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $data = array(
        'id' => NULL,
        'fb_id' => $facebookUserId,
        'created' => time()
      );

      $dbQuery
        ->setSqlConnector('master')
        ->setSqlTable('users')
        ->setData($data);

      return $this->insert($dbQuery);
    }
  }
