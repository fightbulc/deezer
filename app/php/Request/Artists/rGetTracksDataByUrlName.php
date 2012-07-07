<?php

  namespace App\Request\Artists;

  class rGetTracksDataByUrlName extends \Simplon\Abstracts\AbstractVo implements \App\Request\Artists\rInterface\iGetTracksDataByUrlName
  {
    /**
     * @return string
     */
    public function getUrlName()
    {
      $value = $this->getByKey('urlName');

      if(empty($value))
      {
        $this->throwException('urlName is missing.');
      }

      return $value;
    }
  }
