<?php
  /**
   * *** BEGIN LICENSE BLOCK *****
   * This file is part of EasyPDO (http://easypdo.robpoyntz.com/).
   * Software License Agreement (New BSD License)
   * Copyright (c) 2010, Robert Poyntz
   * All rights reserved.
   * Redistribution and use in source and binary forms, with or without modification,
   * are permitted provided that the following conditions are met:
   *     * Redistributions of source code must retain the above copyright notice,
   *       this list of conditions and the following disclaimer.
   *     * Redistributions in binary form must reproduce the above copyright notice,
   *       this list of conditions and the following disclaimer in the documentation
   *       and/or other materials provided with the distribution.
   *     * Neither the name of Robert Poyntz nor the names of its
   *       contributors may be used to endorse or promote products derived from this
   *       software without specific prior written permission.
   * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
   * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
   * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
   * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
   * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
   * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
   * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
   * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
   * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
   * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
   * ***** END LICENSE BLOCK *****
   *
   * @copyright   Copyright (C) 2010 Robert Poyntz
   * @author      Robert Poyntz <rob@digitalfinery.com.au>
   * @license     http://www.opensource.org/licenses/bsd-license.php
   * @package     EasyPDO
   * @version     0.1.6
   */

  namespace Simplon\Lib\Db;

  class MySQL extends \Simplon\Vendor\EasyPDO\EasyPDO
  {
    protected function __construct($connectionString, $username = NULL, $password = NULL)
    {
      define('ERROR_DUPLICATE_KEY', 23000);
      parent::__construct($connectionString, $username, $password);
      $this->PDO->exec('SET NAMES \'utf8\' COLLATE \'utf8_unicode_ci\'');
    }

    /**
     * @static
     * @param $server
     * @param $database
     * @param $username
     * @param $password
     * @return \Simplon\Vendor\EasyPDO\EasyPDO
     */
    public static function Instance($server, $database, $username, $password)
    {
      $connectionString = 'mysql:host=' . $server . ';dbname=' . $database;
      $poolId = $connectionString;

      if (!array_key_exists($poolId, \Simplon\Vendor\EasyPDO\EasyPDO::$Instance))
      {
        \Simplon\Vendor\EasyPDO\EasyPDO::$Instance[$poolId] = new MySQL($connectionString, $username, $password);

        // we want results back as ASSOC Array
        \Simplon\Vendor\EasyPDO\EasyPDO::SetFetchMode(\Simplon\Vendor\EasyPDO\EasyPDO::FETCH_MODE_ASSOCIATIVE_ARRAY);
      }

      return \Simplon\Vendor\EasyPDO\EasyPDO::$Instance[$poolId];
    }

    /**
     * Override method to make use of named variables
     * including auto detecting value type
     *
     * @param $args
     */
    protected function BindParams($args)
    {
      if (isset($args) && count($args[0]) > 0)
      {
        /**
         * bindParam: only pass value as reference:
         * "...support the invocation of stored procedures that return data as output parameters,
         * and some also as input/output parameters that both send in data and are updated to receive it."
         */
        foreach ($args[0] as $key => &$value)
        {
          $valueType = \Simplon\Lib\Helper\BaseHelper::getType($value);
          $paramType = strtolower(substr($valueType['type'], 0, 1));

          // default param type would be s = string
          if (!array_key_exists($paramType, $this->ParamTypes))
          {
            echo 'PARAM TYPE DOES NOT EXIST: ' . $key . '=>' . $value;
            $paramType = 's';
          }

          $this->Query->bindParam(':' . $key, $value, $this->ParamTypes[$paramType]);
        }
      }
    }
  }