<?php

  namespace App\Konstant;

  class ArtistsSoundcloudApiUrls
  {
    const PROFILE_URL = 'http://api.soundcloud.com/users/{{userUrlName}}/tracks.json?client_id={{clientId}}';
    const TRACK_URL = 'http://api.soundcloud.com/users/{{userUrlName}}/tracks/{{trackUrlName}}.json?client_id={{clientId}}';
    const SET_URL = 'http://api.soundcloud.com/users/{{userUrlName}}/playlists/{{setUrlName}}.json?client_id={{clientId}}';
  }
