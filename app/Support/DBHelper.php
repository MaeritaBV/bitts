<?php
/**
 * DBHelper.php
 *
 * Helper class DBHelper for a number of specific database related functions
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 05 november 2026
 */

namespace BitTS\Support;

class DBHelper {

  public static function number_db_to_user($number = 0, $precision = 0) {
    return number_format($number, $precision, NUMBER_DECIMAL_POINT, NUMBER_THOUSANDS_SEPARATOR);
  }

}