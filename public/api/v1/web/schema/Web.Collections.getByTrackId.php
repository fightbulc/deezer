<?php

  return array(
    'params'   => array(

      'trackId' => 'integer',

    ),

    // ########################################

    'response' => array(

      array(
        'moods' => array(
          'amount',
          'tag',
        ),

        'tracks' => array(
          'id',
          'artistName',
          'trackTitle',
        ),

        'stories' => array(
          'id',
          'userId',
          'trackId',
          'story',
          'created',
        ),
      ),

    ),

  );