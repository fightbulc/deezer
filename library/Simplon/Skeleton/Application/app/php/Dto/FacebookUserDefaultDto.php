<?php

  namespace App\Dto;

  class FacebookUserDefaultDto extends \Simplon\Abstracts\AbstractDto
  {
    protected function getObjects()
    {
      return array(

        'fbUserId' => array(
          'vo' => 'getId',
          'default' => ''
        ),

        'firstName' => array(
          'vo' => 'getFirstName',
          'default' => ''
        ),

        'lastName' => array(
          'vo' => 'getLastName',
          'default' => ''
        ),

      );
    }
  }
