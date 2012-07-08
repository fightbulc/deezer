<?php

  namespace App\Manager\Stories;

  class StoriesManager extends \Simplon\Abstracts\AbstractCacheQueryManager
  {
    private $_userManager;

    /**
     * @return \App\Manager\DeezerUsers\UserManager
     */
    protected function _getUserManager()
    {
      if (!$this->_userManager)
      {
        $this->_userManager = new \App\Manager\DeezerUsers\UserManager();
      }

      return $this->_userManager;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getByTrackIdQuery()
    {
      return "
      SELECT
        *
      FROM
        stories AS story
      WHERE
        story.track_id = :trackId
      ";
    }

    // ##########################################

    /**
     * @param \App\Request\Memories\rInterface\iGetByTrackId $requestVo
     * @return array
     */
    public function getByTrackId(\App\Request\Memories\rInterface\iGetByTrackId $requestVo)
    {
      $sqlQuery = $this->_getByTrackIdQuery();

      $conditions = array(
        'trackId' => $requestVo->getTrackId(),
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlQuery($sqlQuery)
        ->setSqlConditions($conditions);

      $stories = $this->fetchAll($dbCacheQuery);

      return \App\Factory\VoFactory::factory($stories, new \App\Vo\StoryVo());
    }

    // ##########################################

    /**
     * @param \App\Request\Stories\rInterface\iCreateStory $requestVo
     * @return bool
     */
    public function createStory(\App\Request\Stories\rInterface\iCreateStory $requestVo)
    {
      $userVo = $this
        ->_getUserManager()
        ->getUserVo($requestVo->getDeezerAccessToken());

      $data = array(
        'id'        => NULL,
        'track_id'  => $requestVo->getTrackId(),
        'user_id'   => $userVo->getId(),
        'story'     => $requestVo->getStory(),
        'created'   => time(),
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlTable('stories')
        ->setData($data);

      return $this->insert($dbCacheQuery);
    }
  }
