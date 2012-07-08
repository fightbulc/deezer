<?php

  namespace App\JsonRpc\V1\Web\Service;

  class Memories extends \Simplon\Abstracts\AbstractService
  {
    /**
     * @param $request
     * @return array
     */
    public function createMemory($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Memories\rCreateMemory();
      $requestVo->setData($request);

      // save memory
      $manager = new \App\Manager\Memories\MemoriesManager();
      $result = $manager->createMemory($requestVo);

      return array('created' => $result > 0 ? TRUE : FALSE);
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function getByTrackId($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Memories\rGetByTrackId();
      $requestVo->setData($request);

      // get memories
      $manager = new \App\Manager\Memories\MemoriesManager();
      $memoriesVo = $manager->getByTrackId($requestVo);
      $memoriesDto = \App\Factory\DtoFactory::factory($memoriesVo, new \App\Dto\Memories\MemoriesByTrackDto());

      return $memoriesDto;
    }
  }
