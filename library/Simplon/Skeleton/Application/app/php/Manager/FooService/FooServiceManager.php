<?php

  namespace App\Manager\FooService;

  class FooServiceManager extends \Simplon\Abstracts\AbstractCacheQueryManager
  {
    /**
     * @return string
     */
    public function getBookTitleQuery()
    {
      return 'SELECT title FROM books WHERE id = :id';
    }

    /**
     * @param $bookId Int
     *
     * @return array
     */
    public function getBookTitle($bookId)
    {
      $dbQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbQuery
        ->setSqlConnector('master')
        ->setSqlQuery($this->getBookTitleQuery())
        ->setSqlConditions(array('id' => $bookId));

      $result = $this->fetchColumn($dbQuery);

      return $result;
    }

    // ##########################################

    /**
     * @return string
     */
    public function getBookQuery()
    {
      return 'SELECT * FROM books WHERE id = :id';
    }

    /**
     * @param $bookId Int
     *
     * @return array
     */
    public function getBook($bookId)
    {
      $dbQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbQuery
        ->setSqlConnector('master')
        ->setSqlQuery($this->getBookQuery())
        ->setSqlConditions(array('id' => $bookId));

      $result = $this->fetchRow($dbQuery);

      return $result;
    }

    // ##########################################

    /**
     * @return string
     */
    public function getAllBooksQuery()
    {
      return 'SELECT * FROM books';
    }

    /**
     * @return array
     */
    public function getAllBooks()
    {
      $dbQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbQuery
        ->setSqlConnector('master')
        ->setSqlQuery($this->getAllBooksQuery());

      $result = $this->fetchAll($dbQuery);

      return $result;
    }

    // ##########################################

    /**
     * @param $values array
     *
     * @return bool
     */
    public function insertBook($values)
    {
      $dbQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbQuery
        ->setSqlConnector('master')
        ->setSqlTable('books')
        ->setData($values);

      $result = $this->insert($dbQuery);

      return $result;
    }

    // ##########################################

    /**
     * @param $conditions array
     * @param $values array
     * @return bool
     */
    public function updateBooks($conditions, $values)
    {
      $dbQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbQuery
        ->setSqlConnector('master')
        ->setSqlTable('books')
        ->setSqlConditions($conditions)
        ->setData($values);

      $result = $this->update($dbQuery);

      return $result;
    }

    // ##########################################

    /**
     * @param $conditions
     * @return bool
     */
    public function removeBooks($conditions)
    {
      $dbQuery = new \Simplon\Lib\Db\DbCacheQuery();

      $dbQuery
        ->setSqlConnector('master')
        ->setSqlTable('books')
        ->setSqlConditions($conditions);

      $result = $this->remove($dbQuery);

      return $result;
    }
  }
