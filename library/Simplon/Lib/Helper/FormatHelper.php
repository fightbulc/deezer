<?php

  namespace Simplon\Lib\Helper;

  class FormatHelper
  {
    /**
     * @static
     * @param $array array
     * @return string json
     */
    public static function jsonEncode($array)
    {
      return json_encode($array);
    }

    // ##########################################

    /**
     * @static
     * @param      $json string
     * @param bool $returnArray
     * @return array
     */
    public static function jsonDecode($json, $returnArray = TRUE)
    {
      return json_decode($json, $returnArray);
    }

    // ##########################################

    /**
     * @static
     * @param $unixtime
     * @return string
     */
    public static function convertUnixTimeToIso($unixtime)
    {
      return date('Y-m-d\TH:i:s', $unixtime);
    }

    // ##########################################

    /**
     * @static
     * @return int
     */
    public static function getUnixTime()
    {
      return time();
    }

    // ##########################################

    /**
     * Calculates the time period of a given date. A period is defined by a starting/ending date
     * as well as an interval number. Valid periods are: week, month, quarter, halfyear, year.
     * The calculated period can be shifted to the past/future.
     * Get next months period: getDatePeriod(date("Y-m-d"), 'month', 1) results in:
     * [reference] => 2012-04-08, [start] => 2012-04-01, [end] => 2012-04-30, [interval] => 04
     *
     * @static
     * @param string $date
     * @param string $period
     * @param null   $shift
     * @param string $format
     * @return array
     */
    public static function getDatePeriod($date = '', $period = 'week', $shift = NULL, $format = 'Y-m-d')
    {
      $date = new \Simplon\Vendor\flourish\fTimestamp($date);
      $period_interval = NULL;

      if ($period == 'week')
      {
        if (!is_null($shift))
        {
          $date = $date->adjust(($shift * 7) . ' days');
        }

        $start_date = date('Y-m-d', mktime(0, 0, 0, $date->format('m'), $date->format('d') + (1 - $date->format('N')), $date->format('Y')));
        $end_date = date('Y-m-d', mktime(0, 0, 0, $date->format('m'), $date->format('d') + (7 - $date->format('N')), $date->format('Y')));
      }

      elseif ($period == 'month')
      {
        if (!is_null($shift))
        {
          $date = $date->adjust($shift . ' months');
        }

        $start_date = date('Y-m-d', mktime(0, 0, 0, $date->format('m'), 1, $date->format('Y')));
        $end_date = date('Y-m-d', mktime(0, 0, 0, $date->format('m'), $date->format('t'), $date->format('Y')));
        $period_interval = $date->format('m');
      }

      elseif ($period == 'quarter')
      {
        if (!is_null($shift))
        {
          $date = $date->adjust(($shift * 3) . ' months');
        }

        if ($date->format('m') <= 3)
        {
          $new_date = new \Simplon\Vendor\flourish\fTimestamp($date->format('Y') . '-1-1');
          $period_interval = 1;
        }
        elseif ($date->format('m') > 3 && $date->format('m') <= 6)
        {
          $new_date = new \Simplon\Vendor\flourish\fTimestamp($date->format('Y') . '-4-1');
          $period_interval = 2;
        }
        elseif ($date->format('m') > 6 && $date->format('m') <= 9)
        {
          $new_date = new \Simplon\Vendor\flourish\fTimestamp($date->format('Y') . '-7-1');
          $period_interval = 3;
        }
        else
        {
          $new_date = new \Simplon\Vendor\flourish\fTimestamp($date->format('Y') . '-10-1');
          $period_interval = 4;
        }

        $start_date = date('Y-m-d', mktime(0, 0, 0, $new_date->format('m'), 1, $new_date->format('Y')));
        $end_date = date('Y-m-d', mktime(0, 0, 0, $new_date
          ->adjust('+2 months')
          ->format('m'), $new_date
          ->adjust('+2 months')
          ->format('t'), $new_date
          ->adjust('+2 months')
          ->format('Y')));
      }

      elseif ($period == 'halfyear')
      {
        if (!is_null($shift))
        {
          $date = $date->adjust(($shift * 5) . ' months');
        }

        if ($date->format('m') <= 6)
        {
          $new_date = new \Simplon\Vendor\flourish\fTimestamp($date->format('Y') . '-1-1');
          $period_interval = 1;
        }
        else
        {
          $new_date = new \Simplon\Vendor\flourish\fTimestamp($date->format('Y') . '-7-1');
          $period_interval = 2;
        }

        $start_date = date('Y-m-d', mktime(0, 0, 0, $new_date->format('m'), 1, $new_date->format('Y')));
        $end_date = date('Y-m-d', mktime(0, 0, 0, $new_date
          ->adjust('+5 months')
          ->format('m'), $new_date
          ->adjust('+5 months')
          ->format('t'), $new_date
          ->adjust('+5 months')
          ->format('Y')));
      }

      elseif ($period == 'year')
      {
        if (!is_null($shift))
        {
          $date = $date->adjust($shift . ' years');
        }

        $new_date = new \Simplon\Vendor\flourish\fTimestamp($date->format('Y') . '-1-1');
        $start_date = date('Y-m-d', mktime(0, 0, 0, $new_date->format('m'), 1, $new_date->format('Y')));
        $end_date = date('Y-m-d', mktime(0, 0, 0, $new_date
          ->adjust('+11 months')
          ->format('m'), $new_date
          ->adjust('+11 months')
          ->format('t'), $new_date
          ->adjust('+11 months')
          ->format('Y')));
      }

      $start_date = new \Simplon\Vendor\flourish\fTimestamp($start_date);
      $end_date = new \Simplon\Vendor\flourish\fTimestamp($end_date);

      if ($period == 'week')
      {
        $period_interval = $start_date->format('W');
      }

      return array(
        'reference' => $date->format($format),
        'start' => $start_date->format($format),
        'end' => $end_date->format($format),
        'interval' => $period_interval
      );
    }

    // ##########################################

    /**
     * Returns a period for a given year and an interval.
     * Valid periods are: week, month, quarter, halfyear, year.
     * Get the 2nd quarter: getDatePeriodByInterval('2012', 'quarter', 2)
     * Result: [reference] => 2012-04-01, [start] => 2012-04-01, [end] => 2012-06-30, [interval] => 2
     *
     * @static
     * @param string $year
     * @param string $period
     * @param string $interval
     * @return array|string
     */
    public static function getDatePeriodByInterval($year = '', $period = 'week', $interval = '1')
    {
      $date = new \Simplon\Vendor\flourish\fTimestamp($year . '-1-1');
      $result = '';

      if ($period == 'week')
      {
        if ($interval == 'last')
        {
          $check_date = new \Simplon\Vendor\flourish\fTimestamp($year . '-12-31');
          $interval = $check_date->format('W');
        }

        while ($date->format('W') != $interval)
        {
          $date = $date->adjust('+7 day');
        }

        $result = FormatHelper::getDatePeriod($date, $period);
      }

      if ($period == 'quarter')
      {
        $date = $date->adjust((--$interval * 3) . ' months');
        $result = FormatHelper::getDatePeriod($date, $period);
      }

      if ($period == 'halfyear')
      {
        $date = $date->adjust((--$interval * 6) . ' months');
        $result = FormatHelper::getDatePeriod($date, $period);
      }

      return $result;
    }

    // ##########################################

    /**
     * @static
     * @param $time1
     * @param $time2
     * @param string $return
     * @return mixed
     */
    public static function timeDiff($time1, $time2, $return = 'minutes')
    {
      $return_format = array(
        'years' => 'y',
        'months' => 'm',
        'days' => 'd',
        'hours' => 'h',
        'minutes' => 'i',
        'seconds' => 's',
      );

      $interval = date_diff(new DateTime($time1), new DateTime($time2));
      return $interval->format('%' . $return_format[$return]);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @return string
     */
    public static function stringClean($string)
    {
      return \Simplon\Vendor\flourish\fUTF8::clean($string);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @param string $delimiter
     * @return array
     */
    public static function stringExplode($string, $delimiter = '')
    {
      return \Simplon\Vendor\flourish\fUTF8::explode($string, $delimiter);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @param string $regExp
     * @return int
     */
    public static function stringPregMatch($string, $regExp = '')
    {
      return preg_match('#' . $regExp . '#iu', $string);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @param $regexp
     * @param $replaceWith
     * @return mixed
     */
    public static function stringPregReplace($string, $regexp, $replaceWith)
    {
      return preg_replace('#' . $regexp . '#iu', $replaceWith, $string);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @param string $regExp
     * @return array
     */
    public static function stringPregSplit($string, $regExp = '')
    {
      return preg_split('#' . $regExp . '#iu', $string);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @param null $charlist
     * @param null $side
     * @return string
     */
    public static function stringTrim($string, $charlist = NULL, $side = NULL)
    {
      if ($side == 'left')
      {
        $trimmed = \Simplon\Vendor\flourish\fUTF8::ltrim($string, $charlist);
      }
      elseif ($side == 'right')
      {
        $trimmed = \Simplon\Vendor\flourish\fUTF8::ltrim($string, $charlist);
      }
      else
      {
        $trimmed = \Simplon\Vendor\flourish\fUTF8::trim($string, $charlist);
      }

      return $trimmed;
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @return int
     */
    public static function stringLength($string)
    {
      return \Simplon\Vendor\flourish\fUTF8::len($string);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @param $search
     * @param $replaceWith
     * @return string
     */
    public static function stringReplace($string, $search, $replaceWith)
    {
      return \Simplon\Vendor\flourish\fUTF8::replace($string, $search, $replaceWith);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @param $start
     * @param null $length
     * @return mixed
     */
    public static function stringSubstr($string, $start, $length = NULL)
    {
      return \Simplon\Vendor\flourish\fUTF8::sub($string, $start, $length);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @param $needle
     * @return mixed
     */
    public static function stringPos($string, $needle)
    {
      return \Simplon\Vendor\flourish\fUTF8::ipos($string, $needle, 0);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @return string
     */
    public static function stringToLower($string)
    {
      return \Simplon\Vendor\flourish\fUTF8::lower($string);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @return string
     */
    public static function stringToUpper($string)
    {
      return \Simplon\Vendor\flourish\fUTF8::upper($string);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @return string
     */
    public static function stringUcFirst($string)
    {
      return \Simplon\Vendor\flourish\fUTF8::ucfirst($string);
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @return string
     */
    public static function stringUcWords($string)
    {
      return \Simplon\Vendor\flourish\fUTF8::ucwords($string);
    }

    // ##########################################

    /**
     * @static
     * @param bool $string
     * @param string $whiteSpacesReplacement
     * @return bool|mixed|string
     */
    public static function stringUrlable($string = FALSE, $whiteSpacesReplacement = '')
    {
      if ($string !== FALSE)
      {
        if (!empty($whiteSpacesReplacement))
        {
          $string = FormatHelper::stringReplace($string, $whiteSpacesReplacement, '');
        }

        $string = FormatHelper::stringAscii(FormatHelper::stringTrim($string));
        $string = FormatHelper::stringToLower(FormatHelper::stringPregReplace($string, '[^\w\d\.\-\_ ]', ''));
        $string = preg_replace('#\s+#', $whiteSpacesReplacement, $string);
      }

      return $string;
    }

    // ##########################################

    /**
     * @static
     * @param bool $string
     * @return bool|string
     */
    public static function stringAscii($string = FALSE)
    {
      if ($string !== FALSE)
      {
        $string = FormatHelper::stringReplace($string, 'Ä', 'Ae');
        $string = FormatHelper::stringReplace($string, 'ä', 'ae');
        $string = FormatHelper::stringReplace($string, 'Ü', 'Ue');
        $string = FormatHelper::stringReplace($string, 'ü', 'ue');
        $string = FormatHelper::stringReplace($string, 'Ö', 'Oe');
        $string = FormatHelper::stringReplace($string, 'ö', 'oe');
        $string = iconv("UTF-8", 'ASCII//TRANSLIT', $string);
      }

      return $string;
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @return string
     */
    public static function stringHtmlSpecialChars($string)
    {
      return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    // ##########################################

    /**
     * @static
     * @param $string
     * @return string
     */
    public static function stringHtmlSpecialCharsDecode($string)
    {
      return htmlspecialchars_decode($string, ENT_QUOTES, 'UTF-8');
    }

    // ##########################################

    /**
     * @static
     * @param $sizeInByte
     * @param string $return
     * @return array|string
     */
    public static function fileSize($sizeInByte, $return = 'human')
    {
      $format = array(
        'kb' => 1024,
        'mb' => 1048576,
        'gb' => 1073741824
      );

      $calculations = array(
        'byte' => $sizeInByte,
        'kb' => round($sizeInByte / $format['kb'], 2),
        'mb' => round($sizeInByte / $format['mb'], 2),
        'gb' => round($sizeInByte / $format['gb'], 2)
      );

      if ($return == 'human')
      {
        if ($calculations['kb'] < 1000)
        {
          return $calculations['kb'] . ' KB';
        }
        elseif ($calculations['mb'] < 1000)
        {
          return $calculations['mb'] . ' MB';
        }
        else
        {
          return $calculations['gb'] . ' GB';
        }
      }
      else
      {
        return $calculations;
      }
    }

    // ##########################################

    /**
     * @static
     * @param $array
     * @return array|mixed
     */
    public static function arrayPop($array)
    {
      if (is_array($array))
      {
        return array_pop($array);
      }

      return $array;
    }

    // ##########################################

    /**
     * @static
     * @param $array
     * @param $value
     * @return array
     */
    public static function arrayPush($array, $value)
    {
      if (!empty($value))
      {
        if (!is_array($array))
        {
          $array = array();
        }

        array_push($array, $value);
      }

      return $array;
    }

    // ##########################################

    /**
     * @static
     * @param $array
     * @return array|mixed
     */
    public static function arrayShift($array)
    {
      if (is_array($array))
      {
        return array_shift($array);
      }

      return $array;
    }

    // ##########################################

    /**
     * @static
     * @param $array
     * @param $value
     * @return array
     */
    public static function arrayUnshift($array, $value)
    {
      if (!empty($value))
      {
        if (!is_array($array))
        {
          $array = array();
        }

        array_unshift($array, $value);
      }

      return $array;
    }

    // ##########################################

    /**
     * @static
     * @param $array
     * @return array
     */
    public static function arrayKeys($array)
    {
      if (!is_array($array))
      {
        $array = array();
      }

      $keys = array_keys($array);
      return $keys;
    }

    // ##########################################

    /**
     * @static
     * @param $array
     * @return array
     */
    public static function arrayValues($array)
    {
      if (!is_array($array))
      {
        $array = array();
      }

      $values = array_values($array);
      return $values;
    }

    // ##########################################

    /**
     * @static
     * @param $array
     * @param string $joinString
     * @return bool|string
     */
    public static function arrayJoin($array, $joinString = '')
    {
      if (!empty($array))
      {
        return join($joinString, $array);
      }

      return FALSE;
    }

    // ##########################################

    /**
     * @static
     * @param $array
     * @return array|bool|mixed
     */
    public static function arrayShuffle($array)
    {
      if (empty($array))
      {
        return false;
      }

      $tmp = array();
      foreach ($array as $key => $value)
      {
        $tmp[] = array(
          'k' => $key,
          'v' => $value
        );
      }

      shuffle($tmp);

      $array = array();
      foreach ($tmp as $entry)
      {
        $array[$entry['k']] = $entry['v'];
      }

      return $array;
    }

    // ##########################################

    /**
     * @static
     * @param $array
     * @param $size
     * @return array
     */
    public static function arrayShrink($array, $size)
    {
      $objects = array();

      if (count($array) <= $size)
      {
        return $array;
      }

      for ($i = 0; $i < $size; $i++)
      {
        list($id, $obj) = each($array);
        $objects[$id] = $obj;
      }

      return $objects;
    }

    // ##########################################

    /**
     * @static
     * @param $array1
     * @param $array2
     * @param string $mergeBy
     * @return array
     */
    public static function arrayMerge($array1, $array2, $mergeBy = 'val')
    {
      $result = array();

      if (count($array1) > 0 && count($array2) > 0)
      {
        if ($mergeBy == 'val')
        {
          $result = array_intersect($array1, $array2);
        }
      }
      elseif (count($array1) > 0)
      {
        $result = $array1;
      }
      elseif (count($array2) > 0)
      {
        $result = $array2;
      }

      return $result;
    }
  }