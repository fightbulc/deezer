<?php

  return array(
    'params' => array(

      'urlName',

    ),

    // ########################################

    'response' => array(

      'id',
      'venue_id',
      'name',
      'urlName',
      'hasCosts',
      'cost',
      'date',
      'timeStarting',
      'timeEnding',
      'isRunning',
      'daySelector',
      'genres',

      'artists' => array(
        array(
          'id',
          'urlname',
          'name',
          'country',
          'url_soundcloud',
          'url_facebook',
          'url_avatar',
          'genres',
        )
      ),

    ),

  );