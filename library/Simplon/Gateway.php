<?php

  namespace Simplon;

  class Gateway extends \Simplon\Server
  {
    protected $namespace;
    protected $api = array();
    protected $apiVersion;
    protected $apiDomain;
    protected $apiClass;
    protected $apiMethod;
    protected $jsonRequest = array();
    protected $requestedServiceClass;

    // ##########################################

    /**
     * @param string $key
     *
     * @return bool
     */
    private function _apiHasKey($key)
    {
      return array_key_exists($key, $this->api);
    }

    // ##########################################

    protected function decodeJsonRequest()
    {
      $requestContent = $this
        ->getRequestHandle()
        ->getContent();

      $this->jsonRequest = \Simplon\Lib\Helper\FormatHelper::jsonDecode($requestContent);
    }

    // ##########################################

    /**
     * @return bool
     */
    private function isEnabled()
    {
      if ($this->_apiHasKey('enabled') === FALSE)
      {
        return FALSE;
      }

      return $this->api['enabled'] === TRUE ? TRUE : FALSE;
    }

    // ##########################################

    /**
     * @return bool
     */
    private function hasValidServices()
    {
      if ($this->_apiHasKey('validServices') === FALSE)
      {
        return FALSE;
      }

      return (is_array($this->api['validServices']) && count($this->api['validServices']) > 0) ? TRUE : FALSE;
    }

    // ##########################################

    /**
     * @param array $definitions
     */
    public function setApi(array $definitions)
    {
      $this->api = $definitions;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function isValidJsonRpcRequest()
    {
      // needs to be POST method
      if (strtolower($this
        ->getRequestHandle()
        ->getMethod()) != 'post'
      )
      {
        return FALSE;
      }

      // ID not empty
      if (array_key_exists('id', $this->jsonRequest) === FALSE || empty($this->jsonRequest['id']) === TRUE)
      {
        return FALSE;
      }

      // method not empty
      if (array_key_exists('method', $this->jsonRequest) === FALSE || empty($this->jsonRequest['method']) === TRUE)
      {
        return FALSE;
      }

      // params
      if (array_key_exists('params', $this->jsonRequest) === FALSE)
      {
        return FALSE;
      }

      return TRUE;
    }

    // ##########################################

    protected function isValidService()
    {
      // method needs to be listed in API definition
      if (in_array($this->jsonRequest['method'], $this->api['validServices']) === FALSE)
      {
        return FALSE;
      }

      return TRUE;
    }

    // ##########################################

    protected function setApiVersion()
    {
      $namespaceParts = explode('\\', $this->namespace);
      $this->apiVersion = $namespaceParts[2];
    }

    // ##########################################

    /**
     * @param string $api
     */
    protected function initApi($api)
    {
      // set api
      $this->setApi($api);

      // determine api version
      $this->setApiVersion();
    }

    // ##########################################

    protected function validateRequest()
    {
      // get jsonRequest
      $this->decodeJsonRequest();

      // we need a valid request
      if(!is_array($this->jsonRequest) || !array_key_exists('id', $this->jsonRequest) || !array_key_exists('method', $this->jsonRequest) || !array_key_exists('params', $this->jsonRequest))
      {
        throw new \Exception('Invalid JSONRPC request.');
      }

      // populate request elements
      list($this->apiDomain, $this->apiClass, $this->apiMethod) = explode('.', $this->jsonRequest['method']);

      // API gateway needs to be enabled
      if ($this->isEnabled() === FALSE)
      {
        $this->throwException('API is not enabled');
      }

      // we need defined services
      if ($this->hasValidServices() === FALSE)
      {
        $this->throwException('API is not defined');
      }

      // is correct jsonRPC format
      if ($this->isValidJsonRpcRequest() === FALSE)
      {
        $this->throwException('Malformed service request');
      }

      // is valid service
      if ($this->isValidService() === FALSE)
      {
        $this->throwException('Invalid service request');
      }
    }

    // ##########################################

    protected function instantiateServiceClass()
    {
      $classPath = __DIR__ . '/../../app/php/JsonRpc/' . $this->apiVersion . '/' . $this->apiDomain . '/Service/' . $this->apiClass . '.php';

      // class file exists
      if (file_exists($classPath) === FALSE)
      {
        $this->throwException('Service class failure #1');
      }

      // instantiate service class
      $serviceClassName = '\App\\JsonRpc\\' . $this->apiVersion . '\\' . $this->apiDomain . '\\Service\\' . $this->apiClass;
      $this->requestedServiceClass = new $serviceClassName();
      $classMethods = get_class_methods($this->requestedServiceClass);

      // check if method exists in class
      if (in_array($this->apiMethod, $classMethods) === FALSE)
      {
        $this->throwException('Service class failure #2');
      }
    }

    // ##########################################

    /**
     * @return array
     */
    protected function getRequestParams()
    {
      $params = $this->jsonRequest['params'][0];
      return $params;
    }

    // ##########################################

    protected function validateServiceClass()
    {
      // has auth
      if ($this->api['auth'] === TRUE)
      {
        $authClassName = '\App\\JsonRpc\\' . $this->apiVersion . '\\' . $this->apiDomain . '\Auth';
        $params = $this->getRequestParams();
        new $authClassName($params);
      }
    }

    // ##########################################

    protected function runServiceClass()
    {
      // instantiate service class
      $this->instantiateServiceClass();

      $method = $this->apiMethod;
      $params = $this->getRequestParams();

      // call requested method
      $serviceClassResponse = $this->requestedServiceClass->$method($params);

      // set response
      $this->setValidResponse($serviceClassResponse);
      $this->sendResponse();
    }

    // ##########################################

    /**
     * @param array $api
     */
    public function load(array $api)
    {
      $this->initApi($api);

      $this->validateRequest();

      $this->validateServiceClass();

      $this->runServiceClass();
    }
  }