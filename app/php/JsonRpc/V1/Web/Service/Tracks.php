<?php

  namespace App\JsonRpc\V1\Web\Service;

  class Tracks extends \Simplon\Abstracts\AbstractService
  {
    /**
     * @param $request
     * @return array
     */
    public function getByTrackId($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Moods\rGetByTrackId();
      $requestVo->setData($request);

      // get artists
      $moodsManager = new \App\Manager\Moods\MoodsManager();
      $moodsVo = $moodsManager->getByTrackId($requestVo);
      $moodsDto = \App\Factory\DtoFactory::factory($moodsVo, new \App\Dto\Moods\MoodsByTrackDto());

      return $moodsDto;
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function getByMoodTag($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Tracks\rGetByMoodTag();
      $requestVo->setData($request);

      // get artists
      $manager = new \App\Manager\Tracks\TracksManager();
      $tracksVo = $manager->getByMoodTag($requestVo);
      $tracksDto = \App\Factory\DtoFactory::factory($tracksVo, new \App\Dto\Tracks\TracksByMoodTagDto());

      return $tracksDto;
    }
  }
