<?php
/****************************************************************************
 * CODE FILE   : sessions.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 17 december 2007
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
      $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
      $database = $_SESSION['database'];
      //$database = new database();
      //$database->connect();
      $value_query = $database->query("select sessions_value from " . TABLE_SESSIONS . " where sessions_key = '" . $database->input($key) . "' and sessions_expiry > '" . time() . "'");
      $value = $database->fetch_array($value_query);
      //$database->close();

      if (isset($value['value'])) {
        return $value['value'];
      }

      return false;
    }

    function _sess_write($key, $val) {
      global $SESS_LIFE;

      $expiry = time() + $SESS_LIFE;
      $value = $val;

      $database = $_SESSION['database'];
      //$database = new database();
      //$database->connect();
      $check_query = $database->query("select count(*) as total from " . TABLE_SESSIONS . " where sessions_key = '" . $database->input($key) . "'");
      $check = $database->fetch_array($check_query);

      if ($check['total'] > 0) {
        $result = $database->query("update " . TABLE_SESSIONS . " set sessions_expiry = '" . $database->input($expiry) . "', sessions_value = '" . $database->input($value) . "' where sessions_key = '" . $database->input($key) . "'");
      } else {
        $result = $database->query("insert into " . TABLE_SESSIONS . " values ('" . $database->input($key) . "', '" . $database->input($expiry) . "', '" . $database->input($value) . "')");
      }
      //$database->close();
      return $result;
    }

    function _sess_destroy($key) {
      $database = $_SESSION['database'];
      //$database = new database();
      //$database->connect();
      $result = $database->query("delete from " . TABLE_SESSIONS . " where sessions_key = '" . tep_db_input($key) . "'");
      //$database->close();
      return $result;
    }

    function _sess_gc($maxlifetime) {
      $database = $_SESSION['database'];
      //$database = new database();
      //$database->connect();
      $database->query("delete from " . TABLE_SESSIONS . " where sessions_expiry < '" . time() . "'");
      //$database->close();
      return true;
    }

    @session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

  function tep_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function tep_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  function tep_session_close() {
    if (PHP_VERSION >= '4.0.4') {
      return session_write_close();
    } elseif (function_exists('session_close')) {
      return session_close();
    }
  }
