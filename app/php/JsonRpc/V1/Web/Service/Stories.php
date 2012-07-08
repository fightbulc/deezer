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

      // save story
      $manager = new \App\Manager\Stories\StoriesManager();
      $storyId = $manager->createStory($requestVo);

      // get story
      $storyDto = $this->getById(array('id' => $storyId));

      return $storyDto;
    }

    // ##########################################

    /**
     * @param $request
     * @return array
     */
    public function getById($request)
    {
      // create requestVo
      $requestVo = new \App\Request\Stories\rGetById();
      $requestVo->setData($request);

      // get story
      $manager = new \App\Manager\Stories\StoriesManager();
      $storyVo = $manager->getById($requestVo);
      $storyDto = \App\Factory\DtoFactory::singleFactory($storyVo, new \App\Dto\Stories\StoriesByIdDto());

      return $storyDto;
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

      // get stories
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
      $requestVo = new \App\Request\Stories\rSetUpVote();
      $requestVo->setData($request);

      // save memory
      $manager = new \App\Manager\Stories\StoriesManager();
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
      $requestVo = new \App\Request\Stories\rSetDownVote();
      $requestVo->setData($request);

      // save memory
      $manager = new \App\Manager\Stories\StoriesManager();
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
      $requestVo = new \App\Request\Stories\rRemoveVote();
      $requestVo->setData($request);

      // save memory
      $manager = new \App\Manager\Stories\StoriesManager();
      $result = $manager->removeVote($requestVo);

      return array('created' => TRUE);
    }
  }
