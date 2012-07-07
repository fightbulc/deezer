<?php

  function isMaintenance()
  {
    global $app;

    if ($app['maintenance']['enabled'])
    {
      return TRUE;
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

    $templatePath = __DIR__ . '/app.html';

    if ($app['environment'] != 'local')
    {
      $templatePath = __DIR__ . '/../bundles/' . $app['environment'] . '/app.html';
    }

    // load app template
    $template = join('', file($templatePath));

    // set metas
    $template = str_replace('{{CRAWLER:METAS}}', setCrawlerMetas(), $template);

    // set google analytics init
    $template = str_replace('{{GA:INIT}}', initGA(), $template);

    // set google analytics loader
    $template = str_replace('{{GA:LOAD}}', loadGA(), $template);

    // set config vars for accessible for javascript
    $template = str_replace('{{CONFIG:PUBLIC}}', setPublicVars(), $template);

    // replace template config placeholders
    $template = replaceConfigPlaceholders($template);

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
        elseif (in_array($tagElms[0], array(
          'og',
          'fb'
        ))
        )
        {
          if(is_array($value))
          {
            foreach($value as $v)
            {
              $tags[] = '<meta property="' . $key . '" content="' . $v . '">';
            }
          }
          else
          {
            $tags[] = '<meta property="' . $key . '" content="' . $value . '">';
          }
        }

        // link tags
        elseif ($tagElms[0] == 'link')
        {
          $fileParts = explode('.', $value);

          if ($tagElms[1] == 'image')
          {
            $imageType = array_pop($fileParts);
            $tags[] = '<link rel="image" type="image/' . $imageType . '" href="' . $value . '">';
          }
          elseif ($tagElms[1] == 'icon')
          {
            $tags[] = '<link rel="shortcut icon" href="' . $value . '">';
          }
        }
      }
    }

    return implode("\n", $tags);
  }

// ############################################

  function replaceConfigPlaceholders($template)
  {
    global $app;

    // replace via config
    $template = recursiveReplacement($app, $template);

    // clean left placeholders
    $template = preg_replace('/{{.*?}}/i', '', $template);

    return $template;
  }

// ############################################

  function recursiveReplacement($array, $template, $parentKey = NULL)
  {
    foreach ($array as $key => $val)
    {
      if (is_array($val))
      {
        $template = recursiveReplacement($val, $template, $key);
      }
      else
      {
        $template = str_replace('{{' . strtoupper($parentKey . ':' . $key) . '}}', $val, $template);
      }
    }

    return $template;
  }

// ############################################

  function initGA()
  {
    global $app;

    if ($app['ga']['enabled'] == TRUE)
    {
      if ($app['environment'] != 'local')
      {
        return "
<script>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '" . $app['ga']['account'] . "']);
  _gaq.push(['_setDomainName', '" . $app['ga']['domain'] . "']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_trackEvent']);
  _gaq.push(['_trackPageLoadTime']);
</script>
        ";
      }
      else
      {
        return "
<script>
  var _gaq = _gaq || [];
</script>
        ";
      }
    }
  }

// ############################################

  function loadGA()
  {
    global $app;

    if ($app['ga']['enabled'] == TRUE && $app['environment'] != 'local')
    {
      return "
<script>
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
      ";
    }
  }
