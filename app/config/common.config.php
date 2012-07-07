<?php

  $app = array();

  // ##########################################
  // app name

  $app['appName'] = 'beatguide';

  // ##########################################
  // active environment

  $app['environment'] = 'local';

  // ##########################################
  // third party

  $app['thirdParty'] = array(

    'ga'         => array(
      'enabled' => FALSE,
      'account' => 'UA-26262067-2',
      'domain'  => 'beatguide.me',
    ),

    'soundcloud' => array(
      'clientId'     => '3d5d9f33c9df990d4c2637494c6b2c7e',
      'clientSecret' => 'd0e7672760807fd50fdbb4f9e60a44f5',
    ),

    'facebook'   => array(
      'appId'       => '214748345258289',
      'secret'      => 'd65b884a39f4d5f574a54f8676c2184d',
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
      'domain' => 'http://local.beatguide.me',
      'public' => '',
      'api'    => '/api/v1',
      'cdn'    => NULL,
    ),

    // ----------------------------------------

    'path'       => array(
      'root'   => '/Users/fightbulc/www/apps/beatguide.me',
      'public' => '/public',
    ),

    // ----------------------------------------

    'database'        => array(

      'mysql'  => array(

        array(
          'server'   => 'localhost',
          'database' => 'beatguide',
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
      'domain' => 'http://next.beatguide.me',
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
          'database' => 'beatguide',
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