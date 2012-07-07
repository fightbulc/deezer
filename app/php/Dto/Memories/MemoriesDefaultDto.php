<?php

  namespace App\Dto\Memories;

  class MemoriesDefaultDto extends \Simplon\Abstracts\AbstractDto
  {
    protected function getObjects()
    {
      return array(

        'id' => array(
          'vo' => 'getId',
        ),

        'name' => array(
          'vo' => 'getName',
        ),

      );
    }

  }
