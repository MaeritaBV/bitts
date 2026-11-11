<?php
/**
 * GeneralHelper.php
 *
 * General helper class GeneralUtil for misc. general functions
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 08 november 2026
 */

namespace BitTS\Support;

class GeneralHelper {

  /**
   * Controle of de waarde van een variabele zich tussen twee grenswaarden bevindt, inclusief de grenswaarden
   *
   * @param mixed    $var        Op waarde te controleren variabele
   * @param mixed    $startValue Onderste grenswaarde
   * @param mixed    $endValue   Bovenste grenswaarde
   * @return boolean             TRUE indien de waarde van de variabele zich op of tussen de grenswaarden bevindt
   */
  public static function isBetween($var, $startValue, $endValue) {
    return ($startValue < $endValue?$var >= $startValue && $var <= $endValue:$var >= $endValue && $var <= $startValue);
  } // public static function isBetween($var, $startValue, $endValue)

  /**
   * Verkrijgen van huidige Git revisie
   *
   * @return string Git revisie (hash)
   */
  public static function getGitRevision() {
    $rev = trim(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.git/HEAD"));
    if (substr($rev, 0, 4) == 'ref:') {
      $explodedrev = explode('/', $rev);
      $ref         = end($explodedrev);
      $rev         = trim(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.git/refs/heads/{$ref}"));
    } // if (substr($rev, 0, 4) == 'ref:')
    return $rev;
  } // public static function getGitRevision()

  // Stop from parsing any further PHP code
  public static function exit() {
   SessionHelper::session_close();
   exit();
  }

  public static function is_null($value) {
    if (is_object($value)) {
      return ($value === null);
    } else if (is_array($value)) {
      return (sizeof($value) == 0);
    } else {
      return (!isset($value) || ($value == '') || (strtolower($value) == 'null') || (strlen(trim($value)) == 0));
    }
  }

  public static function get_partial_array($array = null, $key = '', $value = '') {
    $key_array = array();
    $value_array = array();
    $result_array = array();
    foreach ($array as $row) {
      if (is_object($row)) {
        array_push($key_array, $row->$key);
        array_push($value_array, $row->$value);
      } else {
        array_push($key_array, $row[$key]);
        array_push($value_array, $row[$value]);
      }
    }
    // Only combine arrays if they contain any values
    if (sizeof($key_array)!=0)
      $result_array = array_combine($key_array, $value_array);
    return $result_array;
  }

  public static function sanitize_string($string) {
    $string = preg_replace('/\s\s+/', ' ', trim((isset($string)?$string:'')));
    return preg_replace("/[<>]/", '_', $string);
  }

    // Get a certain array from within an array
  public static function get_array_from_array($array = null, $keyname = '', $criterium = '') {
  	foreach ($array as $row) {
      if ($row->$keyname == $criterium) {
        return $row;
      }
    }
    return array();
  }

  // Break a word in a string if it is longer than a specified length ($len)
  public static function break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i=0, $n=strlen($string); $i<$n; $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }

  // Case-insensitive str_replace()
  public static function stri_replace($find, $replace, $string) {
    $parts = explode( strtolower($find), strtolower($string) );

    $pos = 0;

    foreach( $parts as $key=>$part ){
      $parts[ $key ] = substr($string, $pos, strlen($part));
      $pos += strlen($part) + strlen($find);
    }

    return( join( $replace, $parts ) );
  }

} // class GeneralUtil