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
  }
