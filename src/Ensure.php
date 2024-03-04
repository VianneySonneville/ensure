<?php
namespace Ensure;

class Ensure {

  /**
   * @description  Escapes special characters in a string for use in an SQL statement
   * @param String $data
   * @param String $with, default is mysqli
   * @example Ensure::sanitize($data);
   * @return String $data, String with characters that need to be escaped
   */
  static function sanitize(String $data, String $with = "mysqli"){
    if (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) {
      switch ($with) {
        case "mysqli":
          $data = \mysqli_real_escape_string(stripslashes($data));
          break;
        case "sqlite":
          $data = \sqlite_escape_string(stripslashes($data));
          break;
        case "pdo":
          $data = \addslashes(stripslashes($data));
          break;
        case "mysql":
          $data = \mysql_real_escape_string(stripslashes($data));
          break;
      }
    } else {
      switch ($with) {
        case "mysqli":
          $data = \mysqli_real_escape_string($data);
          break;
        case "sqlite":
          $data = \sqlite_escape_string($data);
          break;
        case "pdo":
          $data = \addslashes($data);
          break;
        case "mysql":
          $data = \mysql_real_escape_string($data);
          break;
      }
    }

    return $data;
  }
}