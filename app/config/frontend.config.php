<?php

  require_once __DIR__ . '/common.config.php';

  // ##########################################

  $environment = $app['environment'];

  $app = array(

    'logging'     => TRUE,

    'environment' => $environment,

    'url'         => $app[$environment]['url'],

    'path'        => array(
      'public' => $app[$environment]['path']['public'],
    ),

    'soundcloud'  => array(
      'clientId' => $app['thirdParty']['soundcloud']['clientId'],
    ),

    'facebook'    => array(
      'appId'       => $app['thirdParty']['facebook']['appId'],
      'permissions' => $app['thirdParty']['facebook']['permissions'],
    ),

    'twitter'     => $app['thirdParty']['twitter'],

    'ga'          => $app['thirdParty']['ga'],

  );

// ##########################################

  /**
   * determine which config keys
   * should be accessible for javascript
   */

  $app['public'] = array(
    'logging',
    'url',
    'path',
    'facebook',
    'soundcloud',
  );

