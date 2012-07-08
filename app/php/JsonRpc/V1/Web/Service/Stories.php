<?php

  namespace App\JsonRpc\V1\Web\Service;

  class Stories extends \Simplon\Abstracts\AbstractService
  {
    /**
     * @param $request
     * @return array
     */
    public function createStory($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Stories\rCreateStory();
      $requestVo->setData($request);

      // save memory
      $manager = new \App\Manager\Stories\StoriesManager();
      $result = $manager->createStory($requestVo);

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
      $requestVo = new \App\Request\Stories\rGetByTrackId();
      $requestVo->setData($request);

      // get memories
      $manager = new \App\Manager\Stories\StoriesManager();
      $storiesVo = $manager->getByTrackId($requestVo);
      $storiesDto = \App\Factory\DtoFactory::factory($storiesVo, new \App\Dto\Stories\StoriesByTrackDto());

      return $storiesDto;
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
