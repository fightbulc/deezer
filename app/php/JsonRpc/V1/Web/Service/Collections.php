<?php

  namespace App\JsonRpc\V1\Web\Service;

  class Collections extends \Simplon\Abstracts\AbstractService
  {
    /**
     * @param $request
     * @return array
     */
    public function getByTrackId($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Collections\rGetByTrackId();
      $requestVo->setData($request);

      // get moods
      $manager = new \App\Manager\Collections\CollectionsManager();
      $collectionsVo = $manager->getByTrackId($requestVo);
      $collectionsDto = \App\Factory\DtoFactory::factory($collectionsVo, new \App\Dto\Moods\MoodsByTrackDto());

      return $collectionsDto;
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function getByUserId($request)
    {
    }
  }
