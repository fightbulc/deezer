<?php

  if (array_key_exists('_escaped_fragment_', $_GET))
  {
    // client understands javascript? back to hashbang URL
    // echo '<script>window.location.href="/#!' . $_GET['_escaped_fragment_'] . '";</script>';

    // else get data for crawler
    $escapedFragement = $_GET['_escaped_fragment_'];

    // custom event descriptions
    if (!empty($escapedFragement))
    {
      $escapedFragement = ltrim($escapedFragement, '/');
      $urlNameParts = explode('/', $escapedFragement);

      if ($urlNameParts[0] === 'event')
      {
        require(__DIR__ . '/../../library/Simplon/Bootstrap.php');

        // create request array
        $request = array('urlName' => $urlNameParts[1]);

        // get event details
        $requestEventVo = new \App\Request\Events\rGetByUrlName();
        $requestEventVo->setData($request);

        $eventsManager = new \App\Manager\Events\EventsManager();

        /** @var $eventVo \App\Vo\EventVo */
        $eventVo = $eventsManager->getByUrlName($requestEventVo);

        // get venue details
        $requestVenueVo = new \App\Request\Venues\rGetByUrlName();
        $requestVenueVo->setData(array('urlName' => $eventVo->getVenueUrlName()));

        $venueManager = new \App\Manager\Venues\VenuesManager();
        $venueVo = $venueManager->getByUrlName($requestVenueVo);

        // get artists details
        $requestArtistVo = new \App\Request\Artists\rGetByEventUrlName();
        $requestArtistVo->setData($request);

        $artistsManager = new \App\Manager\Artists\ArtistsManager();
        $artistsVo = $artistsManager->getByEventUrlName($requestArtistVo);

        // get artists with avatar
        $artistsVoWithAvatar = array();

        foreach ($artistsVo as $artist)
        {
          /** @var $artist \App\Vo\ArtistVo */

          $avatarImage = $artist->getUrlAvatar();

          if (!empty($avatarImage))
          {
            $artistsVoWithAvatar[] = $artist;
          }
        }

        shuffle($artistsVoWithAvatar);
        $description = NULL;

        /** @var $firstArtist \App\Vo\ArtistVo */
        $firstArtist = $artistsVoWithAvatar[0];

        /** @var $secondArtist \App\Vo\ArtistVo */
        $secondArtist = $artistsVoWithAvatar[1];

        if (count($artistsVoWithAvatar) > 2)
        {
          $description = 'See ' . $firstArtist->getName() . ' and ' . (count($artistsVoWithAvatar) - 1) . ' other artists performing that night. Sounds awesome? Join them!';
        }
        elseif (count($artistsVoWithAvatar) == 2)
        {
          $description = $firstArtist->getName() . ' & ' . $secondArtist->getName() . ' gonna be the artists for that night. Interested? Pass by and move your feet!';
        }
        else
        {
          $description = 'The one and only, ' . $firstArtist->getName() . ', will spin the decks. Go and have fun!';
        }

        $startDateTime = new DateTime();
        $startDateTime->setTimestamp($eventVo->getDateTimeStart());

        $startDate = $startDateTime->format('D, d.m');
        $startTime = $startDateTime->format('H:i');

        $title = $eventVo->getName() . ' at ' . $venueVo->getName() . ' / ' . $startDate . ' - ' . $startTime . ' hrs';
        $avatarImage = $firstArtist->getUrlAvatar();
        $avatarImage = str_replace('crop', 't300x300', $avatarImage);

        $avatarImage2 = $secondArtist->getUrlAvatar();
        $avatarImage2 = str_replace('crop', 't300x300', $avatarImage2);

        // set new metas
        $crawler['title'] = $title;
        $crawler['meta:description'] = $description;
        $crawler['link:image'] = $avatarImage;

        $crawler['og:title'] = $title;
        $crawler['og:description'] = $description;
        $crawler['og:image'] = $avatarImage;
        $crawler['og:image'] = $avatarImage2;
        $crawler['og:url'] = 'http://next.beatguide.me/#!/' . $escapedFragement;
      }
    }
  }