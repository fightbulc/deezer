<?php

  namespace App\Dto\Stories;

  class StoriesByIdDto extends \Simplon\Abstracts\AbstractDto
  {
    protected function getObjects()
    {
      return array(

        'id' => array(
          'vo' => 'getId',
        ),

        'userId' => array(
          'vo' => 'getUserId',
        ),

        'trackId' => array(
          'vo' => 'getTrackId',
        ),

        'story' => array(
          'vo' => 'getStory',
        ),

        'created' => array(
          'vo' => 'getCreated',
          'format' => function($date)
          {
            return \Simplon\Lib\Helper\FormatHelper::convertUnixTimeToIso($date);
          }
        ),

      );
    }

  }
