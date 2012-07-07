<?php

  if(array_key_exists('_escaped_fragment_', $_GET))
  {
    // client understands javascript? back to hashbang URL
    echo '<script>window.location.href="/#!'.$_GET['_escaped_fragment_'].'";</script>';

    // else get data for crawler
    $escapedFragement = $_GET['_escaped_fragment_'];

    // custom event descriptions
    if( ! empty($escapedFragement))
    {
      $escapedFragement = ltrim($escapedFragement, '/');
      $parts = explode('/', $escapedFragement);

      if($parts[0] === 'event')
      {
        $processusCorePath = '../library/Processus/core/';
        $applicationPath = '../application/php/Application/';

        require_once($processusCorePath . 'Interfaces/InterfaceBootstrap.php');
        require_once($processusCorePath . 'Interfaces/InterfaceApplicationContext.php');
        require_once ($processusCorePath . 'ProcessusBootstrap.php');
        require_once($applicationPath . 'ApplicationBootstrap.php');

        $bootstrap = \Application\ApplicationBootstrap::getInstance();
        $bootstrap->init();

        $event = new \Application\JsonRpc\V1\Pub\Service\Event();
        $eventData = $event->filterByUrl(array('urlname' => $parts[1]));

        // get artists with avatar
        $artistsWithAvatar = array();

        foreach($eventData['artists'] as $artist)
        {
          if( ! empty($artist->url_avatar))
          {
            $artistsWithAvatar[] = $artist;
          }
        }

        shuffle($artistsWithAvatar);
        $description = NULL;

        if(count($artistsWithAvatar) > 2)
        {
          $description = 'See '.$artistsWithAvatar[0]->name.' and '.(count($artistsWithAvatar)-1).' other artists performing that night. Sounds awesome? Join them!';
        }
        elseif(count($artistsWithAvatar) == 2)
        {
          $description = $artistsWithAvatar[0]->name.' & '.$artistsWithAvatar[1]->name.' gonna be the artists for that night. Interested? Pass by and move your feet!';
        }
        else
        {
          $description = 'The one and only, '.$artistsWithAvatar[0]->name.', will spin the decks. Go and have fun!';
        }

        // set new metas
        $app['metas']['title']        = $eventData['event']->name.' at '.$eventData['venue']->name.' / '.$eventData['event']->date.' - '.$eventData['event']->time.' hrs';
        $app['metas']['description']  = $description;
        $app['metas']['image']        = $artistsWithAvatar[0]->url_avatar;
      }
    }
  }

?>