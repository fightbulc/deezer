<?php

  namespace App\Dto\Tracks;

  class TracksByMoodTagDto extends \Simplon\Abstracts\AbstractDto
  {
    protected function getObjects()
    {
      return array(

        'id' => array(
          'vo' => 'getId',
        ),

        'amount' => array(
          'vo' => 'getAmount',
        ),

        'moodTag' => array(
          'vo' => 'getMoodTag',
        ),

      );
    }

  }
