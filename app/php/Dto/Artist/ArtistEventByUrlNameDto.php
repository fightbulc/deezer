<?php

  namespace App\Dto\Artist;

  class ArtistEventByUrlNameDto extends \Simplon\Abstracts\AbstractDto
  {
    protected function getObjects()
    {
      return array(

        'id'            => array(
          'vo' => 'getUrlName',
        ),

        'xid'           => array(
          'vo' => 'getId',
        ),

        'name'          => array(
          'vo' => 'getName',
        ),

        'genres'        => array(
          'vo' => 'getGenres',
        ),

        'urlAvatar'     => array(
          'vo'     => 'getUrlAvatar',
          'format' => function($url)
          {
            $newSize = 'large';
            return str_replace('crop', $newSize, $url);
          }
        ),

        'urlSoundcloud' => array(
          'vo' => 'getUrlSoundcloud',
        ),

        'urlFacebook'   => array(
          'vo' => 'getUrlFacebook',
        ),

      );
    }

  }
