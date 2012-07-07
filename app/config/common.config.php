<?php

  $app = array();

  // ##########################################
  // app name

  $app['appName'] = 'deezer';

  // ##########################################
  // active environment

  $app['environment'] = 'local';

  // ##########################################
  // third party

  $app['thirdParty'] = array(

    'ga'         => array(
      'enabled' => FALSE,
      'account' => 'UA-26262067-2',
      'domain'  => 'deezer.efides.com',
    ),

    'deezer' => array(
      'clientId' => '103961',
      'clientSecret' => '1d19176572e51c4830ce4dbd10878737',
    ),

    'soundcloud' => array(
      'clientId' => '',
    ),

    'facebook'   => array(
      'appId'       => '',
      'secret'      => '',
      'permissions' => array(
        'email',
        'publish_stream'
      )
    ),

    'twitter'    => array(),

  );

  // ##########################################
  // environment: local

  $app['local'] = array(

    'url'        => array(
      'domain' => 'http://local.deezer.efides.com',
      'public' => '',
      'api'    => '/api/v1',
      'cdn'    => NULL,
    ),

    // ----------------------------------------

    'path'       => array(
      'root'   => '/Users/fightbulc/www/apps/deezer',
      'public' => '/public',
    ),

    // ----------------------------------------

    'database'        => array(

      'mysql'  => array(

        array(
          'server'   => 'localhost',
          'database' => 'deezer',
          'username' => 'root',
          'password' => 'root'
        )

      ),

      'couchbase' => array(

        array(
          'server'   => '127.0.0.1',
          'port'     => '11211',
          'username' => 'rootuser',
          'password' => 'rootuser',
          'bucket'   => 'default',
        )

      ),

    ),

    // ----------------------------------------

    'thirdParty' => $app['thirdParty']

  );

  // ##########################################
  // environment: mirror

  $app['mirror'] = array();

  // ##########################################
  // environment: live

  $app['production'] = array(

    'url'        => array(
      'domain' => 'http://deezer.efides.com',
      'public' => '',
      'api'    => '/api/v1',
      'cdn'    => NULL,
    ),

    // ----------------------------------------

    'path'       => array(
      'root'   => '/home/beatguide/www/beatguide.me',
      'public' => '/public',
    ),

    // ----------------------------------------

    'database'        => array(

      'mysql'  => array(

        array(
          'server'   => 'localhost',
          'database' => 'deezer',
          'username' => 'root',
          'password' => '$%beatguide2012'
        )

      ),

      'couchbase' => array(

        array(
          'server'   => '127.0.0.1',
          'port'     => '11211',
          'username' => 'rootbeatguide',
          'password' => '$%beatguide2012',
          'bucket'   => 'default',
        )

      ),

    ),

    // ----------------------------------------

    'thirdParty' => $app['thirdParty']

  );