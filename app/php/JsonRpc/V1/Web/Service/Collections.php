<?php

  namespace App\JsonRpc\V1\Web\Service;

  class Collections extends \Simplon\Abstracts\AbstractService
  {
    /**
     * @param $request
     * @return array
     */
    protected function _getStoriesByTrackId($request)
    {
      $storyRequestVo = new \App\Request\Stories\rGetByTrackId();
      $storyRequestVo->setData($request);
      $storiesManager = new \App\Manager\Stories\StoriesManager();
      $storiesVo = $storiesManager->getByTrackId($storyRequestVo);
      return \App\Factory\DtoFactory::factory($storiesVo, new \App\Dto\Stories\StoriesByTrackDto());
    }

    // ##########################################

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
      $response['stories'] = $this->_getStoriesByTrackId($request);

      // return response
      return $response;
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function getByMoodTag($request)
    {
      $response = array();

      // create requestVo
      $requestVo = new \App\Request\Collections\rGetByMoodTag();
      $requestVo->setData($request);

      // get tracks
      $trackRequestVo = new \App\Request\Tracks\rGetByMoodTag();
      $trackRequestVo->setData($request);
      $tracksManager = new \App\Manager\Tracks\TracksManager();
      $tracksVo = $tracksManager->getByMoodTag($trackRequestVo);
      $tracksDto = \App\Factory\DtoFactory::factory($tracksVo, new \App\Dto\Tracks\TracksByMoodTagDto());

      $shuffledTracks = array();

      /** @var $trackVo \App\Vo\TrackVo */
      foreach($tracksVo as $trackVo)
      {
        $shuffledTracks[] = $trackVo->getId();
      }

      $shuffledTracks = \Simplon\Lib\Helper\FormatHelper::arrayShuffle($shuffledTracks);
      $randomTrackId = \Simplon\Lib\Helper\FormatHelper::arrayShift($shuffledTracks);

      foreach($tracksDto as $track)
      {
        if($track['id'] != $randomTrackId)
        {
          $response['tracks'][] = $track;
        }
        else
        {
          $response['randomTrack'] = $track;
        }
      }

      // get moods
      $moodRequestVo = new \App\Request\Moods\rGetByTrackId();
      $moodRequestVo->setData(array('trackId' => $randomTrackId));

      $moodsManager = new \App\Manager\Moods\MoodsManager();
      $moodsVo = $moodsManager->getByTrackId($moodRequestVo);
      $response['moods'] = \App\Factory\DtoFactory::factory($moodsVo, new \App\Dto\Moods\MoodsByTrackDto());

      // get stories
      $response['stories'] = $this->_getStoriesByTrackId(array('trackId' => $randomTrackId));

      // return response
      return $response;
    }
  }
