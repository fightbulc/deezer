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

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function setUpVote($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Memories\rSetUpVote();
      $requestVo->setData($request);

      // save memory
      $manager = new \App\Manager\Memories\MemoriesManager();
      $result = $manager->setUpVote($requestVo);

      return array('created' => TRUE);
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function setDownVote($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Memories\rSetDownVote();
      $requestVo->setData($request);

      // save memory
      $manager = new \App\Manager\Memories\MemoriesManager();
      $result = $manager->setDownVote($requestVo);

      return array('created' => TRUE);
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function removeVote($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Memories\rRemoveVote();
      $requestVo->setData($request);

      // save memory
      $manager = new \App\Manager\Memories\MemoriesManager();
      $result = $manager->removeVote($requestVo);

      return array('created' => TRUE);
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function getByMoodName($request)
    {
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function getByMoodId($request)
    {
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
