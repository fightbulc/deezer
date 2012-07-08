<?php

  $api = array(

    // state of gateway
    'enabled' => TRUE,

    // auth definition
    'auth' => FALSE,

    // allowed services
    'validServices' => array(

      // collections
      'Web.Collections.getByTrackId',
      'Web.Collections.getByMoodTag',

      // moods
      'Web.Moods.getByTrackId',
//      'Web.Moods.getByUserId',

      // tracks
      'Web.Tracks.getByMoodTag',
//      'Web.Tracks.getByUserId',

      // memories
      'Web.Memories.createMemory',
      'Web.Memories.getByTrackId',
      'Web.Memories.setUpVote',
      'Web.Memories.setDownVote',
      'Web.Memories.removeVote',
//      'Web.Memories.getByMoodName',
//      'Web.Memories.getByUserId',

      // story
      'Web.Stories.createStory',
      'Web.Stories.getByTrackId',
//      'Web.Stories.getByUserId',

      // users
//      'Web.Users.getByMoodName',
//      'Web.Users.getByTrackId',

    )

  );