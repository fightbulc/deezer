<?php

  namespace App\Dto\Collections;

  class CollectionsByTrackDto extends \Simplon\Abstracts\AbstractDto
  {
    protected function getObjects()
    {
      return array(

        'amount' => array(
          'vo' => 'getAmount',
        ),

        'tag' => array(
          'vo' => 'getTag',
        ),

      );
    }

  }
