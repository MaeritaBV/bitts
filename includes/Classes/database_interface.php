<?php
/**
 * database_interface.php
 * 
 * Interface to database specific functions
 * 
 * PHP version 5 >= 5.3
 * 
 * @package BitTS - BART it TimeSheet
 * @author  Erwin Beukhof
 * @version 14 may 2017
 */

interface database_interface {
  public function connect();
  public function select_db($database);
  public function error();
  public function errno();
  public static function escape_string($string);
  public function query($query);
  public function fetch_array($result, $array_type);
  public function fetch_row($result);
  public function fetch_assoc($result);
  public function fetch_object($result);
  public function num_rows($result);
  public function data_seek($query, $row_number);
  public function insert_id();
  public function free_result($db_query);
  public function fetch_fields($db_query);
  public function input($string);
  public function close();
  public function get_link();
  public function is_connected();
}