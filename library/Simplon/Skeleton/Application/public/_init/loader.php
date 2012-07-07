<?php

//  <link rel="stylesheet" href="/assets/vendor/h5bp/h5bp-reset.css">
//  <link rel="stylesheet" href="/assets/app/css/bootstrap.css">
//  <script src="/assets/vendor/modernizr/modernizr-2.5.2.min.js"></script>

  function isMaintenance()
  {
    global $app;

    if ($app['maintenance']['enabled'])
    {
    }

    return FALSE;
  }

  // ############################################

  function loadApp($bundler = FALSE)
  {
    global $app;

    // maintenance mode validation
    if (isMaintenance())
    {
      header('Location: ' . $app['url']['domain'] . '/maintenance/');
      exit;
    }

    // load app template
    $template = join('', file(__DIR__ . '/app.html'));

    // set metas
    $template = str_replace('{{CRAWLER:METAS}}', setCrawlerMetas(), $template);

    // set config vars for accessible for javascript
    $template = str_replace('{{CONFIG:PUBLIC}}', setPublicVars(), $template);

    return $template;
  }

  // ############################################

  function setPublicVars()
  {
    global $app;

    $makePublic = array();

    foreach ($app['public'] as $publicKey)
    {
      $makePublic[$publicKey] = $app[$publicKey];
    }

    return '<script>window._appConfig = ' . json_encode($makePublic) . ';</script>';
  }

  // ############################################

  function setCrawlerMetas()
  {
    global $crawler;

    $tags = array();

    foreach ($crawler as $key => $value)
    {
      if (!empty($value))
      {
        $tagElms = explode(':', $key);

        // title tag
        if ($tagElms[0] == 'title')
        {
          $tags[] = '<title>' . $value . '</title>';
        }

        // meta tags
        elseif ($tagElms[0] == 'meta')
        {
          $tags[] = '<meta name="' . $tagElms[1] . '" content="' . $value . '">';
        }

        // open graph tags
        elseif ($tagElms[0] == 'facebook')
        {
          $tags[] = '<meta property="' . $tagElms[1] . '" content="' . $value . '">';
        }

        // link tags
        elseif ($tagElms[0] == 'link')
        {
          if ($tagElms[1] == 'image')
          {
            $imageType = array_pop(explode('.', $value));
            $tags[] = '<link rel="image" type="image/' . $imageType . '" href="' . $value . '">';
          }
          elseif ($tagElms[1] == 'icon')
          {
            $imageType = array_pop(explode('.', $value));
            $tags[] = '<link rel="shortcut icon" href="' . $value . '">';
          }
        }
      }
    }

    return implode("\n", $tags);
  }