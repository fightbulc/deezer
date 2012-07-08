<?php

  namespace App\Manager\Tracks;

  class TracksManager extends \Simplon\Abstracts\AbstractCacheQueryManager
  {
    /**
     * @return string
     */
    protected function _getByTrackIdQuery()
    {
      return "
      SELECT
        count(mood.id) AS amount,
        mood.id,
        mood.name
      FROM
        memories AS mem
        INNER JOIN moods AS mood ON mem.mood_id = mood.id
      WHERE
        track_id = :trackId
      GROUP BY mood.name
      ORDER BY amount DESC, mood.name ASC
      ";
    }

    // ##########################################

    /**
     * @param \App\Request\Moods\rInterface\iGetByTrackId $requestVo
     * @return array
     */
    public function getByTrackId(\App\Request\Moods\rInterface\iGetByTrackId $requestVo)
    {
      $sqlQuery = $this->_getByTrackIdQuery();

      $conditions = array(
        'trackId' => $requestVo->getTrackId(),
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlQuery($sqlQuery)
        ->setSqlConditions($conditions);

      $moods = $this->fetchAll($dbCacheQuery);

      return \App\Factory\VoFactory::factory($moods, new \App\Vo\MoodVo());
    }

    /**
     * @return string
     */
    protected function _getByMoodTagQuery()
    {
      return "
      SELECT
        count(mem.id) AS amount,
        mem.track_id AS id,
      	mem.mood_tag
      FROM
        memories AS mem
      WHERE
        MATCH(mem.mood_tag) AGAINST(:moodTag IN BOOLEAN MODE)
      GROUP BY mem.track_id
      ";
    }

    // ##########################################

    /**
     * @param \App\Request\Tracks\rInterface\iGetByMoodTag $requestVo
     * @return array
     */
    public function getByMoodTag(\App\Request\Tracks\rInterface\iGetByMoodTag $requestVo)
    {
      $sqlQuery = $this->_getByMoodTagQuery();

      $conditions = array(
        'moodTag' => $requestVo->getMoodTag() . '*',
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlQuery($sqlQuery)
        ->setSqlConditions($conditions);

      $tracks = $this->fetchAll($dbCacheQuery);

      return \App\Factory\VoFactory::factory($tracks, new \App\Vo\TrackVo());
    }
  }
