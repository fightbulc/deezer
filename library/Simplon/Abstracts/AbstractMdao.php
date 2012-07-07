<?php

  namespace Simplon\Abstracts;

  class AbstractMdao extends AbstractVo
  {
    /**
     * @var string
     */
    public $_cacheId;

    // ##########################################

    /**
     * @return \Simplon\Lib\Db\CouchbaseLib
     */
    protected function getCouchbaseInstance()
    {
      return \Simplon\Lib\Db\DbFactory::Couchbase();
    }

    // ##########################################

    /**
     * @param string $cacheId
     */
    public function setCacheId($cacheId)
    {
      $appName = $this->getConfigByKey(array('appName'));
      $this->_cacheId = $appName . '_' . $cacheId;
    }

    /**
     * @return string
     */
    public function getCacheId()
    {
      return $this->_cacheId;
    }

    // ##########################################

    /**
     * @return array
     */
    public function getFromCache()
    {
      $data = $this
        ->getCouchbaseInstance()
        ->get($this->getCacheId());

      $this->setData($data);
    }

    /**
     * @return string
     */
    public function save()
    {
      return $this
        ->getCouchbaseInstance()
        ->set($this->getCacheId(), $this->getData());
    }
  }
