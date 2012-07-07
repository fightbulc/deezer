<?php

  namespace App\Dto\Moods;

  class MoodsByTrackDto extends \Simplon\Abstracts\AbstractDto
  {
    protected function getObjects()
    {
      return array(

        'amount' => array(
          'vo' => 'getAmount',
        ),

        'tag' => array(
          'vo' => 'getTag',
        ),

      );
    }

  }
