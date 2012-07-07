<?php

  namespace Simplon\Abstracts;

  class AbstractClass
  {
    /**
     * @return \Simplon\SimplonContext
     */
    protected function getSimplonContext()
    {
      return \Simplon\SimplonContext::getInstance();
    }

    // ########################################

    /**
     * @return array
     */
    protected function getConfig()
    {
      return $this->getSimplonContext()->getConfig();
    }

    // ########################################

    /**
     * @param array $keys
     * @return array
     */
    protected function getConfigByKey($keys)
    {
      return $this->getSimplonContext()->getConfigByKeys($keys);
    }

    // ########################################

    /**
     * @param $message
     * @throws \Exception
     */
    protected function throwException($message)
    {
      throw new \Exception($message);
      die();
    }
  }
