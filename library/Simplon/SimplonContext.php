<?php

  namespace Simplon;

  class SimplonContext
  {
    /**
     * @var SimplonContext
     */
    private static $_instance;

    /**
     * @var array
     */
    protected $_config = array();

    // ########################################

    /**
     * @static
     * @return SimplonContext
     */
    public static function getInstance()
    {
      if (!SimplonContext::$_instance)
      {
        SimplonContext::$_instance = new SimplonContext();
      }

      return SimplonContext::$_instance;
    }

    // ########################################

    public function getConfig()
    {
      if (!$this->_config)
      {
        $app = array();

        require __DIR__ . '/../../app/config/common.config.php';

        /**
         * get current environment
         */
        $env = $app['environment'];

        /**
         * insert appName in environment
         */
        $app[$env]['appName'] = $app['appName'];

        /**
         * only enabled environment
         */
        $this->_config = $app[$env];
      }

      return $this->_config;
    }

    // ########################################

    /**
     * @param array $keys
     * @return mixed
     * @throws \Exception
     */
    public function getConfigByKeys(array $keys)
    {
      $config = $this->getConfig();

      foreach ($keys as $key)
      {
        if (array_key_exists($key, $config))
        {
          $config = $config[$key];
        }
        else
        {
          throw new \Exception('Config key "' . implode('->', $keys) . '" doesnt exist.');
        }
      }

      return $config;
    }

    // ########################################

    /**
     * @return Vo\Config\CoreDatabaseConfigVo
     */
    public function getDatabaseConfig()
    {
      $configVo = new \Simplon\Vo\Config\CoreDatabaseConfigVo();
      $configVo->setData($this->getConfigByKeys(array('database')));
      return $configVo;
    }

    // ########################################

    /**
     * @return Vo\Config\CoreMysqlConfigVo
     */
    public function getMysqlConfig()
    {
      $databaseConfig = $this
        ->getDatabaseConfig()
        ->getMysqlConfig();

      $mysqlConfig = array_shift($databaseConfig);

      $configVo = new \Simplon\Vo\Config\CoreMysqlConfigVo();
      $configVo->setData($mysqlConfig);

      return $configVo;
    }

    // ########################################

    /**
     * @return Vo\Config\CoreCouchbaseConfigVo
     */
    public function getCouchbaseMasterConfig()
    {
      $databaseConfig = $this
        ->getDatabaseConfig()
        ->getCouchbaseConfig();

      $couchbaseConfig = array_shift($databaseConfig);

      $configVo = new \Simplon\Vo\Config\CoreCouchbaseConfigVo();
      $configVo->setData($couchbaseConfig);

      return $configVo;
    }

    // ########################################

    /**
     * @return Vo\Config\CoreThirdPartyConfigVo
     */
    public function getThirdPartyConfigVo()
    {
      return new \Simplon\Vo\Config\CoreThirdPartyConfigVo();
    }

    // ########################################

    /**
     * @return Vo\Config\CoreThirdPartyConfigVo
     */
    public function getThirdPartyConfig()
    {
      $configVo = $this->getThirdPartyConfigVo();
      $configVo->setData($this->getConfigByKeys(array('thirdParty')));
      return $configVo;
    }

    // ########################################

    /**
     * @return Vo\Config\CoreFacebookConfigVo
     */
    public function getFacebookConfig()
    {
      $thirdPartyConfigVo = $this->getThirdPartyConfig();

      $configVo = new \Simplon\Vo\Config\CoreFacebookConfigVo();
      $configVo->setData($thirdPartyConfigVo->getFacebookConfig());

      return $configVo;
    }
  }
