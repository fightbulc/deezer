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

      // save memory

      $data = array(
        'id'        => NULL,
        'user_id'   => $userVo->getId(),
        'track_id'  => $requestVo->getTrackId(),
        'mood_tag'  => $requestVo->getMoodTag(),
        'created'   => time(),
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlTable('memories')
        ->setData($data);

      $this->insert($dbCacheQuery);

      // save track
      $this->createTrack($requestVo);

      return TRUE;
    }

    // ##########################################

    /**
     * @param \App\Request\Memories\rInterface\iCreateMemory $requestVo
     * @return bool
     */
    public function createTrack(\App\Request\Memories\rInterface\iCreateMemory $requestVo)
    {
      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $data = array(
        'id'            => $requestVo->getTrackId(),
        'artist'        => $requestVo->getArtistName(),
        'title'         => $requestVo->getTrackTitle(),
      );

      $dbCacheQuery
        ->setSqlTable('tracks')
        ->setData($data)
        ->setSqlInsertIgnore(TRUE);

      return $this->insert($dbCacheQuery);
    }
  }
