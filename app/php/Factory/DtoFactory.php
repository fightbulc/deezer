<?php

  namespace App\Factory;

  class DtoFactory
  {
    /**
     * @static
     * @param array $vos
     * @param \Simplon\Abstracts\AbstractDto $dto
     * @param null $prefixKey
     * @return array
     */
    static function factory(array $vos, \Simplon\Abstracts\AbstractDto $dto, $prefixKey = NULL)
    {
      $factoriesDto = array();

      foreach ($vos as $vo)
      {
        /** @var $vo \Simplon\Abstracts\AbstractVo */

        $dtoResponse = $vo->export($dto);

        // in case we want an key prefixed array
        if (!is_null($prefixKey))
        {
          $dtoResponse = array($prefixKey => $dtoResponse);
        }

        $factoriesDto[] = $dtoResponse;
      }

      return $factoriesDto;
    }

    // ##########################################

    /**
     * @static
     * @param \Simplon\Abstracts\AbstractVo $vo
     * @param \Simplon\Abstracts\AbstractDto $dto
     * @return array
     */
    static function singleFactory(\Simplon\Abstracts\AbstractVo $vo, \Simplon\Abstracts\AbstractDto $dto)
    {
      return $vo->export($dto);
    }
  }