<?php

  namespace App\Manager\Collections;

  class CollectionsManager extends \Simplon\Abstracts\AbstractCacheQueryManager
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
     * @param \App\Request\Collections\rInterface\iGetByTrackId $requestVo
     * @return array
     */
    public function getByTrackId(\App\Request\Collections\rInterface\iGetByTrackId $requestVo)
    {
      $sqlQuery = $this->_getByTrackIdQuery();

      $conditions = array(
        'trackId' => $requestVo->getTrackId(),
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlQuery($sqlQuery)
        ->setSqlConditions($conditions);

      $collections = $this->fetchAll($dbCacheQuery);

      return \App\Factory\VoFactory::factory($collections, new \App\Vo\CollectionsVo());
    }
  }
