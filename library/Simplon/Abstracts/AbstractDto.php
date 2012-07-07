<?php

  namespace Simplon\Abstracts;

  class AbstractDto extends AbstractClass
  {
    /**
     * @param AbstractVo $vo
     * @return array
     * @throws \Exception
     */
    public function export(AbstractVo $vo)
    {
      $newObject = array();
      $voMethods = get_class_methods($vo);

      $transferObjects = $this->getObjects();

      foreach ($transferObjects as $newKey => $mappings)
      {
        $value = NULL;

        // get value by VO
        if (in_array($mappings['vo'], $voMethods))
        {
          $value = $vo->$mappings['vo']();
        }
        else
        {
          throw new \Exception('VO -> DTO export fail. Vo method doesnt exist: ' . $mappings['vo']);
        }

        // default fallback value
        if (empty($value))
        {
          $value = NULL;

          if (array_key_exists('default', $mappings))
          {
            $value = $mappings['default'];
          }
        }

        // format value if defined
        if (!empty($value) && array_key_exists('format', $mappings))
        {
          $value = $mappings['format']($value);
        }

        $newObject[$newKey] = $value;
      }

      return $newObject;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function getObjects()
    {
      return array();
    }
  }
