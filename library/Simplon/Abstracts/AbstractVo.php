<?php

  namespace Simplon\Abstracts;

  class AbstractVo extends AbstractClass
  {
    /**
     * @var array
     */
    public $_data = array();

    // ##########################################

    /**
     * @return bool
     */
    public function hasData()
    {
      $data = $this->getData();
      return !empty($data) ? TRUE : FALSE;
    }

    // ##########################################

    /**
     * @param string $key
     * @param $val
     * @return AbstractVo
     */
    public function setByKey($key, $val)
    {
      $this->_data[$key] = $val;
      return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getByKey($key)
    {
      if (array_key_exists($key, $this->_data))
      {
        return $this->_data[$key];
      }
      else
      {
        $this->throwException('VO fail: "' . $key . '" is missing.');
      }

      return FALSE;
    }

    // ##########################################

    /**
     * @param array $data
     * @return AbstractVo
     */
    public function setData($data)
    {
      if (is_array($data))
      {
        $this->_data = $data;
      }

      return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
      return $this->_data;
    }

    // ##########################################

    /**
     * @param string $id
     * @return AbstractVo
     */
    public function setId($id)
    {
      $this->setByKey('id', $id);
      return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
      return $this->getByKey('id');
    }

    // ##########################################

    /**
     * @param AbstractDto $dto
     * @return array
     */
    public function export(AbstractDto $dto)
    {
      return $dto->export($this);
    }

    // ##########################################

    /**
     * @return string
     */
    public function getCurrentUsersFacebookId()
    {
      return \Simplon\Lib\Facebook\FacebookLib::getInstance()
        ->getUserId();
    }
  }
