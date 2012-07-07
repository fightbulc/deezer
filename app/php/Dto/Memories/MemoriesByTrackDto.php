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

        'memory' => array(
          'vo' => 'getMemory',
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
