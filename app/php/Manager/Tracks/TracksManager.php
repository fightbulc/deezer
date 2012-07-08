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

    // ##########################################

    /**
     * @return string
     */
    protected function _getByMoodTagQuery()
    {
      return "
      SELECT
        count(mem.id) AS amount,
      	mem.mood_tag,
        mem.track_id AS id,
        track.artist AS artist_name,
        track.title AS track_title
      FROM
        memories AS mem
        LEFT JOIN tracks AS track ON track.id = mem.track_id
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

    // ##########################################

    /**
     * @param $moodTags
     * @return string
     */
    protected function _getByMultipleMoodTagsQuery($moodTags)
    {
      return "
      SELECT
        count(mem.id) AS amount,
        mem.mood_tag AS mood_name,
        mem.track_id AS id,
        track.artist AS artist_name,
        track.title AS track_title
      FROM
        memories AS mem
        LEFT JOIN tracks AS track ON track.id = mem.track_id
      WHERE
        mem.mood_tag IN(" . join(',', $moodTags) . ")
        AND mem.track_id != :excludeTrackId
      GROUP BY mem.track_id
      ";
    }

    // ##########################################

    /**
     * @param \App\Request\Tracks\rInterface\iGetByMultipleMoodTags $requestVo
     * @param $excludeTrackId
     * @return array
     */
    public function getByMultipleMoodTags(\App\Request\Tracks\rInterface\iGetByMultipleMoodTags $requestVo, $excludeTrackId)
    {
      $moodTags = $requestVo->getMoodTags();

      if(count($moodTags))
      {
        $sqlQuery = $this->_getByMultipleMoodTagsQuery($moodTags);

        $conditions = array(
          'excludeTrackId' => $excludeTrackId,
        );

        $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

        $dbCacheQuery
          ->setSqlQuery($sqlQuery)
          ->setSqlConditions($conditions);

        $tracks = $this->fetchAll($dbCacheQuery);

        return \App\Factory\VoFactory::factory($tracks, new \App\Vo\TrackVo());
      }

      return FALSE;
    }
  }
