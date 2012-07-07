<?php

  namespace App\Manager\Artists;

  class ArtistsManager extends \Simplon\Abstracts\AbstractCacheQueryManager
  {
    /**
     * @return string
     */
    protected function getByEventUrlNameQuery()
    {
      return "
      SELECT
        d.*,
        GROUP_CONCAT(DISTINCT bg.name) AS genres

      FROM
        events AS e
        INNER JOIN dj_event_relations AS der ON der.event_id = e.id
        INNER JOIN djs AS d ON d.id = der.dj_id
        INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
        INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id

      WHERE
        e.urlname = :eventUrlName

      GROUP BY d.id
      ";
    }

    // ##########################################

    /**
     * @param \App\Request\Artists\rInterface\iGetByEventUrlName $requestVo
     * @return array
     */
    public function getByEventUrlName(\App\Request\Artists\rInterface\iGetByEventUrlName $requestVo)
    {
      $sqlQuery = $this->getByEventUrlNameQuery();

      $conditions = array(
        'eventUrlName' => $requestVo->getEventUrlName(),
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlQuery($sqlQuery)
        ->setSqlConditions($conditions);

      $artists = $this->fetchAll($dbCacheQuery);

      return \App\Factory\VoFactory::factory($artists, new \App\Vo\ArtistVo());
    }

    // ##########################################

    /**
     * @return string
     */
    protected function getByUrlNameQuery()
    {
      return "
      SELECT
        d.*,
        GROUP_CONCAT(DISTINCT bg.name) AS genres

      FROM
        djs AS d
        INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
        INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id

      WHERE
        d.urlname = :urlName
      ";
    }

    // ##########################################

    /**
     * @param \App\Request\Artists\rInterface\iGetByUrlName $requestVo
     * @return \App\Vo\ArtistVo
     */
    public function getByUrlName(\App\Request\Artists\rInterface\iGetByUrlName $requestVo)
    {
      $sqlQuery = $this->getByUrlNameQuery();

      $conditions = array(
        'urlName' => $requestVo->getArtistUrlName()
      );

      $dbCacheQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbCacheQuery
        ->setSqlQuery($sqlQuery)
        ->setSqlConditions($conditions);

      $artist = $this->fetchRow($dbCacheQuery);

      if(empty($artist))
      {
        $this->throwException('artist does not exist.');
      }

      return \App\Factory\VoFactory::singleFactory($artist, new \App\Vo\ArtistVo());
    }

    // ##########################################

    /**
     * @param \App\Request\Artists\rInterface\iGetTracksDataByUrlName $requestVo
     * @return \App\Vo\ArtistTracksDataVo
     */
    public function getTracksData(\App\Request\Artists\rInterface\iGetTracksDataByUrlName $requestVo)
    {
      // get artistVo
      $artistRequest = array(
        'urlName' => $requestVo->getUrlName()
      );

      $artistRequestVo = new \App\Request\Artists\rGetByUrlName();
      $artistRequestVo->setData($artistRequest);
      $artistVo = $this->getByUrlName($artistRequestVo);

      // get tracks from soundcloud API
      $soundcloudManager = new SoundcloudManager();
      $tracksVo = $soundcloudManager->getSoundcloudTracksData($artistVo);

      return $tracksVo;
    }
  }
