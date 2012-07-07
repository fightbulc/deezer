<?php

  namespace Simplon\Lib\Db;

  class MemcachedLib
  {
    /**
     * @var \Memcached
     */
    private $_instance;

    // ########################################

    /**
     * @return \Memcached
     */
    public function getInstance()
    {
      return $this->_instance;
    }

    // ########################################

    /**
     * @param $server
     * @param $port
     * @param $userName
     * @param $password
     * @param $bucket
     */
    public function __construct($server, $port, $userName, $password, $bucket)
    {
      $this->_instance = new \Memcached();

      $appName = \App\AppContext::getInstance()
        ->getConfigByKeys(array('appName'));

      $this->_instance->setOption(\Memcached::OPT_PREFIX_KEY, $appName . '_');
      $this->_instance->setOption(\Memcached::SERIALIZER_JSON, TRUE);
      $this->_instance->setOption(\Memcached::OPT_COMPRESSION, FALSE);
      $this->_instance->setOption(\Memcached::OPT_CONNECT_TIMEOUT, 500);
      $this->_instance->setOption(\Memcached::OPT_POLL_TIMEOUT, 500);
      $this->_instance->setOption(\Memcached::OPT_TCP_NODELAY, TRUE);
      $this->_instance->setOption(\Memcached::OPT_NO_BLOCK, TRUE);

      if (!count($this->_instance->getServerList()))
      {
        $this->_instance->addServer($server, $port);
      }
    }

    // ########################################

    /**
     * @param string $cacheId
     * @return array
     */
    public function get($cacheId)
    {
      return $this
        ->getInstance()
        ->get($cacheId);
    }

    // ########################################

    /**
     * @param array $cacheIds
     * @return array
     */
    public function getMulti(array $cacheIds)
    {
      return $this
        ->getInstance()
        ->getMulti($cacheIds);
    }

    // ########################################

    /**
     * @param $cacheId
     * @param $data
     * @param int $expireSeconds
     * @return mixed
     */
    public function set($cacheId, $data, $expireSeconds = 0)
    {
      $this
        ->getInstance()
        ->set($cacheId, $data, $expireSeconds);
    }

    // ########################################

    /**
     * @param array $data
     * @param int $expireSeconds
     */
    public function setMulti(array $data, $expireSeconds = 1)
    {
      $this
        ->getInstance()
        ->setMulti($data, 0, $expireSeconds);
    }

    // ########################################

    /**
     * @param $cacheId
     * @return mixed
     */
    public function delete($cacheId)
    {
      return $this
        ->getInstance()
        ->delete($cacheId);
    }

    // ########################################

    /**
     * @param int $delayInSeconds
     * @return bool
     */
    public function flush($delayInSeconds = 0)
    {
      return $this
        ->getInstance()
        ->flush($delayInSeconds);
    }
  }
