<?php
/**
 * HTMLHelper.php
 *
 * HTML Helper class voor diverse weergave functies in de user interface
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 11 november 2026
 */

namespace BitTS\Support;

use BitTS\Support\GeneralHelper;

require_once DIR_WS_SUPPORT . 'GeneralHelper.php';

class HTMLHelper {

  /**
   * Geeft de waarde van een POST variabele of een default waarde indien de variabele
   * niet is geset.
   *
   * @param  string $key        Key naam van de $_POST variabele
   * @param  string $default    Default waarde die wordt gehanteerd indien de betreffende
   *                            $_POST variabele niet is geset
   * @param  bool   $persistent Indien de gevraagde $_POST variabele niet is geset, wordt
   *                            deze aangemaakt met de waarde van $default als actuele
   *                            waarde
   * @return mixed
   */
  public static function getPOST(string $key, ?string $default = null, bool $persistent = false) {
    return self::getPOSTorSESSION($_POST, $key, $default, $persistent);
  } // public static function getPOST($key, $default = null, $persistent = false)

  /**
   * Geeft de waarde van een SESSION variabele of een default waarde indien de variabele
   * niet is geset.
   *
   * @param  string $key        Key naam van de $_SESSION variabele
   * @param  string $default    Default waarde die wordt gehanteerd indien de betreffende
   *                            $_POST variabele niet is geset
   * @param  bool   $persistent Indien de gevraagde $_SESSION variabele niet is geset, wordt
   *                            deze aangemaakt met de waarde van $default als actuele
   *                            waarde
   * @return mixed
   */
  public static function getSESSION(string $key, ?string $default = null, bool $persistent = false) {
    return self::getPOSTorSESSION($_SESSION, $key, $default, $persistent);
  } // public static function getSESSION($key, $default = null, $persistent = false)

  /**
   * Geeft de waarde van een POST of SESSION variabele of een default waarde indien de variabele
   * niet is geset.
   *
   * @param  mixed  $post_or_session $_POST of $_SESSION object
   * @param  string $key             Key naam van de $_POST of $_SESSION variabele
   * @param  string $default         Default waarde die wordt gehanteerd indien de betreffende
   *                                 $_POST variabele niet is geset
   * @param  bool   $persistent      Indien de gevraagde $_POST variabele niet is geset, wordt
   *                                 deze aangemaakt met de waarde van $default als actuele
   *                                 waarde
   * @return mixed
   */
  private static function getPOSTorSESSION(&$post_or_session, string $key, ?string $default, ?bool $persistent) {
    if (isset($post_or_session[$key])) {
      return $post_or_session[$key];
    } else { // if (isset($post_or_session[$key]))
      if ($persistent) {
        $post_or_session[$key] = $default;
      } // if ($persistent)
      return $default;
    } // if (isset($post_or_session[$key]))
  } // private static function getPOSTorSESSION($post_or_session, $key, $default = null, $persistent = false)

  // Redirect to another page or site
  public static function redirect($url) {
    if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) { 
      self::redirect(self::href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }
    if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
      if (substr($url, 0, strlen(HTTP_SERVER)) == HTTP_SERVER) { // NONSSL url
        $url = HTTPS_SERVER . substr($url, strlen(HTTP_SERVER)); // Change it to SSL
      }
    }
    header('Location: ' . $url);
    GeneralHelper::exit();
  }

    // The HTML href link wrapper function
  public static function href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true) {
    global $session_started, $SID;

    if ($page === null) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link! (' . $page . ')<br><br>');
    }

    switch ($connection) {
      case 'NONSSL':
        if (ENABLE_SSL == true) {
          $link = HTTPS_SERVER . DIR_WS_APPLICATION;
        } else {
          $link = HTTP_SERVER . DIR_WS_APPLICATION;
        }
        break;
      case 'SSL':
        $link = HTTPS_SERVER . DIR_WS_APPLICATION;
        break;
      default:
        die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }

    if (!GeneralHelper::is_null($parameters)) {
      $link .= $page . '?' . self::output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    // Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if (!GeneralHelper::is_null($SID)) {
        $_sid = $SID;
      } elseif ( ( (getenv('HTTPS') != 'on') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( (getenv('HTTPS') == 'on') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = SessionHelper::session_name() . '=' . SessionHelper::session_id();
        }
      }
    }

    if (isset($_sid)) {
      $link .= $separator . self::output_string($_sid);
    }

    return $link;
  }

  public static function output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return self::parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return self::parse_input_field_data($string, $translate);
      }
    }
  }

    public static function output_string_protected($string) {
      return self::output_string($string, false, true);
    }

  // Parse the data used in the html tags to ensure the tags will not break
  public static function parse_input_field_data($data, $parse) {
    return strtr(trim((isset($data)?$data:'')), $parse);
  }

  public static function image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

    // alt is added to the img tag even if it is null to prevent browsers from outputting
    // the image filename as default
    $image = '<img src="' . self::output_string($src) . '" border="0" alt="' . self::output_string($alt) . '"';

    if (!GeneralHelper::is_null($alt)) {
      $image .= ' title="' . self::output_string($alt) . '"';
    }

    //if ( (@CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
    //  if ($image_size = @getimagesize($src)) {
    //    if (empty($width) && !GeneralHelper::is_null($height)) {
    //      $ratio = $height / $image_size[1];
    //      $width = $image_size[0] * $ratio;
    //    } elseif (!GeneralHelper::is_null($width) && empty($height)) {
    //      $ratio = $width / $image_size[0];
    //      $height = $image_size[1] * $ratio;
    //    } elseif (empty($width) && empty($height)) {
    //      $width = $image_size[0];
    //      $height = $image_size[1];
    //    }
    //  } elseif (IMAGE_REQUIRED == 'false') {
    //    return false;
    //  }
    //}

    if (!GeneralHelper::is_null($width) && !GeneralHelper::is_null($height)) {
      $image .= ' width="' . self::output_string($width) . '" height="' . self::output_string($height) . '"';
    }

    if (!GeneralHelper::is_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }

  // Transforms txt in html
  public static function txt2html($text) {
    //Kills double spaces and spaces inside tags.
    while( !( strpos($text,'  ') === FALSE ) ) $text = str_replace('  ',' ',$text);
    $text = str_replace(' >','>',$text);
    $text = str_replace('< ','<',$text);

    //Transforms accents in html entities.
    $text = htmlentities($text);

    //We need some HTML entities back!
    $text = str_replace('&quot;','"',$text);
    $text = str_replace('&lt;','<',$text);
    $text = str_replace('&gt;','>',$text);
    $text = str_replace('&amp;','&',$text);

    //Ajdusts links - anything starting with HTTP opens in a new window
    $text = GeneralHelper::stri_replace("<a href=\"http://","<a target=\"_blank\" href=\"http://",$text);
    $text = GeneralHelper::stri_replace("<a href=http://","<a target=\"_blank\" href=http://",$text);

    //Basic formatting
    $eol = ( strpos($text,"\r") === FALSE ) ? "\n" : "\r\n";
    $html = '<p>'.str_replace("$eol$eol","</p><p>",$text).'</p>';
    $html = str_replace("$eol","<br />\n",$html);
    $html = str_replace("</p>","</p>\n\n",$html);
    $html = str_replace("<p></p>","<p>&nbsp;</p>",$html);

    //Wipes <br> after block tags (for when the user includes some html in the text).
    $wipebr = Array("table","tr","td","blockquote","ul","ol","li");

    for($x = 0; $x < count($wipebr); $x++) {
      $tag = $wipebr[$x];
      $html = GeneralHelper::stri_replace("<$tag><br />","<$tag>",$html);
      $html = GeneralHelper::stri_replace("</$tag><br />","</$tag>",$html);
    }

    return $html;
  }

  public static function href_email_address($address) {
    if (preg_match( "/^ [\d\w\/+!=#|$?%{^&}*`'~-] [\d\w\/\.+!=#|$?%{^&}*`'~-]*@ [A-Z0-9] [A-Z0-9.-]{1,61} [A-Z0-9]\. [A-Z]{2,6}$/ix", $address)) {
      $retval = '<a href="mailto:' . $address . '">' . $address . '</a>';
    } else {
      $retval = $address;
    }
    return $retval;
  }

  public static function create_parameters($new_or_changed_parameters, $relevant_other_parameters = null, $output_type = 'string', $method = 'post') {
  	$result = '';

  	if (is_array($new_or_changed_parameters) && sizeof($new_or_changed_parameters) > 0) {
  	  // Walk through the array
  	  //while (list($key, $value) = each($new_or_changed_parameters)) {
      foreach ($new_or_changed_parameters as $key => $value) {	
	      // Detect if an ampersant is needed (first entry doesn't)
        if ($output_type == 'string') {
          if ($result != '')
            $result .= '&';
          // Retrieve and add parameter
          $result .= $key . '=' . $value;
        } elseif ($output_type == 'hidden_field') {
          if (!(is_bool($value) && $value==false) && !GeneralHelper::is_null($value)) {
            // Boolean value 'false' does not fit very well into these fields
            // Solution is to just drop the value, after POSTing, these
            // values read 'false' anyway.
            $result .= self::draw_hidden_field($key, $value);
          }
        }
      }
    }

  	// Walk through the other array
    for ($index = 0; is_array($relevant_other_parameters) && $index < sizeof($relevant_other_parameters); $index++) {
      // Retrieve parameter value
      $key = $relevant_other_parameters[$index];
      $value = (isset($_POST[$relevant_other_parameters[$index]])?$_POST[$relevant_other_parameters[$index]]:'');
      if ( ( !isset($name) || $key != $name ) && $value != '' ) {
        if ($output_type == 'string') {
          // Detect if an ampersant is needed (first entry doesn't)
      	  if ($result != '')
            $result .= '&';
          // Retrieve and add parameter
          $result .= $key . '=' . $value;
        } elseif ($output_type == 'hidden_field') {
          $result .= HTMLHelper::draw_hidden_field($key, $value);
        }
      }
    }
  	return $result;
  }

  ////
  // The HTML form submit button wrapper function
  // Outputs a button in the selected language or from given path
  public static function image_submit($image, $alt = '', $parameters = '', $image_path = '') {
    $image_submit = '<input type="image" src="';
    if (!GeneralHelper::is_null($image_path)) {
      $image_submit .= self::output_string($image_path . $image);
    } else {
      $image_submit .= self::output_string(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/' . $image);
    }
    $image_submit .= '" border="0" alt="' . self::output_string($alt) . '"';

    if (!GeneralHelper::is_null($alt)) $image_submit .= ' title="' . self::output_string($alt) . '"';

    if (!GeneralHelper::is_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }

  ////
  // The HTML form submit text wrapper function
  public static function href_submit($text, $class = 'submitLinkInfoBoxContents', $parameters = '') {
    // <a href="#" onclick="parentNode.submit()" ' . $parameters . '>' . $text . '</a>
    $href_submit = '<input type="submit" class="' . $class . '" value="' . $text . '"';
    if (!GeneralHelper::is_null($parameters)) $href_submit .= ' ' . $parameters;
    $href_submit .= '>';
    return $href_submit;
  }

  ////
  // Output a function button in the selected language
  public static function image_button($image, $alt = '', $parameters = '') {
    return self::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/' . $image, $alt, '', '', $parameters);
  }

  ////
  // Output a separator either through whitespace, or with an image
  public static function draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return self::image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

  ////
// Output a form
  public static function draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . self::output_string($name) . '" action="' . self::output_string($action) . '" method="' . self::output_string($method) . '"';

    if (!GeneralHelper::is_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }

////
// Output a form input field
  public static function draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = 'true') {
    $field = '<input type="' . self::output_string($type) . '" name="' . self::output_string($name) . '"';

    if ($reinsert_value == 'true') {
      $field .= ' value="' . self::output_string(stripslashes((isset($_POST[$name])?$_POST[$name]:''))) . '"';
    } elseif (!GeneralHelper::is_null($value)) {
      $field .= ' value="' . self::output_string($value) . '"';
    }

    if (!GeneralHelper::is_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Output a selection field - alias function for HTMLHelper::draw_checkbox_field() and HTMLHelper::draw_radio_field()
  public static function draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input style="vertical-align:middle;" type="' . self::output_string($type) . '" name="' . self::output_string($name) . '"';

    $selection .= ' value="' . (is_bool($value)?($value?'true':'false'):$value) . '"';

    if ($checked || (isset($_POST[$name]) && !GeneralHelper::is_null($_POST[$name]) && $_POST[$name]==$value)) {
      $selection .= ' CHECKED';
    }

    if (!GeneralHelper::is_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= '>';

    return $selection;
  }

////
// Output a SELECT object
  public static function html_select($name, $options = null, $enabled = true, $selected_value = '', $parameters = 'onChange="this.form.submit();" size="1" style="width: 100%"') {
    $retval = '<select name="' . $name . '" ' . $parameters;
    if (!$enabled) {
      $retval .= 'disabled';
    }
    $retval .= '>';

    if (!GeneralHelper::is_null($options)) {
      if ($selected_value=='')
        $retval .= '<option value="" selected disabled>' . TEXT_ACTIVITY_ENTRY_SELECT . '</option>';
      //while (list($key, $value) = each($options)) {
      foreach ($options as $key => $value) {
        $retval .= '<option value="' . $key . '"' . ($selected_value==$key?' selected':'') . '>' . $value . '</option>';
      }
    }

    return $retval . '</select>';
  }

////
// Output a form checkbox field
  public static function draw_checkbox_field($name, $value = true, $checked = false, $parameters = '') {
    return self::draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  public static function draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return self::draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }

////
// Output a form textarea field
  public static function draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea name="' . self::output_string($name) . '" wrap="' . self::output_string($wrap) . '" cols="' . self::output_string($width) . '" rows="' . self::output_string($height) . '"';

    if (!GeneralHelper::is_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= self::output_string_protected(stripslashes($GLOBALS[$name]));
    } elseif (!GeneralHelper::is_null($text)) {
      $field .= self::output_string_protected($text);
    }

    $field .= '</textarea>';

    return $field;
  }

  ////
  // Output a form hidden field
  public static function draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . self::output_string($name) . '"';

    if (!GeneralHelper::is_null($value)) {
      $field .= ' value="' . self::output_string($value) . '"';
    } elseif (isset($GLOBALS[$name])) {
      $field .= ' value="' . self::output_string(stripslashes($GLOBALS[$name])) . '"';
    }

    if (!GeneralHelper::is_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

} // class HTMLUtil