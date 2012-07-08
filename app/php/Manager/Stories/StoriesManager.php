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
    protected function _getByIdQuery()
    {
      return "
      SELECT
        story.*,
        SUM(sv.vote) AS votes
      FROM
        stories AS story
        LEFT JOIN story_votes AS sv ON sv.story_id = story.id
      WHERE
        story.id = :storyId
      GROUP BY story.id
      ";
    }

    // ##########################################

    /**
     * @param \App\Request\Stories\rInterface\iGetById $requestVo
     * @return \Simplon\Abstracts\AbstractVo
     */
    public function getById(\App\Request\Stories\rInterface\iGetById $requestVo)
    {
      $sqlQuery = $this->_getByIdQuery();

      $conditions = array(
        'storyId' => $requestVo->getId(),
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlQuery($sqlQuery)
        ->setSqlConditions($conditions);

      $story = $this->fetchRow($dbCacheQuery);

      return \App\Factory\VoFactory::singleFactory($story, new \App\Vo\StoryVo());
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getByTrackIdQuery()
    {
      return "
      SELECT
        story.*,
        SUM(sv.vote) AS votes
      FROM
        stories AS story
        LEFT JOIN story_votes AS sv ON sv.story_id = story.id
      WHERE
        story.track_id = :trackId
      GROUP BY story.id
      ";
    }

    // ##########################################

    /**
     * @param \App\Request\Stories\rInterface\iGetByTrackId $requestVo
     * @return array
     */
    public function getByTrackId(\App\Request\Stories\rInterface\iGetByTrackId $requestVo)
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
      $story = $requestVo->getStory();

      if(!empty($story))
      {
        $userVo = $this
          ->_getUserManager()
          ->getUserVo($requestVo->getDeezerAccessToken());

        $data = array(
          'id'        => NULL,
          'track_id'  => $requestVo->getTrackId(),
          'user_id'   => $userVo->getId(),
          'story'     => $story,
          'created'   => time(),
        );

        $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

        $dbCacheQuery
          ->setSqlTable('stories')
          ->setData($data);

        return $this->insert($dbCacheQuery);
      }

      return FALSE;
    }

    // ##########################################

    /**
     * @param $storyId
     * @param $userId
     * @param $data
     * @return bool
     */
    protected function _InsertUpdateStoryUserVote($storyId, $userId, $data)
    {
      $sqlQuery = 'SELECT story_id FROM story_votes WHERE story_id = :storyId AND user_id = :userId';

      $sqlConditions = array(
        'storyId' => $storyId,
        'userId'   => $userId,
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlQuery($sqlQuery)
        ->setSqlConditions($sqlConditions);

      $isInDb = NULL;
      $isInDb = $this->fetchColumn($dbCacheQuery);

      if ($isInDb)
      {
        $result = $this->_updateStoryUserVote($data);
      }
      else
      {
        $result = $this->_insertStoryUserVote($data);
      }

      return $result;
    }

    // ##########################################

    /**
     * @param $data
     * @return bool
     */
    protected function _insertStoryUserVote($data)
    {
      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlTable('story_votes')
        ->setData($data);

      return $this->insert($dbCacheQuery);
    }

    // ##########################################

    /**
     * @param $data
     * @return bool
     */
    protected function _updateStoryUserVote($data)
    {
      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $sqlConditions = array(
        'story_id' => $data['story_id'],
        'user_id'   => $data['user_id'],
      );

      unset($data['story_id']);
      unset($data['user_id']);

      $dbCacheQuery
        ->setSqlTable('story_votes')
        ->setSqlConditions($sqlConditions)
        ->setData($data);

      return $this->update($dbCacheQuery);
    }

    // ##########################################

    /**
     * @param \App\Request\Stories\rInterface\iSetUpVote $requestVo
     * @return bool
     */
    public function setUpVote(\App\Request\Stories\rInterface\iSetUpVote $requestVo)
    {
      $userVo = $this
        ->_getUserManager()
        ->getUserVo($requestVo->getDeezerAccessToken());

      $data = array(
        'story_id' => $requestVo->getStoryId(),
        'user_id'   => $userVo->getId(),
        'vote'      => 1
      );

      return $this->_InsertUpdateStoryUserVote($requestVo->getStoryId(), $userVo->getId(), $data);
    }

    // ##########################################

    /**
     * @param \App\Request\Stories\rInterface\iSetDownVote $requestVo
     * @return bool
     */
    public function setDownVote(\App\Request\Stories\rInterface\iSetDownVote $requestVo)
    {
      $userVo = $this
        ->_getUserManager()
        ->getUserVo($requestVo->getDeezerAccessToken());

      $data = array(
        'story_id' => $requestVo->getStoryId(),
        'user_id'   => $userVo->getId(),
        'vote'      => -1
      );

      return $this->_InsertUpdateStoryUserVote($requestVo->getStoryId(), $userVo->getId(), $data);
    }

    // ##########################################

    /**
     * @param \App\Request\Stories\rInterface\iRemoveVote $requestVo
     * @return bool
     */
    public function removeVote(\App\Request\Stories\rInterface\iRemoveVote $requestVo)
    {
      $userVo = $this
        ->_getUserManager()
        ->getUserVo($requestVo->getDeezerAccessToken());

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $sqlConditions = array(
        'story_id' => $requestVo->getStoryId(),
        'user_id'   => $userVo->getId(),
      );

      $dbCacheQuery
        ->setSqlTable('story_votes')
        ->setSqlConditions($sqlConditions);

      return $this->remove($dbCacheQuery);
    }
  }
