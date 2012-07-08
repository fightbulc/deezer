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
      $moodRequestVo = new \App\Request\Moods\rGetByTrackId();
      $moodRequestVo->setData($request);

      $moodsManager = new \App\Manager\Moods\MoodsManager();
      $moodsVo = $moodsManager->getByTrackId($moodRequestVo);
    }
  }
