<?php

  $api = array(

    // state of gateway
    'enabled' => TRUE,

    // auth definition
    'auth' => FALSE,

    // allowed services
    'validServices' => array(

      // moods
      'Web.Moods.getByTrackId',

      // tracks
      'Web.Tracks.getByMoodName',
      'Web.Tracks.getByMoodId',

      // users
      'Web.Users.getByMoodName',
      'Web.Users.getByTrackId',

    )

  );