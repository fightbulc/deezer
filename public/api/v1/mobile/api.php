<?php

  $api = array(

    // state of gateway
    'enabled' => TRUE,

    // auth definition
    'auth' => TRUE,

    // allowed services
    'validServices' => array(

      'Mobile.Base.getInitialData',

      'Mobile.Events.getByTime',
      'Mobile.Events.getByUserId',
      'Mobile.Events.getByUrlName',
      'Mobile.Events.getByVenueUrlName',
      'Mobile.Events.setUserRelation',
      'Mobile.Events.removeUserRelation',

      'Mobile.Artists.getByEventUrlName',
      'Mobile.Artists.getByUrlName',
      'Mobile.Artists.getTracksDataByUrlName',

      'Mobile.Venues.getByUrlName',

    )

  );