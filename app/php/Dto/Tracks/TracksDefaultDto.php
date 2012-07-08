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

        'artistName' => array(
          'vo' => 'getArtistName',
        ),

        'trackTitle' => array(
          'vo' => 'getTrackTitle',
        ),

      );
    }

  }
