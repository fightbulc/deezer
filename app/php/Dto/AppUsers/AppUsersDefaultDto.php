<?php

  namespace App\Dto\AppUsers;

  class AppUsersDefaultDto extends \Simplon\Abstracts\AbstractDto
  {
    protected function getObjects()
    {
      return array(

        'id' => array(
          'vo' => 'getId',
        ),

        'name' => array(
          'vo' => 'getName',
        ),

      );
    }

  }
