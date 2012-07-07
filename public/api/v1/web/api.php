<?php

  $api = array(

    // state of gateway
    'enabled' => TRUE,

    // auth definition
    'auth' => FALSE,

    // allowed services
    'validServices' => array(

      'Web.Base.getInitialData',

      'Web.Events.getByTime',
      'Web.Events.getByUrlName',
      'Web.Events.getByVenueUrlName',
      'Web.Events.setUserRelation',
      'Web.Events.removeUserRelation',

      'Web.Artists.getByUrlName',
      'Web.Artists.getByEventUrlName',
      'Web.Artists.getTracksDataByUrlName',

      'Web.Venues.getByUrlName',

    )

  );