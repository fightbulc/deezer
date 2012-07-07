<?php

  namespace Simplon\Abstracts;

  class AbstractCacheQueryManager extends \Simplon\Abstracts\AbstractClass
  {
    /**
     * @return \Simplon\Vendor\EasyPDO\EasyPDO
     */
    private function getSqlInstance()
    {
      return \Simplon\Lib\Db\DbFactory::MySQL();
    }

    // ########################################

    /**
     * @return \Simplon\Lib\Db\MemcachedLib
     */
    private function getCacheInstance()
    {
      return \Simplon\Lib\Db\DbFactory::Memcached();
    }

    // ########################################

    /**
     * @param \Simplon\Lib\Db\DbCacheQuery $dbCacheQuery
     * @return null|string
     */
    protected function fetchColumn(\Simplon\Lib\Db\DbCacheQuery $dbCacheQuery)
    {
      return $this
        ->getSqlInstance()
        ->FetchValue($dbCacheQuery->getSqlQuery(), $dbCacheQuery->getSqlConditions());
    }

    // ########################################

    /**
     * @param \Simplon\Lib\Db\DbCacheQuery $dbCacheQuery
     * @return array
     */
    protected function fetchRow(\Simplon\Lib\Db\DbCacheQuery $dbCacheQuery)
    {
      $result = array();

      // pull results from cache
      if ($dbCacheQuery->getUseCache())
      {
        $result = $this
          ->getCacheInstance()
          ->get($dbCacheQuery->getCacheId());
      }

      // pull results from sql
      if (empty($result))
      {
        $result = $this
          ->getSqlInstance()
          ->FetchArray($dbCacheQuery->getSqlQuery(), $dbCacheQuery->getSqlConditions());

        // write data in cache
        if ($dbCacheQuery->getUseCache())
        {
          $this->writeInCache($dbCacheQuery, $result);
        }
      }

      return $result;
    }

    // ########################################

    /**
     * @param \Simplon\Lib\Db\DbCacheQuery $dbCacheQuery
     * @return array
     */
    protected function fetchAll(\Simplon\Lib\Db\DbCacheQuery $dbCacheQuery)
    {
      $result = array();

      // pull results from cache
      if ($dbCacheQuery->getUseCache())
      {
        $result = $this
          ->getCacheInstance()
          ->get($dbCacheQuery->getCacheId());
      }

      // pull results from sql
      if (empty($result))
      {
        $result = $this
          ->getSqlInstance()
          ->FetchAll($dbCacheQuery->getSqlQuery(), $dbCacheQuery->getSqlConditions());

        // write data in cache
        if ($dbCacheQuery->getUseCache())
        {
          $this->writeInCache($dbCacheQuery, $result);
        }
      }

      return $result;
    }

    // ########################################

    /**
     * @param \Simplon\Lib\Db\DbCacheQuery $dbCacheQuery
     * @return bool
     */
    protected function insert(\Simplon\Lib\Db\DbCacheQuery $dbCacheQuery)
    {
      $tableName = $dbCacheQuery->getSqlTable();
      $data = $dbCacheQuery->getData();

      if (!empty($data))
      {
        // write to SQL
        if ($tableName)
        {
          if (!empty($data))
          {
            // prepare placeholders and values
            $_set = array();
            $_placeholder = array();
            $_values = array();

            foreach ($data as $key => $value)
            {
              $_set[] = $key;
              $placeholder_key = ':' . $key;

              // only ID field gets autoincrement
              if (is_null($value))
              {
                $placeholder_key = 'NULL';
              }
              else
              {
                $_values[$key] = $value;
              }

              $_placeholder[] = $placeholder_key;
            }

            $insertString = 'INSERT';

            // insert ignore awareness for tables with unique entries
            if ($dbCacheQuery->getSqlInsertIgnore() === TRUE)
            {
              $insertString = 'INSERT IGNORE';
            }

            // sql statement
            $sql = $insertString . ' INTO ' . $tableName . ' (' . join(',', $_set) . ') VALUES (' . join(',', $_placeholder) . ')';

            // insert data
            $insertId = $this
              ->getSqlInstance()
              ->ExecuteSQL($sql, $_values);

            return $insertId;
          }
        }

        // write data in cache
        if ($dbCacheQuery->getUseCache())
        {
          $this->writeInCache($dbCacheQuery, $data);
        }
      }

      return FALSE;
    }

    // ########################################

    /**
     * @param \Simplon\Lib\Db\DbCacheQuery $dbCacheQuery
     * @return bool
     */
    protected function update(\Simplon\Lib\Db\DbCacheQuery $dbCacheQuery)
    {
      $tableName = $dbCacheQuery->getSqlTable();
      $newData = $dbCacheQuery->getData();
      $updateConditions = $dbCacheQuery->getSqlConditions();

      if (!empty($newData))
      {
        // update sql
        if ($tableName)
        {
          if (!empty($updateConditions))
          {
            // prepare placeholders and values
            $_set = array();
            $_values = array();

            foreach ($newData as $key => $value)
            {
              $placeholder_key = ':' . $key;
              $_set[] = $key . '=' . $placeholder_key;
              $_values[$key] = $value;
            }

            // prepare conditions
            $_conditions = array();

            foreach ($updateConditions as $key => $value)
            {
              /**
               * Case NULL to enable conditions such as:
               * IN (1,2,3,4,5)
               */
              if (is_null($value))
              {
                $_conditions[] = $key;
              }
              else
              {
                /**
                 * wrap key to prevent duplication with $_values keys
                 */
                $placeholder_key = ':_simplon_condition_' . $key;
                $_conditions[] = $key . '= ' . $placeholder_key;
                $_values[substr($placeholder_key, 1)] = $value;
              }
            }

            // sql statement
            $sql = 'UPDATE ' . $tableName . ' SET ' . join(',', $_set) . ' WHERE ' . join(' AND ', $_conditions);

            // update data
            return $this
              ->getSqlInstance()
              ->ExecuteSQL($sql, $_values);
          }
        }

        // update data in cache
        if ($dbCacheQuery->getUseCache())
        {
          $this->writeInCache($dbCacheQuery, $newData);
        }
      }

      return FALSE;
    }

    // ########################################

    /**
     * @param \Simplon\Lib\Db\DbCacheQuery $dbCacheQuery
     * @return bool
     */
    protected function remove(\Simplon\Lib\Db\DbCacheQuery $dbCacheQuery)
    {
      $tableName = $dbCacheQuery->getSqlTable();

      // remove from sql
      if ($tableName)
      {
        $deleteConditions = $dbCacheQuery->getSqlConditions();

        if (!empty($deleteConditions))
        {
          // prepare conditions
          $_conditions = array();
          $_values = array();

          foreach ($deleteConditions as $key => $value)
          {
            /**
             * Case NULL to enable conditions such as:
             * IN (1,2,3,4,5)
             */
            if (is_null($value))
            {
              $_conditions[] = $key;
            }
            else
            {
              $_conditions[] = $key . '= :' . $key;
              $_values[$key] = $value;
            }
          }

          // sql statement
          $sql = 'DELETE FROM ' . $tableName . ' WHERE ' . join(' AND ', $_conditions);

          // remove data
          return $this
            ->getSqlInstance()
            ->ExecuteSQL($sql, $_values);
        }

        // remove data from cache
        if ($dbCacheQuery->getUseCache())
        {
          $this
            ->getCacheInstance()
            ->delete($dbCacheQuery->getCacheId());
        }
      }

      return FALSE;
    }

    // ########################################

    /**
     * @param \Simplon\Lib\Db\DbCacheQuery $dbCacheQuery
     * @param $data
     * @return mixed
     */
    protected function writeInCache(\Simplon\Lib\Db\DbCacheQuery $dbCacheQuery, $data)
    {
      return $this
        ->getCacheInstance()
        ->set($dbCacheQuery->getCacheId(), $data, $dbCacheQuery->getCacheExpiration());
    }
  }
