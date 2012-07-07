<?php

  namespace App\Manager\Memories;

  class MemoriesManager extends \Simplon\Abstracts\AbstractCacheQueryManager
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
        mem.*,
        SUM(mv.vote) AS votes
      FROM
        memories AS mem
        LEFT JOIN memory_votes AS mv ON mv.memory_id = mem.id
      WHERE
        mem.track_id = :trackId
      GROUP BY mem.id
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

      $memories = $this->fetchAll($dbCacheQuery);

      return \App\Factory\VoFactory::factory($memories, new \App\Vo\MemoryVo());
    }

    // ##########################################

    /**
     * @param \App\Request\Memories\rInterface\iCreateMemory $requestVo
     * @return bool
     */
    public function createMemory(\App\Request\Memories\rInterface\iCreateMemory $requestVo)
    {
      $userVo = $this
        ->_getUserManager()
        ->getUserVo($requestVo->getDeezerAccessToken());

      $data = array(
        'id'        => NULL,
        'user_id'   => $userVo->getId(),
        'track_id'  => $requestVo->getTrackId(),
        'mood_tag'  => $requestVo->getMoodTag(),
        'memory'    => $requestVo->getMemory(),
        'created'   => time(),
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlTable('memories')
        ->setData($data);

      return $this->insert($dbCacheQuery);
    }

    // ##########################################

    /**
     * @param $memoryId
     * @param $userId
     * @param $data
     * @return bool
     */
    protected function _InsertUpdateMemoryUserVote($memoryId, $userId, $data)
    {
      $sqlQuery = 'SELECT memory_id FROM memory_votes WHERE memory_id = :memoryId AND user_id = :userId';

      $sqlConditions = array(
        'memoryId' => $memoryId,
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
        $result = $this->_updateMemoryUserVote($data);
      }
      else
      {
        $result = $this->_insertMemoryUserVote($data);
      }

      return $result;
    }

    // ##########################################

    /**
     * @param $data
     * @return bool
     */
    protected function _insertMemoryUserVote($data)
    {
      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlTable('memory_votes')
        ->setData($data);

      return $this->insert($dbCacheQuery);
    }

    // ##########################################

    /**
     * @param $data
     * @return bool
     */
    protected function _updateMemoryUserVote($data)
    {
      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $sqlConditions = array(
        'memory_id' => $data['memory_id'],
        'user_id'   => $data['user_id'],
      );

      unset($data['memory_id']);
      unset($data['user_id']);

      $dbCacheQuery
        ->setSqlTable('memory_votes')
        ->setSqlConditions($sqlConditions)
        ->setData($data);

      return $this->update($dbCacheQuery);
    }

    // ##########################################

    /**
     * @param \App\Request\Memories\rInterface\iSetUpVote $requestVo
     * @return bool
     */
    public function setUpVote(\App\Request\Memories\rInterface\iSetUpVote $requestVo)
    {
      $userVo = $this
        ->_getUserManager()
        ->getUserVo($requestVo->getDeezerAccessToken());

      $data = array(
        'memory_id' => $requestVo->getId(),
        'user_id'   => $userVo->getId(),
        'vote'      => 1
      );

      return $this->_InsertUpdateMemoryUserVote($requestVo->getId(), $requestVo->getUserId(), $data);
    }

    // ##########################################

    /**
     * @param \App\Request\Memories\rInterface\iSetDownVote $requestVo
     * @return bool
     */
    public function setDownVote(\App\Request\Memories\rInterface\iSetDownVote $requestVo)
    {
      $userVo = $this
        ->_getUserManager()
        ->getUserVo($requestVo->getDeezerAccessToken());

      $data = array(
        'memory_id' => $requestVo->getId(),
        'user_id'   => $userVo->getId(),
        'vote'      => -1
      );

      return $this->_InsertUpdateMemoryUserVote($requestVo->getId(), $userVo->getId(), $data);
    }

    // ##########################################

    /**
     * @param \App\Request\Memories\rRemoveVote $requestVo
     * @return bool
     */
    public function removeVote(\App\Request\Memories\rRemoveVote $requestVo)
    {
      $userVo = $this
        ->_getUserManager()
        ->getUserVo($requestVo->getDeezerAccessToken());

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $sqlConditions = array(
        'memory_id' => $requestVo->getId(),
        'user_id'   => $userVo->getId(),
      );

      $dbCacheQuery
        ->setSqlTable('memory_votes')
        ->setSqlConditions($sqlConditions);

      return $this->remove($dbCacheQuery);
    }
  }
