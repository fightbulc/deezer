<?php

  namespace App\Dto\Memories;

  class MemoriesByTrackDto extends \Simplon\Abstracts\AbstractDto
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

        'moodTag' => array(
          'vo' => 'getMoodTag',
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
