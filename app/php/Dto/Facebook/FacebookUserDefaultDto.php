<?php

  namespace App\Dto\Facebook;

  class FacebookUserDefaultDto extends \Simplon\Abstracts\AbstractDto
  {
    /**
     * @return array
     */
    protected function getObjects()
    {
      return array(

        'id'  => array(
          'vo' => 'getId',
        ),

        'fbUserId'  => array(
          'vo' => 'getFacebookId',
        ),

        'firstName' => array(
          'vo' => 'getFirstName',
        ),

        'lastName'  => array(
          'vo' => 'getLastName',
        ),

      );
    }
  }
