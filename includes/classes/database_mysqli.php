<?php
/**
 * database_mysqli.php
 * 
 * Mysql server implementation of the database interface (MySQLi)
 * 
 * PHP version 5 >= 5.3
 * 
 * @package BitTS - BART it TimeSheet
 * @author  Erwin Beukhof
 * @version 22 october 2026
 */

// Requires interface database_interface
require DIR_WS_CLASSES . 'database_interface.php';

class database_mysqli implements database_interface {
  private $link;

  public function __construct() {
    define('DATE_FORMAT_DATABASE', 'yyyy-MM-dd');
  }

  public function connect($host='', $user='', $password='', $database=null, $port=null, $socket=null) {
    $this->link = mysqli_connect($host, $user, $password, $database, $port, $socket);
  } // public function connect($host='', $username='', $password='', $database=null, $port=null, $socket=null)

  public function select_db($database) {
    mysqli_select_db($this->link, $database);
  } // public function select_db($database)

  public function error() {
    return mysqli_error($this->link);
  } // public function error()

  public function errno() {
    return mysqli_errno($this->link);
  } // public function errno()

  public static function escape_string($string) {
    return mysqli_real_escape_string($this->link, $string);
  } // public static function escape_string($string)

  public function query($query) {
    return mysqli_query($this->link, $query);
  } // public function query($query)

  public function fetch_array($result, $resulttype=MYSQLI_BOTH) {
    return mysqli_fetch_array($result, $resulttype);
  } // public function fetch_array($result, $resulttype=MYSQLI_BOTH)

  public function fetch_row($result) {
    return mysqli_fetch_row($result);
  } // public function fetch_row($result)

  public function fetch_assoc($result) {
    return mysqli_fetch_assoc($result);
  } // public function fetch_assoc($result)

  public function fetch_object($result) { 
    return mysqli_fetch_object($result);
  } // public function fetch_object($result)

  public function num_rows($result) {
    return mysqli_num_rows($result);
  } // public function num_rows($result)

  public function data_seek($result, $offset) {
    return mysqli_data_seek($result, $offset);
  } // public function data_seek($result, $offset)

  public function insert_id() {
    return mysqli_insert_id($this->link);
  } // public function insert_id()

  public function free_result($result) {
    return mysqli_free_result($result);
  } // public function free_result($result)

  public function fetch_fields($result) {
    return mysqli_fetch_field($result);
  } // public function fetch_fields($result)

  public function input($string) {
    if (function_exists('mysqli_real_escape_string')) {
      return mysqli_real_escape_string($this->link, $string);
    } // if (function_exists('mysqli_real_escape_string'))
    return addslashes($string);
  } // public function input($string)

  public function close() {
    return mysqli_close($this->link);
  } // public function close()

  public function get_link() {
    return $this->link;
  } // public function get_link()

  /**
   * Check for a valid connection with the database
   * 
   * @return boolean true on valid connectie, false when no link or valid connection is available
   */
  public function is_connected() {
    if (!$this->link) {
      return false;
    } else { // if (!$this->link)
      return @mysqli_ping($this->link);
    } // if (!$this->link)
  } // public function is_connected()

} // class database_mysqli implements database_interface
