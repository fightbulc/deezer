<?php

  namespace App\JsonRpc\V1\Auth\Service;

  class FooService extends \Simplon\Abstracts\AbstractService
  {
    public function getBar(array $request)
    {
      $dto = new \App\Dto\FacebookUserDefaultDto();
      $facebookUserId = \Simplon\Lib\Facebook\FacebookLib::getInstance()->getUserId();
      $facebookUserData = $dto->export(\App\Factory\FacebookUserFactory::factory($facebookUserId));

      return array(
        'fbUserData' => $facebookUserData
      );
    }
  }