<?php

  namespace App\Dto\Moods;

  class MoodsByTrackDto extends \Simplon\Abstracts\AbstractDto
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

        'name' => array(
          'vo' => 'getName',
        ),

      );
    }

  }
