<?php

  namespace App\Dto\Artist;

  class ArtistTracksDataDefaultDto extends \Simplon\Abstracts\AbstractDto
  {
    protected function getObjects()
    {
      return array(

        'id'          => array(
          'vo' => 'getId',
        ),

        'title'       => array(
          'vo' => 'getTitle',
        ),

        'durationMs'  => array(
          'vo' => 'getDuration',
        ),

        'waveformUrl' => array(
          'vo' => 'getWaveformUrl',
        ),

        'artworkUrl'  => array(
          'vo' => 'getArtworkUrl',
          'format' => function($url)
          {
            $newSize = 'crop';
            return str_replace('large', $newSize, $url);
          }
        ),

        'streamUrl'   => array(
          'vo'     => 'getStreamUrl',
          'format' => function($streamUrl)
          {
            $soundcloudClientId = \App\AppContext::getInstance()
              ->getSoundcloudConfig()
              ->getClientId();

            return $streamUrl . '/?client_id=' . $soundcloudClientId;
          }
        ),

      );
    }

  }
