<?php
/****************************************************************************
 * CODE FILE   : activity_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 11 november 2026
 * Description : Activity entry fields
 *               Data validation sequence
 *               Storing of entered data (via timesheet object)
 */

use BitTS\Support\DateTimeHelper;
use BitTS\Support\DBHelper;
use BitTS\Support\GeneralHelper;
use BitTS\Support\HTMLHelper;

// When action==save_data: verify entered data and save if OK / set errorlevel when NOK
$error_level = 0;
if (HTMLHelper::getPOST('action') == 'copy_activity') {
  $former_activity_id = $_SESSION['timesheet']->get_former_activity_id($_SESSION['employee']->id, HTMLHelper::getPOST('selected_date'));
  switch ($former_activity_id) {
    case -1:
      // Activity data cannot be used on the selected date
      // Set errorlevel and continue as if the date has just been selected
      $_POST['action'] = 'select_project';
      $error_level = 33;
      break;
    case 0:
      // No activity data available
      $_POST['action'] = 'select_project';
      $error_level = 64;
      break;
    default:
      // Retrieve activity details
      $_POST['activity_id'] = 0;
      $_POST['projects_id'] = $_SESSION['timesheet']->former_activity->projects_id;
      $_POST['roles_id'] = $_SESSION['timesheet']->former_activity->roles_id;
      $_POST['activity_amount'] = DBHelper::number_db_to_user($_SESSION['timesheet']->former_activity->amount, 2);
      $_POST['tariffs_id'] = $_SESSION['timesheet']->former_activity->tariff->id;
      $_POST['activity_travel_distance'] = "" . $_SESSION['timesheet']->former_activity->travel_distance;
      $_POST['activity_travel_description'] = "" . $_SESSION['timesheet']->former_activity->travel_description;
      $_POST['activity_expenses'] = DBHelper::number_db_to_user($_SESSION['timesheet']->former_activity->expenses, 2);
      $_POST['activity_ticket_number'] = $_SESSION['timesheet']->former_activity->ticket_number;
      $_POST['activity_comment'] = $_SESSION['timesheet']->former_activity->comment;
      $_POST['action'] = 'enter_data';
  }
}
if (HTMLHelper::getPOST('action') == 'save_data') {
  // Check for data format and required fields
  // change action when not everything is filled-in
  if (HTMLHelper::getPOST('selected_date') == '') {
    $_POST['action'] = '';
  } else if (HTMLHelper::getPOST('projects_id') == '') {
    $_POST['action'] = 'select_project';
    $error_level = 1;
  } else if (HTMLHelper::getPOST('roles_id') == '') {
    $_POST['action'] = 'select_role';
    $error_level = 2;
  } else if (!activity::validate('amount', HTMLHelper::getPOST('activity_amount'))) {
    $error_level = 3;
  } else if (HTMLHelper::getPOST('tariffs_id') == '') {
    $error_level = 4;
  } else if (!activity::validate('travel_distance', HTMLHelper::getPOST('activity_travel_distance'))) {
    $error_level = 5;
  } else if (!activity::validate('expenses', HTMLHelper::getPOST('activity_expenses'))) {
    $error_level = 6;
  } else if (activity::ticket_entry_is_required(HTMLHelper::getPOST('tariffs_id')) && GeneralHelper::is_null(HTMLHelper::getPOST('activity_ticket_number'))) { // no ticket number when required
    $error_level = 7;
  } else if (!project::hours_fit_in_calculated_amount(HTMLHelper::getPOST('projects_id'), activity::format('amount', HTMLHelper::getPOST('activity_amount'))-HTMLHelper::getPOST('original_activity_amount'), HTMLHelper::getPOST('selected_date')) && (HTMLHelper::getPOST('error_level_history')!=32 || HTMLHelper::getPOST('previous_activity_amount')!=activity::format('amount', HTMLHelper::getPOST('activity_amount')))) {
    // Exceeding calculated hours, the definite value is calculated by subtracting the original value
    // (original_activity_amount, only available when editing an existing activity) from the entered value.
    // When OK-ing the 2nd time (error_level_history==32) without changing the activity_amount value
    // (tested with previous_activity_amount), the entry will be saved.
    $error_level = 32;
  } else {
    // OK, entry can be saved
    $_SESSION['timesheet']->save_activity(HTMLHelper::getPOST('activity_id'),
                                          HTMLHelper::getPOST('selected_date'),
                                          HTMLHelper::getPOST('activity_amount'),
                                          HTMLHelper::getPOST('tariffs_id'),
                                          HTMLHelper::getPOST('activity_travel_distance'),
                                          HTMLHelper::getPOST('activity_travel_description'),
                                          HTMLHelper::getPOST('activity_expenses'),
                                          HTMLHelper::getPOST('activity_ticket_number'),
                                          HTMLHelper::getPOST('activity_comment'));

    // Clear all values except mPath and period
    foreach($_POST as $key=>$value) {
      if ($key != 'mPath' && $key != 'period' && $key != 'sort_order') {
        $_POST[$key] = '';
      }
    }

    // Reload the timesheet object in order to
    // update the activity listing below
    $_SESSION['timesheet'] = new timesheet(0, $_SESSION['employee']->id, HTMLHelper::getPOST('period'), true, HTMLHelper::getPOST('sort_order'));
  }
}
$_POST['error_level_history'] = $error_level;
$_POST['previous_activity_amount'] = activity::format('amount', HTMLHelper::getPOST('activity_amount','')); ?>
  <!-- activity_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td valign="top">
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><?php require(DIR_WS_INCLUDES . 'calendar.php'); ?></td>
          </tr>
          <tr>
            <td><?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td align="center">
              <?php if (HTMLHelper::getPOST('selected_date', '') != '') {
                echo HTMLHelper::draw_form('activity_copy', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('action'=>'copy_activity'), array('mPath', 'period', 'sort_order', 'selected_date', 'activity_id'), 'hidden_field');
                echo HTMLHelper::image_submit('button_copy.gif', TEXT_ACTIVITY_COPY);
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_copy_disabled.gif');
              } ?>
            </td>
        </table>
      </td>
      <td>
        <?php echo HTMLHelper::draw_separator('pixel_trans.gif', '10'); ?>
      </td>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" width="250" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="2">
                <?php echo $ACTIVITY_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php } ?>
          <tr>
            <td align="center" class="item_entry" colspan="2">
              <?php if (HTMLHelper::getPOST('selected_date', '')!='') {
                echo TEXT_ACTIVITY_ENTRY_SELECTED_DATE . DateTimeHelper::strftime(DATE_FORMAT_SHORT, HTMLHelper::getPOST('selected_date'));
              } else {
                echo TEXT_ACTIVITY_ENTRY_NO_DATE_SELECTED;
              } ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_ACTIVITY_PROJECTNAME; ?>
            </td>
            <td class="item_entry">
              <?php echo HTMLHelper::draw_form('project_selection', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('action'=>'select_role'), array('mPath','period','sort_order','selected_date', 'activity_id'), 'hidden_field');
              if (in_array(HTMLHelper::getPOST('action'), array('select_project','select_role','enter_data','save_data'))) {
                echo HTMLHelper::html_select('projects_id', GeneralHelper::get_partial_array(project::get_selectable_projects($_SESSION['employee']->id, DateTimeHelper::strftime('%Y-%m-%d', HTMLHelper::getPOST('selected_date'))), 'projects_id', 'projects_name'), TRUE, HTMLHelper::getPOST('projects_id'), 'onChange="this.form.submit();" size="1" style="width: 99%"');
              } else {
                echo HTMLHelper::html_select('projects_id', array(), FALSE, 0, 'onChange="this.form.submit();" size="1" style="width: 99%"');
              }
              ?></form>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_ACTIVITY_ROLENAME; ?>
            </td>
            <td class="item_entry">
              <?php echo HTMLHelper::draw_form('role_selection', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('action'=>'enter_data'), array('mPath','period','sort_order','selected_date', 'projects_id', 'activity_id'), 'hidden_field');
              if (in_array(HTMLHelper::getPOST('action'), array('select_role','enter_data','save_data'))) {
                echo HTMLHelper::html_select('roles_id', GeneralHelper::get_partial_array(role::get_selectable_roles($_SESSION['employee']->id, DateTimeHelper::strftime('%Y-%m-%d', HTMLHelper::getPOST('selected_date')),HTMLHelper::getPOST('projects_id')), 'roles_id', 'roles_name'), TRUE, HTMLHelper::getPOST('roles_id'), 'onChange="this.form.submit();" size="1" style="width: 99%"');
              } else {
                echo HTMLHelper::html_select('roles_id', array(), FALSE, 0, 'onChange="this.form.submit();" size="1" style="width: 99%"');
              }
              ?></form>
            </td>
          </tr>
          <?php if (in_array(HTMLHelper::getPOST('action'),array('enter_data','save_data'))) {
            echo HTMLHelper::draw_form('activity_entry', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('action'=>'save_data'), array('mPath','period','sort_order','selected_date','projects_id','roles_id', 'activity_id', 'error_level_history', 'previous_activity_amount', 'original_activity_amount'), 'hidden_field');
          } ?>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_ACTIVITY_AMOUNT . ' &amp; ' . TEXT_ACTIVITY_UNIT; ?>
            </td>
            <td class="item_entry">
              <?php echo HTMLHelper::draw_input_field('activity_amount', '', 'size="1" maxlength="6" style="width: 20%"' . (in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))?'':' disabled'));
              if (in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))) {
                echo HTMLHelper::html_select('tariffs_id', GeneralHelper::get_partial_array(tariff::get_selectable_tariffs($_SESSION['employee']->id, DateTimeHelper::strftime('%Y-%m-%d', HTMLHelper::getPOST('selected_date')), HTMLHelper::getPOST('roles_id')), 'tariffs_id', 'units_name'), TRUE, HTMLHelper::getPOST('tariffs_id'), 'size="1" style="width: 76%"');
              } else {
                echo HTMLHelper::html_select('tariffs_id', array(), FALSE, 0, 'size="1" style="width: 76%"');
              }
              ?></select>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              &nbsp;
            </td>
            <td class="item_entry">
              <table border="0" cellspacing="0" cellpadding="2" width="250" class="item_entry">
                <tr>
                  <td width="50%" class="item_entry"><?php echo TEXT_ACTIVITY_TRAVELDISTANCE; ?></td>
                  <td width="10%" class="item_entry"><?php echo HTMLHelper::draw_input_field('activity_travel_distance', '', 'size="1" maxlength="5" style="width: 35px"' . (in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))?'':' disabled')); ?></td>
                  <td colspan="2" width="40%" class="item_entry"><?php echo HTMLHelper::draw_input_field('activity_travel_description', '', 'size="1" maxlength="64" style="width: 92%"' . (in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))?'':' disabled')); ?></td>
                </tr>
                <tr>
                  <td width="50%" class="item_entry"><?php echo TEXT_ACTIVITY_EXPENSES; ?></td><td colspan="4" width="50%" class="item_entry"><?php echo HTMLHelper::draw_input_field('activity_expenses', '', 'size="1" maxlength="7" style="width: 95%"' . (in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))?'':' disabled')); ?></td>
                </tr>
                <tr>
                  <td width="50%" class="item_entry"><?php echo TEXT_ACTIVITY_TICKETNUMBER; ?></td>
                  <td colspan="2" width="50%" class="item_entry"><?php echo HTMLHelper::draw_input_field('activity_ticket_number', '', 'size="1" maxlength="16" style="width: 95%"' . (in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))?'':' disabled')); ?></td>
                  <td width="22px" class="item_entry">
                    <?php if (ENABLE_TICKET_DATABASE_LOOKUPS && in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))) {
                      echo HTMLHelper::image_button('button_lookup.gif', TEXT_ACTIVITY_TICKETNUMBER_LOOKUP, 'onClick="fetchTicketNrAndDescription(\'' . DateTimeHelper::strftime('%Y-%m-%d', HTMLHelper::getPOST('selected_date')) . '\');"');
                      //echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_lookup.gif', '', '', '', 'onClick="fetchTicketNrAndDescription(\'' . DateTimeHelper::strftime('%Y-%m-%d', HTMLHelper::getPOST('selected_date')) . '\');"');
                    } else {
                      echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_lookup_disabled.gif');
                    } ?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_ACTIVITY_COMMENT; ?>
            </td>
            <td class="item_entry">
              <?php //echo HTMLHelper::draw_input_field('activity_comment', '', 'size="1" maxlength="255" style="width: 100%"' . (in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))?'':' disabled'));
                echo HTMLHelper::draw_textarea_field('activity_comment', 'on', 64, 4, HTMLHelper::getPOST('activity_comment',''), 'id="activity_comment" style="width: 96%; resize: none;" onKeyUp="maxLength(this, 255)"' . (in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="2">
              <?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td align="right" class="item_entry" colspan="2">
              <?php if (in_array(HTMLHelper::getPOST('action'), array('enter_data','save_data'))) {
                echo HTMLHelper::image_submit('button_save.gif', TEXT_ENTRY_SAVE);
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_save_disabled.gif');
              }
              echo '&nbsp;';
              if (in_array(HTMLHelper::getPOST('action'), ['select_project','select_role','enter_data','save_data'])) {
                echo HTMLHelper::draw_form('fcancel', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array(), array('mPath','period','sort_order'), 'hidden_field');
                echo HTMLHelper::image_submit('button_cancel.gif', TEXT_ENTRY_CANCEL);
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_cancel_disabled.gif');
              } ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!-- activity_entry_eof //-->
