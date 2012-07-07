<?php

  namespace Simplon\Lib\Db;

  class DbFactory
  {
    /**
     * @var \Simplon\SimplonContext
     */
    private static $_simplonContext;

    /**
     * @var MySQL
     */
    private static $_mysqlInstance;

    /**
     * @var CouchbaseLib
     */
    private static $_couchbaseInstance;

    /**
     * @var MemcachedLib
     */
    private static $_memcacheInstance;

    // ########################################

    /**
     * @static
     * @return \Simplon\SimplonContext
     */
    public static function getSimplonContext()
    {
      if (!DbFactory::$_simplonContext)
      {
        DbFactory::$_simplonContext = \Simplon\SimplonContext::getInstance();
      }

      return DbFactory::$_simplonContext;
    }

    // ########################################

    /**
     * @static
     * @return \Simplon\Vendor\EasyPDO\EasyPDO
     */
    public static function MySQL()
    {
      if (!DbFactory::$_mysqlInstance)
      {
        $configVo = \App\AppContext::getInstance()
          ->getMysqlConfig();

        DbFactory::$_mysqlInstance = MySQL::Instance($configVo->getServer(), $configVo->getDatabase(), $configVo->getUserName(), $configVo->getPassword());
      }

      return DbFactory::$_mysqlInstance;
    }

    // ########################################

    /**
     * @static
     * @return CouchbaseLib
     */
    public static function Couchbase()
    {
      if (!DbFactory::$_couchbaseInstance)
      {
        $configVo = \App\AppContext::getInstance()
          ->getCouchbaseMasterConfig();

        DbFactory::$_couchbaseInstance = new CouchbaseLib($configVo->getServer(), $configVo->getPort(), $configVo->getUserName(), $configVo->getPassword(), $configVo->getBucket());
      }

      return DbFactory::$_couchbaseInstance;
    }

    // ########################################

    /**
     * @static
     * @return MemcachedLib
     */
    public static function Memcached()
    {
      if (!DbFactory::$_memcacheInstance)
      {
        $configVo = \App\AppContext::getInstance()
          ->getCouchbaseMasterConfig();

        DbFactory::$_memcacheInstance = new MemcachedLib($configVo->getServer(), $configVo->getPort(), $configVo->getUserName(), $configVo->getPassword(), $configVo->getBucket());
      }

      return DbFactory::$_memcacheInstance;
    }
  }
