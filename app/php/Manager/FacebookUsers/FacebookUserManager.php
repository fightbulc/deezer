<?php

  namespace App\Manager\FacebookUsers;

  class FacebookUserManager extends \Simplon\Abstracts\AbstractCacheQueryManager
  {
    /**
     * @return string
     */
    public function getUserQuery()
    {
      return "
      SELECT
        fb_id,
        id,
        first_name,
        last_name
      FROM
        fbusers
      WHERE
        fb_id = :fbId
      ";
    }

    // ##########################################

    /**
     * @param $facebookUserId
     * @return \App\Vo\FacebookUserVo
     */
    public function getByFacebookId($facebookUserId)
    {
      $dbQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $sqlQuery = $this->getUserQuery();

      $sqlConditions = array(
        'fbId' => $facebookUserId,
      );

      $dbQuery
        ->setSqlQuery($sqlQuery)
        ->setSqlConditions($sqlConditions);

      $userData = $this->fetchRow($dbQuery);

      if(empty($userData))
      {
        return new \App\Vo\FacebookUserVo();
      }

      return \App\Factory\VoFactory::singleFactory($userData, new \App\Vo\FacebookUserVo());
    }

    // ##########################################

    /**
     * @param \App\Vo\FacebookUserVo $facebookUserVo
     * @return bool
     */
    public function registerUser(\App\Vo\FacebookUserVo $facebookUserVo)
    {
      $dbQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $data = array(
        'id'           => NULL,
        'fb_id'        => $facebookUserVo->getId(),
        'username'     => $facebookUserVo->getUserName(),
        'first_name'   => $facebookUserVo->getFirstName(),
        'last_name'    => $facebookUserVo->getLastName(),
        'locale'       => $facebookUserVo->getLocale(),
        'gender'       => $facebookUserVo->getGender(),
        'created'      => \Simplon\Lib\Helper\FormatHelper::getUnixTime(),
        'updated'      => \Simplon\Lib\Helper\FormatHelper::getUnixTime(),
      );

      $dbQuery
        ->setSqlTable('fbusers')
        ->setData($data);

      return $this->insert($dbQuery);
    }
  }
