<?php
/**
 * role_entry.php
 *
 * Role entry fields
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 10 november 2026
 */

use BitTS\Support\GeneralHelper;
use BitTS\Support\HTMLHelper;
?>
  <!-- role_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="5">
                <?php echo $ROLE_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php }
          if (HTMLHelper::getPOST('action')=='enter_data') {
            echo HTMLHelper::draw_form('role_entry', HTMLHelper::href_link(FILENAME_ADMINISTRATION_ROLES)) . HTMLHelper::create_parameters(array('action'=>'save_data'), array('mPath', 'roles_id', 'projects_id'), 'hidden_field');
          } ?>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_ROLES_NAME; ?>
            </td>
            <td class="item_entry" style="width:150px">
              <?php echo HTMLHelper::draw_input_field('roles_name', '', 'size="1" maxlength="64" style="width: 100%"' . (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_ROLES_DESCRIPTION; ?>
            </td>
            <td class="item_entry" style="width:150px">
              <?php echo HTMLHelper::draw_input_field('roles_description', '', 'size="1" maxlength="255" style="width: 95%"' . (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_ROLES_MANDATORY_TICKET_ENTRY; ?>
            </td>
            <td class="item_entry" style="text-align:left">
              <?php echo HTMLHelper::draw_checkbox_field('roles_mandatory_ticket_entry', true, false, (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CATEGORIES; ?>
            </td>
            <td class="item_entry"  style="width:150px">
              <?php if (in_array(HTMLHelper::getPOST('action'), ['enter_data','delete_entry'])) {
                $temp_category = new category();
                echo HTMLHelper::html_select('categories_id', GeneralHelper::get_partial_array($temp_category->listing, 'id', 'name'), HTMLHelper::getPOST('action')=='enter_data', HTMLHelper::getPOST('categories_id'), 'size="1" maxlength="20" style="width: 100%"');
              } else {
                echo HTMLHelper::html_select('categories_id', array(), false);
              } ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="5">
              <?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" style="text-align:left">
              <?php if (HTMLHelper::getPOST('projects_id') !== null && !in_array(HTMLHelper::getPOST('action'), ['enter_data','delete_entry'])) {
                echo HTMLHelper::draw_form('fnew', HTMLHelper::href_link(FILENAME_ADMINISTRATION_ROLES)) . HTMLHelper::create_parameters(array('action'=>'enter_data'), array('mPath', 'projects_id'), 'hidden_field');
                echo HTMLHelper::image_submit('button_new.gif', TEXT_ENTRY_NEW, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_new_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
            <td class="item_entry" colspan="4" style="text-align:right">
              <?php if (HTMLHelper::getPOST('action')=='enter_data') {
                echo HTMLHelper::image_submit('button_save.gif', TEXT_ENTRY_SAVE, 'style="vertical-align:middle"');
                echo '</form>';
              } else if (HTMLHelper::getPOST('action')=='delete_entry') {
                echo TEXT_ENTRY_DELETE_QUESTION . '&nbsp;';
                echo HTMLHelper::draw_form('delete_entry_confirm', HTMLHelper::href_link(FILENAME_ADMINISTRATION_ROLES)) . HTMLHelper::create_parameters(array('action'=>'delete_entry_confirmed'), array('mPath', 'roles_id', 'projects_id'), 'hidden_field');
                echo HTMLHelper::image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_save_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              }
              echo '&nbsp;';
              if (in_array(HTMLHelper::getPOST('action'), ['enter_data','delete_entry'])) {
                echo HTMLHelper::draw_form('fcancel', HTMLHelper::href_link(FILENAME_ADMINISTRATION_ROLES)) . HTMLHelper::create_parameters(array(), array('mPath', 'projects_id'), 'hidden_field');
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
  <!-- role_entry_eof //-->
