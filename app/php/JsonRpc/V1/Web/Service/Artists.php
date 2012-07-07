<?php

  namespace App\JsonRpc\V1\Web\Service;

  class Artists extends \Simplon\Abstracts\AbstractService
  {
    /**
     * @param $request
     * @return array
     */
    public function getByEventUrlName($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Artists\rGetByEventUrlName();
      $requestVo->setData($request);

      // get artists
      $artistsManager = new \App\Manager\Artists\ArtistsManager();
      $artistsVo = $artistsManager->getByEventUrlName($requestVo);
      $artistsDto = \App\Factory\DtoFactory::factory($artistsVo, new \App\Dto\Artist\ArtistEventByUrlNameDto());

      return $artistsDto;
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function getByUrlName($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Artists\rGetByUrlName();
      $requestVo->setData($request);

      // get artist by urlname
      $artistsManager = new \App\Manager\Artists\ArtistsManager();
      $artistVo = $artistsManager->getByUrlName($requestVo);
      $artistDto = \App\Factory\DtoFactory::singleFactory($artistVo, new \App\Dto\Artist\ArtistEventByUrlNameDto());

      return $artistDto;
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function getTracksDataByUrlName($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Artists\rGetTracksDataByUrlName();
      $requestVo->setData($request);

      // get artist tracks from soundcloud
      $artistsManager = new \App\Manager\Artists\ArtistsManager();
      $artistTracksVo = $artistsManager->getTracksData($requestVo);
      $artistTracksDto = \App\Factory\DtoFactory::factory($artistTracksVo, new \App\Dto\Artist\ArtistTracksDataDefaultDto());

      return $artistTracksDto;
    }
  }
