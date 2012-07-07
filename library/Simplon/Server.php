<?php

  namespace Simplon;

  class Server
  {
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $_requestHandle;

    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $_responseHandle;

    // ##########################################

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequestHandle()
    {
      if (isset($this->_requestHandle) === FALSE)
      {
        $this->_requestHandle = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
      }

      return $this->_requestHandle;
    }

    // ##########################################

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResponseHandle()
    {
      if (isset($this->_responseHandle) === FALSE)
      {
        $this->_responseHandle = new \Symfony\Component\HttpFoundation\Response();
      }

      return $this->_responseHandle;
    }

    // ##########################################

    /**
     * @param string $type
     */
    protected function setResponseContentType($type = 'json')
    {
      $contentTypes = array(
        'json' => 'application/json',
        'html' => 'text/html',
      );

      if (array_key_exists($type, $contentTypes) === FALSE)
      {
        $type = 'json';
      }

      $this->getResponseHandle()->headers->set('Content-Type', $contentTypes[$type]);
    }

    // ##########################################

    /**
     * @param string $code
     */
    protected function setResponseStatusCode($code = '200')
    {
      $this
        ->getResponseHandle()
        ->setStatusCode($code);
    }

    // ##########################################

    /**
     * @param string $charset
     */
    protected function setResponseCharset($charset = '')
    {
      if (isset($charset) === FALSE)
      {
        $charset = 'utf-8';
      }

      $this
        ->getResponseHandle()
        ->setCharset($charset);
    }

    // ##########################################

    /**
     * @param array $content
     */
    protected function setResponseContent(array $content)
    {
      $response = array(
        'id'     => 1,
        'result' => $content
      );

      $json = \Simplon\Lib\Helper\FormatHelper::jsonEncode($response);

      $this
        ->getResponseHandle()
        ->setContent($json);
    }

    // ##########################################

    /**
     * @param array $response
     */
    public function setValidResponse(array $response)
    {
      $this->setResponseStatusCode('200');
      $this->setResponseContentType('json');
      $this->setResponseContent($response);
    }

    // ##########################################

    /**
     * @param array $error
     */
    public function setErrorResponse(array $error)
    {
      $this->setResponseStatusCode('500');
      $this->setResponseContentType('json');
      $this->setResponseContent($error);
    }

    // ##########################################

    public function sendResponse()
    {
      $this->setResponseCharset('utf-8');

      $this
        ->getResponseHandle()
        ->send();
    }

    // ##########################################

    /**
     * @param $errorMessage
     * @throws \Exception
     */
    protected function throwException($errorMessage)
    {
      throw new \Exception($errorMessage);
    }
  }