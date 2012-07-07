<?php

  namespace Simplon\Lib\Db;

  class DbCacheQuery extends \Simplon\Abstracts\AbstractClass
  {
    /**
     * @var bool
     */
    protected $useCache = FALSE;

    /**
     * Cache invalidation in seconds
     *
     * @var int
     */
    protected $cacheExpiration = 0; // = persistent

    /**
     * @var string
     */
    protected $cacheId;

    /**
     * @var bool
     */
    protected $cacheReIndex;

    /**
     * @var string
     */
    protected $sqlTable;

    /**
     * @var string
     */
    protected $sqlQuery;

    /**
     * @var bool
     */
    protected $sqlInsertIgnore = FALSE;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $sqlConditions = array();

    // ##########################################

    /**
     * @param bool $useCache
     * @return DbCacheQuery
     */
    public function setCacheUse($useCache)
    {
      $this->useCache = $useCache;
      return $this;
    }

    /**
     * @return bool
     */
    public function getUseCache()
    {
      return $this->useCache;
    }

    // ##########################################

    /**
     * @param $cacheExpires int
     * @return DbCacheQuery
     */
    public function setCacheExpiration($cacheExpires)
    {
      $this->cacheExpiration = $cacheExpires;
      return $this;
    }

    /**
     * @return int
     */
    public function getCacheExpiration()
    {
      return $this->cacheExpiration;
    }

    // ##########################################

    /**
     * @param $cacheId
     * @return DbCacheQuery
     */
    public function setCacheId($cacheId)
    {
      $this->cacheId = $cacheId;
      return $this;
    }

    /**
     * @return string
     */
    public function getCacheId()
    {
      return $this->cacheId;
    }

    // ##########################################

    /**
     * @param $sqlConditions array
     * @return DbCacheQuery
     */
    public function setSqlConditions($sqlConditions)
    {
      $this->sqlConditions = $sqlConditions;
      return $this;
    }

    /**
     * @return array
     */
    public function getSqlConditions()
    {
      return $this->sqlConditions;
    }

    /**
     * @return bool
     */
    public function hasSqlConditions()
    {
      return count($this->getSqlConditions()) > 0 ? TRUE : FALSE;
    }

    // ##########################################

    /**
     * @param $sqlQuery string
     * @return DbCacheQuery
     */
    public function setSqlQuery($sqlQuery)
    {
      $this->sqlQuery = $sqlQuery;
      return $this;
    }

    /**
     * @return string
     */
    public function getSqlQuery()
    {
      return $this->sqlQuery;
    }

    // ##########################################

    /**
     * @param array $data
     * @return DbCacheQuery
     */
    public function setData($data)
    {
      $this->data = $data;
      return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
      return $this->data;
    }

    // ##########################################

    /**
     * @param $sqlTable string
     * @return DbCacheQuery
     */
    public function setSqlTable($sqlTable)
    {
      $this->sqlTable = $sqlTable;
      return $this;
    }

    /**
     * @return string
     */
    public function getSqlTable()
    {
      return $this->sqlTable;
    }

    // ##########################################

    /**
     * @param $cacheReIndex
     * @return DbCacheQuery
     */
    public function setCacheReIndex($cacheReIndex)
    {
      $this->cacheReIndex = $cacheReIndex;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getCacheReIndex()
    {
      return $this->cacheReIndex;
    }

    // ##########################################

    /**
     * @param $sqlInsertIgnore
     * @return DbCacheQuery
     */
    public function setSqlInsertIgnore($sqlInsertIgnore)
    {
      $this->sqlInsertIgnore = $sqlInsertIgnore;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getSqlInsertIgnore()
    {
      return $this->sqlInsertIgnore;
    }
  }
