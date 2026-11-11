<?php
/**
 * employee_entry.php
 *
 * Employee entry fields
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 09 november 2026
 */

use BitTS\Support\GeneralHelper;
use BitTS\Support\HTMLHelper;
?>
  <!-- customer_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="5">
                <?php echo $EMPLOYEE_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php }
          if (isset($_POST['action']) && $_POST['action']=='enter_data') {
            echo HTMLHelper::draw_form('employee_entry', HTMLHelper::href_link(FILENAME_ADMINISTRATION_EMPLOYEES)) . HTMLHelper::create_parameters(array('action'=>'save_data'), array('mPath', 'employees_id', 'employees_status'), 'hidden_field');
          } ?>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_EMPLOYEES_ID; ?>
            </td>
            <td class="item_entry" style="width:200px">
              <?php echo HTMLHelper::draw_input_field('employees_id', '', 'size="1" maxlength="10" style="width: 100%"' . (isset($_POST['action'])&&$_POST['action']=='enter_data'&&$_POST['employees_status']=='new'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_EMPLOYEES_RESET_PASSWORD; ?>
            </td>
            <td class="item_entry" style="text-align:right;width:25px">
              <?php echo HTMLHelper::draw_checkbox_field('employees_reset_password', true, false, ((isset($_POST['action'])&&$_POST['action']=='enter_data'&&$_POST['employees_status']!='new')?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_EMPLOYEES_LOGIN; ?>
            </td>
            <td class="item_entry" style="width:200px">
              <?php echo HTMLHelper::draw_input_field('employees_login', '', 'size="1" maxlength="16" style="width: 100%"' . (isset($_POST['action'])&&$_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry" colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_EMPLOYEES_FULLNAME; ?>
            </td>
            <td class="item_entry" style="width:200px">
              <?php echo HTMLHelper::draw_input_field('employees_fullname', '', 'size="1" maxlength="64" style="width: 100%"' . (isset($_POST['action'])&&$_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry" colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_PROFILE; ?>
            </td>
            <td class="item_entry" style="width:200px">
              <?php if (isset($_POST['action']) && ($_POST['action']=='enter_data' || $_POST['action']=='delete_entry')) {
                $temp_profile = new profile();
                echo HTMLHelper::html_select('profiles_id', GeneralHelper::get_partial_array($temp_profile->listing, 'id', 'name'), (isset($_POST['action']) && $_POST['action']=='enter_data'), HTMLHelper::getPOST('profiles_id'), 'size="1" maxlength="32" style="width: 100%"');
              } else {
                echo HTMLHelper::html_select('profiles_id', array(), false);
              } ?>
            </td>
            <td class="item_entry" colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td class="item_entry" colspan="5">
              <?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" style="text-align:left">
              <?php if (!isset($_POST['action']) || ($_POST['action']!='enter_data'&&$_POST['action']!='delete_entry')) {
                echo HTMLHelper::draw_form('fnew', HTMLHelper::href_link(FILENAME_ADMINISTRATION_EMPLOYEES)) . HTMLHelper::create_parameters(array('action'=>'enter_data', 'employees_status'=>'new'), array('mPath'), 'hidden_field');
                echo HTMLHelper::image_submit('button_new.gif', TEXT_ENTRY_NEW, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_new_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
            <td class="item_entry" colspan="4" style="text-align:right">
              <?php if (isset($_POST['action']) && $_POST['action']=='enter_data') {
                echo HTMLHelper::image_submit('button_save.gif', TEXT_ENTRY_SAVE, 'style="vertical-align:middle"');
                echo '</form>';
              } else if (isset($_POST['action']) && $_POST['action']=='delete_entry') {
                echo TEXT_ENTRY_DELETE_QUESTION . '&nbsp;';
                echo HTMLHelper::draw_form('delete_entry_confirm', HTMLHelper::href_link(FILENAME_ADMINISTRATION_EMPLOYEES)) . HTMLHelper::create_parameters(array('action'=>'delete_entry_confirmed'), array('mPath', 'employees_id'), 'hidden_field');
                echo HTMLHelper::image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_save_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              }
              echo '&nbsp;';
              if (isset($_POST['action']) && ($_POST['action']=='enter_data'||$_POST['action']=='delete_entry')) {
                echo HTMLHelper::draw_form('fcancel', HTMLHelper::href_link(FILENAME_ADMINISTRATION_EMPLOYEES)) . HTMLHelper::create_parameters(array(), array('mPath'), 'hidden_field');
                echo HTMLHelper::image_submit('button_cancel.gif', TEXT_ENTRY_CANCEL, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_cancel_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!-- activity_entry_eof //-->
