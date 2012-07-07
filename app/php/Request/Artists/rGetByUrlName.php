<?php

  namespace App\Request\Artists;

  class rGetByUrlName extends \Simplon\Abstracts\AbstractVo implements \App\Request\Artists\rInterface\iGetByUrlName
  {
    /**
     * @return string
     */
    public function getArtistUrlName()
    {
      $value = $this->getByKey('urlName');

      if(empty($value))
      {
        $this->throwException('urlName is missing.');
      }

      return $value;
    }
  }
