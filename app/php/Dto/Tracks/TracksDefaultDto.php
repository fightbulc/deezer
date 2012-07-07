<?php

  namespace App\Dto\Tracks;

  class TracksDefaultDto extends \Simplon\Abstracts\AbstractDto
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
