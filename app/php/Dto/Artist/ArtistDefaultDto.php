<?php

  namespace App\Dto\Artist;

  class ArtistDefaultDto extends \Simplon\Abstracts\AbstractDto
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

        'urlName' => array(
          'vo' => 'getUrlName',
        ),

        'genres' => array(
          'vo' => 'getGenres',
        ),

      );
    }

  }
