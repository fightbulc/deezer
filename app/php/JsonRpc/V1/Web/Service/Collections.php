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
      $response = array();

      // create requestVo
      $requestVo = new \App\Request\Collections\rGetByTrackId();
      $requestVo->setData($request);

      // get moods
      $moodRequestVo = new \App\Request\Moods\rGetByTrackId();
      $moodRequestVo->setData($request);

      $moodsManager = new \App\Manager\Moods\MoodsManager();
      $moodsVo = $moodsManager->getByTrackId($moodRequestVo);
      $response['moods'] = \App\Factory\DtoFactory::factory($moodsVo, new \App\Dto\Moods\MoodsByTrackDto());

      // get tracks
      $tagsOnly = array();

      /** @var $moodVo \App\Vo\MoodVo */
      foreach ($moodsVo as $moodVo)
      {
        $tagsOnly[] = "'" . $moodVo->getTag() . "'";
      }

      $trackRequestVo = new \App\Request\Tracks\rGetByMultipleMoodTags();
      $trackRequestVo->setData(array('moodTags' => $tagsOnly));
      $tracksManager = new \App\Manager\Tracks\TracksManager();
      $tracksVo = $tracksManager->getByMultipleMoodTags($trackRequestVo, $requestVo->getTrackId());
      $response['tracks'] = \App\Factory\DtoFactory::factory($tracksVo, new \App\Dto\Tracks\TracksDefaultDto());

      // get stories
      $storyRequestVo = new \App\Request\Stories\rGetByTrackId();
      $storyRequestVo->setData($request);
      $storiesManager = new \App\Manager\Stories\StoriesManager();
      $storiesVo = $storiesManager->getByTrackId($storyRequestVo);
      $response['stories'] = \App\Factory\DtoFactory::factory($storiesVo, new \App\Dto\Stories\StoriesByTrackDto());

      // return response
      return $response;
    }
  }
