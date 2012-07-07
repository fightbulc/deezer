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
      'Web.Moods.getByUserId',

      // tracks
      'Web.Tracks.getByMoodName',
      'Web.Tracks.getByMoodId',
      'Web.Tracks.getByUserId',

      // memories
      'Web.Memories.createMemory',
      'Web.Memories.getByMoodName',
      'Web.Memories.getByMoodId',
      'Web.Memories.getByTrackId',
      'Web.Memories.getByUserId',
      'Web.Memories.setUpVote',
      'Web.Memories.setDownVote',
      'Web.Memories.removeVote',

      // users
      'Web.Users.getByMoodName',
      'Web.Users.getByMoodId',
      'Web.Users.getByTrackId',

    )

  );