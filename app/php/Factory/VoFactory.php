<?php

  namespace App\Factory;

  class VoFactory
  {
    /**
     * @static
     * @param array $objects
     * @param \Simplon\Abstracts\AbstractVo $vo
     * @return array
     */
    static function factory(array $objects, \Simplon\Abstracts\AbstractVo $vo)
    {
      $factoriesVo = array();

      foreach($objects as $object)
      {
        $vo->setData($object);
        $factoriesVo[] = clone $vo;
      }

      return $factoriesVo;
    }

    // ##########################################

    /**
     * @static
     * @param array $object
     * @param \Simplon\Abstracts\AbstractVo $vo
     * @return \Simplon\Abstracts\AbstractVo
     */
    static function singleFactory(array $object, \Simplon\Abstracts\AbstractVo $vo)
    {
      $vo->setData($object);
      return $vo;
    }
  }