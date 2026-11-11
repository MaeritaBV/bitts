<?php
/**
 * JSHelper.php
 *
 * Javascript Helper class voor diverse weergave functies in de user interface
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 30 oktober 2026
 */

namespace BitTS\Support;

use BitTS\Support\GeneralHelper;

class JSHelper {

////
// Output a javascript focus part
  public static function javascript_focus($fieldname, $form = '') {
    $script = '<script language="javascript">';
    $script .= 'document.';
    if (!GeneralHelper::is_null($form))
      $script .= $form . '.';
    $script .= $fieldname . '.focus();</script>';
    return $script;
  }

////
// Output a javascript maxlength part (for textareas)
  public static function javascript_maxlength() {
    $script = '<script language="javascript"> ';
    $script .= 'function maxLength(textAreaObject, maximumLength) { ';
    $script .= 'if (textAreaObject.value.length > maximumLength) { ';
    $script .= 'textAreaObject.value = textAreaObject.value.substring(0, maximumLength); ';
    $script .= '} } ';
    $script .= '</script>';
    return $script;
  }

}