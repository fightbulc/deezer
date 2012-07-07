<?php
  /**
   * *** BEGIN LICENSE BLOCK *****
   *
   * This file is part of EasyPDO (http://easypdo.robpoyntz.com/).
   *
   * Software License Agreement (New BSD License)
   *
   * Copyright (c) 2010, Robert Poyntz / Digital Finery Pty Ltd
   * All rights reserved.
   *
   * Redistribution and use in source and binary forms, with or without modification,
   * are permitted provided that the following conditions are met:
   *
   *     * Redistributions of source code must retain the above copyright notice,
   *       this list of conditions and the following disclaimer.
   *
   *     * Redistributions in binary form must reproduce the above copyright notice,
   *       this list of conditions and the following disclaimer in the documentation
   *       and/or other materials provided with the distribution.
   *
   *     * Neither the name of Robert Poyntz, Digital Finery nor the names of its
   *       contributors may be used to endorse or promote products derived from this
   *       software without specific prior written permission.
   *
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
   *
   * ***** END LICENSE BLOCK *****
   *
   * @copyright   Copyright (C) 2010 Robert Poyntz
   * @author      Robert Poyntz <rob@digitalfinery.com.au>
   * @license     http://www.opensource.org/licenses/bsd-license.php
   * @package     EasyPDO
   * @version     0.1.6
   */
  namespace Simplon\Vendor\EasyPDO;

  class EasyPDOException extends \Exception
  {
  }

  ;
  class EDatabaseException extends EasyPDOException
  {
  }

  ;
  class ENoDatabaseConnection extends EDatabaseException
  {
  }

  ;
  class EDuplicateKey extends EDatabaseException
  {
  }

  ;

  // ##########################################

  interface EasyPDOInterface
  {
    function GetConnectionObject();

    function Close();

    function Reset();

    function StartTransaction();

    function RollbackTransaction();

    function CommitTransaction();

    function Fetch($sql);

    function FetchObject($sql);

    function FetchArray($sql);

    function FetchValue($sql);

    function ExecuteSQL($sql);

    function GetLastSQL();

    static function SetFetchMode($mode);
  }

  // ##########################################

  class QueryResultIterator implements \Iterator
  {
    protected $Query;
    protected $CurrentObj;
    protected $Idx;
    protected $FetchMode;

    public function __construct($query, $fetchMode)
    {
      $this->Query = $query;
      $this->Idx = 0;
      $this->FetchMode = $fetchMode;
    }

    public function current()
    {
      return $this->CurrentObj;
    }

    public function next()
    {
      $this->CurrentObj = $this->Query->fetch($this->FetchMode);
      $this->Idx++;
    }

    public function key()
    {
      return $this->Idx;
    }

    public function valid()
    {
      return ($this->CurrentObj !== false);
    }

    public function rewind()
    {
      $this->Idx = 0;
      $this->CurrentObj = $this->Query->fetch($this->FetchMode);
    }
  }

  // ##########################################

  class QueryResultIteratorClass extends QueryResultIterator
  {
    private $FetchClass;

    private function SetFetchClass($className)
    {
      if (!isset($className) || ($className == '')) {
        throw new EasyPDOException('Invalid class specified for FETCH_MODE_CLASS');
      }
      else if (!class_exists($className)) {
        throw new EasyPDOException('Specified class does not exist for FETCH_MODE_CLASS');
      }

      $this->FetchClass = $className;
    }

    public function __construct($query, $fetchMode, $fetchClass)
    {
      parent::__construct($query, \PDO::FETCH_CLASS);
      $this->SetFetchClass($fetchClass);
    }

    public function rewind()
    {
      $this->Idx = 0;
      $this->CurrentObj = $this->Query->fetchObject($this->FetchClass);
    }

    public function next()
    {
      $this->CurrentObj = $this->Query->fetchObject($this->FetchClass);
      $this->Idx++;
    }
  }

  // ##########################################

  abstract class EasyPDO implements EasyPDOInterface
  {
    const FETCH_MODE_NUMERIC_ARRAY = 1;
    const FETCH_MODE_ASSOCIATIVE_ARRAY = 2;
    const FETCH_MODE_OBJECT = 3;
    const FETCH_MODE_CLASS = 4;

    private static $FetchModes = array(
      EasyPDO::FETCH_MODE_NUMERIC_ARRAY     => \PDO::FETCH_NUM,
      EasyPDO::FETCH_MODE_ASSOCIATIVE_ARRAY => \PDO::FETCH_ASSOC,
      EasyPDO::FETCH_MODE_OBJECT            => \PDO::FETCH_OBJ,
      EasyPDO::FETCH_MODE_CLASS             => \PDO::FETCH_CLASS
    );

    protected static $FetchMode = EasyPDO::FETCH_MODE_OBJECT;
    protected static $FetchClass = null;
    protected static $Instance = array();

    private $LastSQL = '';

    /**
     * @var \PDO
     */
    protected $PDO;
    protected $ParamTypes = array(
      'i' => \PDO::PARAM_INT,
      'd' => \PDO::PARAM_STR,
      's' => \PDO::PARAM_STR,
      'b' => \PDO::PARAM_LOB
    );

    /**
     * @var \PDOStatement
     */
    protected $Query = null;

    protected $QueryLog = array();

    private function UpdateQueryLog($sql)
    {
      $this->QueryLog[] = $sql;
    }


    /**
     * Sets the fetch mode for EasyPDO
     *
     * @param integer $mode expects EasyPDO::FETCH_MODE_NUMERIC_ARRAY, EasyPDO::FETCH_MODE_ASSOCIATIVE_ARRAY or EasyPDO::FETCH_MODE_OBJECT
     *
     * @return void
     */
    public static function SetFetchMode($mode, $className = null)
    {
      if ($mode === EasyPDO::FETCH_MODE_CLASS)
      {
        if (!isset($className) || ($className == ''))
        {
          throw new EasyPDOException('Invalid class specified for FETCH_MODE_CLASS');
        }
        else if (!class_exists($className))
        {
          throw new EasyPDOException('Specified class does not exist for FETCH_MODE_CLASS');
        }
        else
        {
          EasyPDO::$FetchClass = $className;
        }
      }
      else
      {
        EasyPDO::$FetchClass = null;
      }

      EasyPDO::$FetchMode = $mode;
    }

    /**
     * Returns the \PDO::FETCH_XXX constant associated with the current fetch mode
     * @return integer
     */
    private static function GetFetchMode()
    {
      return EasyPDO::$FetchModes[EasyPDO::$FetchMode];
    }

    /*
    * Attempts to connect to the database using the specified connection string and credentials
    * @param string $connectionString
    * @param string $username
    * @param string $password
    * @throws ENoDatabaseConnection
    */
    protected function __construct($connectionString, $username = null, $password = null)
    {
      try
      {
        $this->PDO = new \PDO($connectionString, $username, $password);
        $this->PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      }
      catch (\Exception $e)
      {
        throw new ENoDatabaseConnection();
      }
    }

    /*
    * Binds values to the parameters in a PDOStatement object.
    * $args is assumed to be an array, the first element of which specifies parameter types,
    * the remaining elements being the parameter values. If the second argument is an array,
    * it's elements are used as the parameter values
    * @param array $args
    */
    protected function BindParams($args)
    {
      if (count($args) > 1)
      {
        if ((count($args) === 2) && is_array($args[1]))
        {
          array_splice($args, 1, 1, $args[1]);
        }

        $types = str_split(array_shift($args));
        if (count($types) !== count($args))
        {
          throw new EDatabaseException('Number of parameters does not equal number of parameter types');
        }

        for ($paramIndex = 0; $paramIndex < count($args); $paramIndex++)
        {
          $this->Query->bindParam(1 + $paramIndex, $args[$paramIndex], $this->ParamTypes[$types[$paramIndex]]);
        }
      }
    }

    /*
    * Returns the last SQL executed (or attempted to be executed)
    */
    public function GetLastSQL()
    {
      return $this->LastSQL;
    }

    /*
    * Returns the \PDO connection instance for this object
    * @return \PDO
    */
    public function GetConnectionObject()
    {
      return $this->PDO;
    }

    /*
    * Deletes the current PDOStatement, if any.
    */
    public function Close()
    {
      if ($this->Query)
      {
        unset($this->Query);
      }
    }

    /*
    * Alias for EasyPDO::Close()
    */
    public function Reset()
    {
      $this->Close();
    }

    /*
    * Starts a transaction (for database engines that support this feature)
    */
    public function StartTransaction()
    {
      $this->Query = null;
      $this->PDO->beginTransaction();
    }

    /*
    * Commits a transaction (for database engines that support this feature)
    */
    public function CommitTransaction()
    {
      $this->Query = null;
      $this->PDO->commit();
    }

    /*
    * Rolls back a transaction (for database engines that support this feature)
    */
    public function RollbackTransaction()
    {
      $this->Query = null;
      $this->PDO->rollBack();
    }

    /*
    * Destructor
    * Closes the current PDOStatement object (if any), and deletes the reference
    * to current EasyPDO singleton instance.
    */
    public function __destruct()
    {
      $this->Query = null;
      EasyPDO::$Instance = null;
    }

    private function PrepareSQL($sql)
    {
      if ($sql != $this->LastSQL)
      {
        $this->UpdateQueryLog($sql);
        $this->Query = null;
        $this->LastSQL = $sql;
        $this->Query = $this->PDO->prepare($sql);
      }
    }

    /*
    * Executes an SQL 'SELECT' statement and returns an Iterator interface allowing access to the result set.
    * @param string $sql
    * @param string $types optional parameter type definition
    * @param mixed $value,... optional parameter value
    * @return QueryResultIterator
    */
    public function Fetch($sql)
    {
      $this->PrepareSQL($sql);
      $args = func_get_args();
      array_shift($args);
      $this->BindParams($args);
      $this->Query->execute();

      if (EasyPDO::$FetchMode == EasyPDO::FETCH_MODE_CLASS)
      {
        return new QueryResultIteratorClass($this->Query, EasyPDO::GetFetchMode(), EasyPDO::$FetchClass);
      }
      else
      {
        return new QueryResultIterator($this->Query, EasyPDO::GetFetchMode());
      }
    }

    /*
    * Returns the first row of an SQL SELECT statement as an array.
    * Array indexing is determined by the current FetchMode and can be either numerical or associative
    * @param string $sql
    * @param string $types optional parameter type definition
    * @param mixed $value,... optional parameter value
    * @return array
    */
    public function FetchArray($sql)
    {
      $this->PrepareSQL($sql);
      $args = func_get_args();
      array_shift($args);
      $this->BindParams($args);
      $this->Query->execute();
      if (EasyPDO::$FetchMode === EasyPDO::FETCH_MODE_NUMERIC_ARRAY)
      {
        return $this->Query->fetch(\PDO::FETCH_NUM);
      }
      else
      {
        return $this->Query->fetch(\PDO::FETCH_ASSOC);
      }
    }

    /*
    * Returns a single value from an SQL SELECT statement
    * @param string $sql
    * @param string $types optional parameter type definition
    * @param mixed $value,... optional parameter value
    * @return mixed
    */
    public function FetchValue($sql)
    {
      $this->PrepareSQL($sql);
      $args = func_get_args();
      array_shift($args);
      $this->BindParams($args);
      $this->Query->execute();
      $result = $this->Query->fetch(\PDO::FETCH_NUM);
      if ($result && (count($result) > 0))
      {
        return $result[0];
      }
      else
      {
        return null;
      }
    }

    /*
    * Returns the first row of an SQL SELECT statement as an object.
    * @param string $sql
    * @param string $types optional parameter type definition
    * @param mixed $value,... optional parameter value
    * @return StdClass
    */
    public function FetchObject($sql)
    {
      $this->PrepareSQL($sql);
      $args = func_get_args();
      array_shift($args);
      $this->BindParams($args);
      $this->Query->execute();
      if (EasyPDO::$FetchMode == EasyPDO::FETCH_MODE_CLASS)
      {
        return $this->Query->fetch(\PDO::FETCH_CLASS, EasyPDO::$FetchClass);
      }
      else
      {
        return $this->Query->fetch(\PDO::FETCH_OBJ);
      }
    }

    /*
    * Returns an entire result set as either an array of objects or an
    * array of arrays, depending on the current FetchMode
    * @param string $sql
    * @param string $types optional parameter type definition
    * @param mixed $value,... optional parameter value
    * @return array
    */
    public function FetchAll($sql)
    {
      $this->PrepareSQL($sql);
      $args = func_get_args();
      array_shift($args);
      $this->BindParams($args);
      $this->Query->execute();
      if (EasyPDO::$FetchMode == EasyPDO::FETCH_MODE_CLASS)
      {
        return $this->Query->fetchAll(EasyPDO::GetFetchMode(), EasyPDO::$FetchClass);
      }
      else
      {
        return $this->Query->fetchAll(EasyPDO::GetFetchMode());
      }
    }

    /*
    * Returns the identity of the last inserted row, if any
    * Note this feature is not supported by all database engines
    * @return integer|null
    */
    protected function GetLastInsertID()
    {
      // Use the generic \PDO "lastInsertId" method.
      // Not all database engines support this feature. Those that do often have differing implementations.
      // This method may be overriden as required
      return ($this->PDO->lastInsertId() > 0) ? $this->PDO->lastInsertId() : null;
    }

    /*
    * Executes an SQL statement against the database
    * @param string $sql
    * @param string $types optional parameter type definition
    * @param mixed $value,... optional parameter value
    * @return integer|null returns the last inserted identity for INSERT statements, or null for other SQL statements
    */
    public function ExecuteSQL($sql)
    {
      $this->UpdateQueryLog($sql);
      if (!isset($this->Query) || ($this->LastSQL != $sql))
      {
        $this->Query = null;
        $this->LastSQL = $sql;
        $this->Query = $this->PDO->prepare($sql);
      }

      $args = func_get_args();
      array_shift($args);
      $this->BindParams($args);
      try
      {
        $this->Query->execute();
      }
      catch (\PDOException $e)
      {
        if ($e->getCode() == ERROR_DUPLICATE_KEY)
        {
          throw new EDuplicateKey($e->getMessage());
        }
        else
        {
          throw $e;
        }
      }

      return $this->GetLastInsertID();
    }

    public function GetQueryLog()
    {
      return $this->QueryLog;
    }

  }
