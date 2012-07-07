<?php

  spl_autoload_register('_autoLoad');
  set_error_handler('_errorHandling');
//  register_shutdown_function('_errorHandling');
  set_exception_handler('_exceptionHandling');

  // 512 MB
  ini_set('memory_limit', '536870912');

  // 30 seconds
  ini_set('max_execution_time', 30);

  // ############################################

  setlocale(LC_ALL, 'en_EN');
  date_default_timezone_set('GMT');

  // ############################################

  function _autoLoad($className)
  {
    $className = str_replace('\\', '/', $className);

//    echo "AUTOLOAD...$className\n";

    if (strpos($className, 'App/') !== FALSE)
    {
      $className = preg_replace('/^app\//i', '', $className);
      $file = __DIR__ . '/../../app/php/' . $className . '.php';

      if (file_exists($file))
      {
        require_once($file);
      }
      else
      {
        throw new \Exception('autoload require failed for...app: ' . $file);
      }
    }

    elseif (strpos($className, 'Simplon/') !== FALSE)
    {
      $className = preg_replace('/^simplon\//i', '', $className);
      $file = __DIR__ . '/' . $className . '.php';

      if (file_exists($file))
      {
        require_once($file);
      }
      else
      {
        throw new \Exception('autoload require failed for...simplon: ' . $file);
      }
    }

    elseif (strpos($className, 'Symfony/') !== FALSE)
    {
      $file = __DIR__ . '/' . $className . '.php';

      if (file_exists($file))
      {
        require_once($file);
      }
      else
      {
        throw new \Exception('autoload require failed for...symfony: ' . $file);
      }
    }
  }

  // ############################################

  function _errorHandling($errNo, $errStr, $errorFile, $errorLine)
  {
    $server = new \Simplon\Server;

    $error = array(
      'error' => array(
        'no' => $errNo,
        'message' => $errStr,
        'file' => $errorFile,
        'line' => $errorLine
      )
    );

    $server->setErrorResponse($error);
    $server->sendResponse();

    die();
  }

  // ############################################

  /**
   * @param \Exception $exception
   */
  function _exceptionHandling($exception)
  {
    $server = new \Simplon\Server;

    $error = array(
      'error' => array(
        'no' => $exception->getCode(),
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTrace()
      )
    );

    $server->setErrorResponse($error);
    $server->sendResponse();

    die();
  }