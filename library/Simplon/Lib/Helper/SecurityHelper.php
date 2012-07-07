<?php

  namespace Simplon\Lib\Helper;

  Class SecurityHelper
  {
    /*
        Creates a salt string
      */
    public static function salt($length = 20)
    {
      return substr(md5(uniqid(rand(), true)), 0, $length);
    }

    // ##########################################

    /*
        Creates a hash string either by a given salt string
        or simply by generating one
      */
    public static function hash_string($string = '', $salt = '', $salt_length = 20)
    {
      // new hash generation
      if (empty($salt))
      {
        $salt = SecurityHelper::salt($salt_length);
      }

      return $salt . substr(sha1($salt . $string), 0, -$salt_length);
    }

    // ##########################################

    /*
        Compares a passed string to a given hashed one
        Example: compare a posted password to one which lies within a DB
      */
    public static function compare_hashed_strings($passed_string, $hashed_string, $salt_length = 20)
    {
      $salt = substr($hashed_string, 0, $salt_length);
      return SecurityHelper::hash_string($passed_string, $salt, $salt_length) === $hashed_string ? TRUE : FALSE;
    }

    // ##########################################

    /*
        Create random password
      */
    public static function create_random_password()
    {
      $password = (string)'';
      $password .= mt_rand(0, 9);
      $password .= chr(mt_rand(97, 122));
      $password .= chr(mt_rand(65, 90));

      for ($i = 0; $i < 5; $i++)
      {
        Switch (mt_rand(0, 2))
        {
          case 0:
            $password .= mt_rand(0, 9);
            break;
          case 1:
            $password .= chr(mt_rand(97, 122));
            break;
          case 2:
            $password .= chr(mt_rand(65, 90));
            break;
        }
      }

      return str_shuffle($password);
    }

    // ##########################################

    /*
        Create unique id
      */
    public static function create_uid()
    {
      return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    // ##########################################

    /*
        Create barcode unique id
      */
    public static function create_barcode_uid()
    {
      $byteString = "";
      $uuid = SecurityHelper::create_uid();
      $uuid = str_replace("-", "", $uuid);

      for ($i = 0; $i < strlen($uuid); $i += 2)
      {
        $s = substr($uuid, $i, 2);
        $d = hexdec($s);
        $c = chr($d);
        $byteString = $byteString . $c;
      }

      $b64uuid = base64_encode($byteString);

      // Replace the "/" and "+" since they are reserved characters
      $b64uuid = str_replace("/", "_", $b64uuid);
      $b64uuid = str_replace("+", "_", $b64uuid);

      // Remove the trailing "=="
      $b64uuid = substr($b64uuid, 0, strlen($b64uuid) - 2);

      // lower cases
      return strtolower($b64uuid);
    }

    // ##########################################

    /*
        Create short unique id
      */
    public static function create_short_uid($id = '')
    {
      $short_id = base_convert($id, 10, 36);
      $uuid = SecurityHelper::create_uid();
      $uuid = str_replace("-", "", $uuid);

      if (strlen($short_id) <= 10)
      {
        $short_id = $short_id . substr($uuid, 0, 10 - strlen($short_id));
      }

      return $short_id;
    }

    // ##########################################

    /*
        Create short reference id
      */
    public static function create_short_refid($number = '')
    {
      return base_convert($number, 10, 36);
    }

    // ##########################################

    /*
        Encrypt sensible data
      */
    public static function encrypt($text)
    {
      return empty($text) ? NULL : trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, CRYPT_SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    // ##########################################

    /*
        Decrypt sensible data
      */
    public static function decrypt($text)
    {
      return empty($text) ? NULL : trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, CRYPT_SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
  }
