<?php

  namespace App\Manager\Moods;

  class MoodsManager extends \Simplon\Abstracts\AbstractCacheQueryManager
  {
    /**
     * @return string
     */
    protected function _getByTrackIdQuery()
    {
      return "
      SELECT
        count(mem.id) AS amount,
        mem.mood_tag AS tag
      FROM
        memories AS mem
      WHERE
        mem.track_id = :trackId
      GROUP BY mem.mood_tag
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
