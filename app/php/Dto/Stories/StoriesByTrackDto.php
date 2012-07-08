<?php

  namespace App\Dto\Stories;

  class StoriesByTrackDto extends \Simplon\Abstracts\AbstractDto
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

        'votes' => array(
          'vo' => 'getVotes',
          'default' => 0,
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
