<?php

  namespace App\Manager\Artists;

  class SoundcloudManager extends \Simplon\Abstracts\AbstractCacheQueryManager
  {
    /**
     * @param array $soundcloudUrlParts
     * @return null|string
     */
    protected function _determineSoundcloudUrlType(array $soundcloudUrlParts)
    {
      $urlPartsCount = count($soundcloudUrlParts);
      $urlType = NULL;

      switch ($urlPartsCount)
      {
        case 1:
          $urlType = \App\Konstant\ArtistsSoundcloudUrlType::IS_PROFILE;
          break;

        case 2:
          $urlType = \App\Konstant\ArtistsSoundcloudUrlType::IS_TRACK;
          break;

        case 3:
          $urlType = \App\Konstant\ArtistsSoundcloudUrlType::IS_SET;
          break;

        default:
          break;
      }

      return $urlType;
    }

    // ##########################################

    /**
     * @param $apiUrl
     * @param $placeholderVars
     * @return mixed
     */
    protected function _renderSoundcloudApiPlaceholders($apiUrl, $placeholderVars)
    {
      foreach ($placeholderVars as $key => $val)
      {
        $apiUrl = str_replace('{{' . $key . '}}', $val, $apiUrl);
      }

      return $apiUrl;
    }

    // ##########################################

    /**
     * @param array $soundcloudUrlParts
     * @param $urlType
     * @return mixed
     */
    protected function _getSoundcloudApiUrl(array $soundcloudUrlParts, $urlType)
    {
      $clientId = \App\AppContext::getInstance()
        ->getSoundcloudConfig()
        ->getClientId();

      // set apiUrl placeholders
      $urlPlaceholders = array(
        'clientId'    => $clientId,
        'userUrlName' => $soundcloudUrlParts[0],
      );

      switch ($urlType)
      {
        case 'profile':
          $apiUrl = \App\Konstant\ArtistsSoundcloudApiUrls::PROFILE_URL;
          break;

        case 'track':
          $apiUrl = \App\Konstant\ArtistsSoundcloudApiUrls::TRACK_URL;
          $urlPlaceholders['trackUrlName'] = $soundcloudUrlParts[1];
          break;

        case 'set':
          $apiUrl = \App\Konstant\ArtistsSoundcloudApiUrls::SET_URL;
          $urlPlaceholders['setUrlName'] = $soundcloudUrlParts[2];
          break;

        default:
          $apiUrl = NULL;
          break;
      }

      // render url and return that shit
      return $this->_renderSoundcloudApiPlaceholders($apiUrl, $urlPlaceholders);
    }

    // ##########################################

    /**
     * @param $apiUrl
     * @return array
     */
    protected function _requestSoundcloudApi($apiUrl)
    {
      $rawResponse = \Simplon\Vendor\CURL\CURL::init($apiUrl)
        ->setReturnTransfer(TRUE)
        ->execute();

      return \Simplon\Lib\Helper\FormatHelper::jsonDecode($rawResponse);
    }

    // ##########################################

    /**
     * @param $data
     * @return \App\Vo\ArtistTracksDataVo
     */
    protected function _getSoundcloudApiTrackVo($data)
    {
      $vo = new \App\Vo\ArtistTracksDataVo();
      $vo->setData($data);
      return $vo;
    }

    // ##########################################

    /**
     * @param $tracks
     * @param $urlType
     * @return array
     */
    protected function _getStreamableTracksVo($tracks, $urlType)
    {
      $tracksVo = array();

      $multipleTracksType = array(
        'profile',
        'set',
      );

      if (in_array($urlType, $multipleTracksType))
      {
        // for sets tracks are embedded
        if ($urlType == 'set')
        {
          $tracks = $tracks['tracks'];
        }

        if(!empty($tracks))
        {
          foreach ($tracks as $track)
          {
            $vo = $this->_getSoundcloudApiTrackVo($track);

            if ($vo->getIsStreamable())
            {
              $tracksVo[] = $vo;
            }
          }
        }
      }
      else
      {
        $tracksVo[] = $this->_getSoundcloudApiTrackVo($tracks);
      }

      return $tracksVo;
    }

    // ##########################################

    /**
     * @param \App\Vo\ArtistVo $artistVo
     * @return \App\Vo\ArtistTracksDataVo
     */
    public function getSoundcloudTracksData(\App\Vo\ArtistVo $artistVo)
    {
      // soundcloud url parts
      $soundcloudUrlParts = $artistVo->getUrlSoundcloudParts($artistVo);

      // determine soundcloud urlType
      $urlType = $this->_determineSoundcloudUrlType($soundcloudUrlParts);

      // get soundcloud api based on urlType
      $apiUrl = $this->_getSoundcloudApiUrl($soundcloudUrlParts, $urlType);

      // lets call soundcloud with our new api url. right away!
      $tracks = $this->_requestSoundcloudApi($apiUrl);

      // get correct track dto's
      $tracksVo = $this->_getStreamableTracksVo($tracks, $urlType);

      return $tracksVo;
    }
  }
