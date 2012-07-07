<?php

  namespace App\Request\Artists;

  class rGetByEventUrlName extends \Simplon\Abstracts\AbstractVo implements \App\Request\Artists\rInterface\iGetByEventUrlName
  {
    /**
     * @return string
     */
    public function getEventUrlName()
    {
      $value = $this->getByKey('urlName');

      if(empty($value))
      {
        $this->throwException('urlName is missing.');
      }

      return $value;
    }
  }
