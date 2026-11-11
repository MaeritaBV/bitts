<?php
/**
 * timeregistration.php
 *
 * Time registration form
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 08 november 2026
 */

// application_top //
require '../includes/application_top.php';

use BitTS\Support\DateTimeHelper;
use BitTS\Support\DBHelper;
use BitTS\Support\GeneralHelper;
use BitTS\Support\HTMLHelper;
use BitTS\Support\JSHelper;

  // Check if user is logged in. If not, redirect to login page
  if (GeneralHelper::is_null($_SESSION['employee']))
    HTMLHelper::redirect(HTMLHelper::href_link(FILENAME_LOGIN));
  // Check if the user is allowed to view this page
  if (!$_SESSION['employee']->profile->right['timeregistration'])
    HTMLHelper::redirect(HTMLHelper::href_link(FILENAME_DEFAULT));

  // Create a new timesheet object with id == 0
  // If a timesheet already exists for this employee and period, the timesheet class will automatically
  // retrieve the correct id and put this into $_SESSION['timesheet']->id
  if (GeneralHelper::is_null(HTMLHelper::getPOST('period', null))) {
    $_POST['period'] = DateTimeHelper::dateToPeriod();
  }

  // Make sure there is a sort order to start with
  if (GeneralHelper::is_null(HTMLHelper::getPOST('sort_order', null))) {
    $_POST['sort_order'] = 'activities_date asc';
  }

  $_SESSION['timesheet'] = new timesheet(0, $_SESSION['employee']->id, HTMLHelper::getPOST('period'), true, HTMLHelper::getPOST('sort_order'));

  switch (HTMLHelper::getPOST('action')) {
    case '':
      $_POST['activity_id'] = 0;
      break;
    case 'save_data':
      if ($_SESSION['timesheet']->id == 0) {
        // Timesheet does not exist yet so a new one has to be created in db
        // This is required for the test if previous unconfirmed timesheets exist
        $_SESSION['timesheet']->save();
      }
      // Adding the activity is handled in activity_entry.php
      break;
    case 'delete_activity':
      break;
    case 'delete_activity_confirmed':
      $_SESSION['timesheet']->delete_activity(HTMLHelper::getPOST('activity_id'));
      $_POST['activity_id'] = 0;
      $_POST['action'] = '';
      // Reload the timesheet object in order to
      // update the activity listing that follows
      $_SESSION['timesheet'] = new timesheet(0, $_SESSION['employee']->id, HTMLHelper::getPOST('period'), true, HTMLHelper::getPOST('sort_order'));
      break;
    case 'timesheet_to_be_confirmed':
      break;
    case 'timesheet_confirmed':
      $_SESSION['timesheet']->confirm();
      $_POST['action'] = '';
      break;
  }

  // Check if there are unconfirmed timesheets available with timesheets_end_date previous to today
  // If so, create an info message
  $oldest_unconfirmed_period = $_SESSION['timesheet']->get_oldest_unconfirmed_period();
  if (!GeneralHelper::is_null($oldest_unconfirmed_period)) {
    $_POST['info_message'] = sprintf(HEADER_INFO_UNCONFIRMED_PERIOD, $oldest_unconfirmed_period);
  }

  // header //
  require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- body //-->
  <table border="0" width="100%" cellspacing="3" cellpadding="3">
    <tr>
      <td width="<?php echo BOX_WIDTH; ?>" valign="top">
        <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
          <!-- left_navigation //-->
          <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
          <!-- left_navigation_eof //-->
        </table>
      </td>
      <!-- body_text //-->
      <td width="100%" valign="top">
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo HEADER_TEXT_TIMEREGISTRATION; ?></td>
                  <td class="pageHeading" align="right"><?php echo HTMLHelper::image(DIR_WS_IMAGES . 'journal-64x64.png', HEADER_TEXT_TIMEREGISTRATION, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0" width="16%" cellspacing="0" cellpadding="1" class="infoBox">
                <tr>
                  <td align="left" class="infoBoxHeading">
                    <?php echo HTMLHelper::draw_form('period_back', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('period'=>DateTimeHelper::getNextPeriod($_SESSION['timesheet']->period, -1)), array('mPath'), 'hidden_field'); ?>
                      <?php echo HTMLHelper::image_submit('arrow_left.gif', TEXT_TIMEREGISTRATION_BACK, '', DIR_WS_IMAGES); ?>
                    </form>
                  </td>
                  <td align="center" class="infoBoxHeading"><?php echo TEXT_TIMEREGISTRATION_PERIOD . $_SESSION['timesheet']->period; ?></td>
                  <td align="right" class="infoBoxHeading">
                    <?php echo HTMLHelper::draw_form('period_forward', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('period'=>DateTimeHelper::getNextPeriod($_SESSION['timesheet']->period, 1)), array('mPath'), 'hidden_field'); ?>
                      <?php echo HTMLHelper::image_submit('arrow_right.gif', TEXT_TIMEREGISTRATION_FORWARD, '', DIR_WS_IMAGES); ?>
                    </form>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0px" width="20%" cellspacing="0" cellpadding="3" class="infoBoxContents">
                <tr>
                  <td align="center" class="boxText"><?php echo DateTimeHelper::strftime(DATE_FORMAT_SHORT, DateTimeHelper::dateToUTS('%Y-%m-%d', $_SESSION['timesheet']->start_date)) . '&nbsp;&nbsp;-&nbsp;&nbsp;' . DateTimeHelper::strftime(DATE_FORMAT_SHORT, DateTimeHelper::dateToUTS('%Y-%m-%d', $_SESSION['timesheet']->end_date)); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <?php if ($_SESSION['timesheet']->locked) {
              echo '<td align="center" class="smallText">' . TEXT_TIMEREGISTRATION_LOCKED;
            } else {
              echo '<td align="center">';
              require(DIR_WS_INCLUDES . 'activity_entry.php');
            } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                <tr>
                  <td class="entryListing-heading"><?php echo HTMLHelper::draw_form('sort_activities_date', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('sort_order'=>(HTMLHelper::getPOST('sort_order', 'activities_date asc')=='activities_date asc'?'activities_date desc':'activities_date asc')), array('mPath','period'), 'hidden_field') . HTMLHelper::href_submit(TEXT_ACTIVITY_DAY, 'submitLinkInfoBoxHeading') . (HTMLHelper::getPOST('sort_order')=='activities_date asc'?HTMLHelper::image(DIR_WS_IMAGES . 'triangle_down.png'):(HTMLHelper::getPOST('sort_order')=='activities_date desc'?HTMLHelper::image(DIR_WS_IMAGES . 'triangle_up.png'):HTMLHelper::draw_separator('pixel_trans.gif', '9', '9'))) . '</form>'; ?></td>
                  <td class="entryListing-heading"><?php echo HTMLHelper::draw_form('sort_projects_name', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('sort_order'=>(HTMLHelper::getPOST('sort_order', 'projects_name asc')=='projects_name asc'?'projects_name desc':'projects_name asc')), array('mPath','period'), 'hidden_field') . HTMLHelper::href_submit(TEXT_ACTIVITY_PROJECTNAME, 'submitLinkInfoBoxHeading') . (HTMLHelper::getPOST('sort_order')=='projects_name asc'?HTMLHelper::image(DIR_WS_IMAGES . 'triangle_down.png'):(HTMLHelper::getPOST('sort_order')=='projects_name desc'?HTMLHelper::image(DIR_WS_IMAGES . 'triangle_up.png'):HTMLHelper::draw_separator('pixel_trans.gif', '9', '9'))) . '</form><br>&nbsp;' . TEXT_ACTIVITY_ROLENAME; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_AMOUNT; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_UNIT; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_TRAVELDISTANCE; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_EXPENSES; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_TICKETNUMBER; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_COMMENT; ?></td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                </tr>
                <?php if (!$_SESSION['timesheet']->empty) {
                  $odd_or_even = "odd";
                  for ($index = 0; $index < sizeof($_SESSION['timesheet']->activities); $index++) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>">
                      <td class="entryListing-data"><?php echo DateTimeHelper::strftime('dd', $_SESSION['timesheet']->activities[$index]->date); ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['timesheet']->activities[$index]->project_name.'<br>'.$_SESSION['timesheet']->activities[$index]->role_name; ?></td>
                      <td class="entryListing-data"><?php echo DBHelper::number_db_to_user($_SESSION['timesheet']->activities[$index]->amount, 2); ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['timesheet']->activities[$index]->unit_name; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['timesheet']->activities[$index]->travel_distance.'<br>'.$_SESSION['timesheet']->activities[$index]->travel_description; ?></td>
                      <td class="entryListing-data"><?php echo DBHelper::number_db_to_user($_SESSION['timesheet']->activities[$index]->expenses, 2); ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['timesheet']->activities[$index]->ticket_number; ?></td>
                      <td class="entryListing-data"><?php echo HTMLHelper::txt2html($_SESSION['timesheet']->activities[$index]->comment); ?></td>
                      <td align="center" width="20" class="entryListing-data">
                      <?php if (!$_SESSION['timesheet']->locked) {
                        echo HTMLHelper::draw_form('edit_activity', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('action'=>'enter_data','selected_date'=>$_SESSION['timesheet']->activities[$index]->date,'projects_id'=>$_SESSION['timesheet']->activities[$index]->projects_id,'roles_id'=>$_SESSION['timesheet']->activities[$index]->roles_id, 'activity_id'=>$_SESSION['timesheet']->activities[$index]->activity_id, 'activity_amount'=>DBHelper::number_db_to_user($_SESSION['timesheet']->activities[$index]->amount, 2), 'original_activity_amount'=>$_SESSION['timesheet']->activities[$index]->amount, 'tariffs_id'=>$_SESSION['timesheet']->activities[$index]->tariff->id, 'activity_travel_distance'=>"".$_SESSION['timesheet']->activities[$index]->travel_distance, 'activity_travel_description'=>"".$_SESSION['timesheet']->activities[$index]->travel_description, 'activity_expenses'=>DBHelper::number_db_to_user($_SESSION['timesheet']->activities[$index]->expenses, 2), 'activity_ticket_number'=>$_SESSION['timesheet']->activities[$index]->ticket_number, 'activity_comment'=>$_SESSION['timesheet']->activities[$index]->comment), array('mPath','period','sort_order'), 'hidden_field');
                        echo HTMLHelper::image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                        echo '</form>';
                      } ?>
                      </td>
                      <td align="center" width="20" class="entryListing-data">
                      <?php if (!$_SESSION['timesheet']->locked) {
                        echo HTMLHelper::draw_form('delete_activity', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('action'=>'delete_activity', 'activity_id'=>$_SESSION['timesheet']->activities[$index]->activity_id), array('mPath','period','sort_order'), 'hidden_field');
                        echo HTMLHelper::image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                        echo '</form>';
                      } ?>
                      </td>
                    </tr>
                    <?php if (HTMLHelper::getPOST('action')=='delete_activity' && HTMLHelper::getPOST('activity_id')==$_SESSION['timesheet']->activities[$index]->activity_id) { ?>
                      <!-- Show OK and Cancel buttons below the activity-to-be-deleted -->
                      <tr class="entryListing-<?php echo $odd_or_even; ?>">
                        <td align="right" valign="middle" class="entryListing-data" colspan="10">
                          <?php echo TEXT_ENTRY_DELETE_QUESTION; ?>&nbsp;
                          <?php echo HTMLHelper::draw_form('delete_activity_confirm', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('action'=>'delete_activity_confirmed'), array('mPath','period','sort_order','activity_id'), 'hidden_field');
                            echo HTMLHelper::image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"'); ?>
                          </form>&nbsp;
                          <?php echo HTMLHelper::draw_form('delete_activity_cancel', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array(), array('mPath','period','sort_order'), 'hidden_field');
                            echo HTMLHelper::image_submit('button_cancel.gif', TEXT_ENTRY_DELETE_CANCEL, 'style="vertical-align:middle"'); ?>
                          </form>
                        </td>
                      </tr>
                    <?php }
                    $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  }
                } else { ?>
                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="10" align="center">
                      <?php echo TEXT_TIMEREGISTRATION_IS_EMPTY; ?>
                    </td>
                  </tr>
                <?php } ?>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="entryListing-data">
              <?php if (!$_SESSION['timesheet']->locked && HTMLHelper::getPOST('action')!='timesheet_to_be_confirmed') {
                // Confirm button enabled
                echo HTMLHelper::draw_form('pre_confirm_timesheet', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('action'=>'timesheet_to_be_confirmed'), array('mPath','period','sort_order'), 'hidden_field');
                echo HTMLHelper::image_submit('button_confirm.gif', TEXT_TIMEREGISTRATION_CONFIRM, 'style="vertical-align:middle"'); ?>
                </form>&nbsp;
              <?php } else if (HTMLHelper::getPOST('action')=='timesheet_to_be_confirmed') { ?>
                <!-- Show OK and Cancel buttons below the timesheet-to-be-confirmed -->
                <?php echo ($_SESSION['timesheet']->hours_complete()?TEXT_TIMEREGISTRATION_CONFIRM_QUESTION:'<b><font color="#ff0000">'.TEXT_TIMEREGISTRATION_CONFIRM_QUESTION_MINIMUM_HOURS_NOT_MET.'</font></b>'); ?>&nbsp;
                <?php echo HTMLHelper::draw_form('confirm_timesheet', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array('action'=>'timesheet_confirmed'), array('mPath','period','sort_order'), 'hidden_field');
                  echo HTMLHelper::image_submit('button_ok.gif', TEXT_TIMEREGISTRATION_CONFIRM_OK, 'style="vertical-align:middle"'); ?>
                </form>&nbsp;
                <?php echo HTMLHelper::draw_form('confirm_timesheet_cancel', HTMLHelper::href_link(FILENAME_TIMEREGISTRATION)) . HTMLHelper::create_parameters(array(), array('mPath','period','sort_order'), 'hidden_field');
                  echo HTMLHelper::image_submit('button_cancel.gif', TEXT_TIMEREGISTRATION_CONFIRM_CANCEL, 'style="vertical-align:middle"'); ?>
                </form>&nbsp;
              <?php } else {
                // Confirm button disabled
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_confirm_disabled.gif', null, null, null, 'style="vertical-align:middle"') . '&nbsp;&nbsp;&nbsp;';
              } 
              echo HTMLHelper::draw_form('report_projects', HTMLHelper::href_link(FILENAME_REPORT), 'post') . HTMLHelper::create_parameters(array('action'=>'report_projects','current_employee'=>true,'show_travel_distance'=>true,'show_expenses'=>true,'show_comment'=>true,'show_signature'=>true), array('mPath','period','sort_order'), 'hidden_field');
              echo HTMLHelper::image_submit('button_pdf.gif', TEXT_BUTTON_PDF, 'style="vertical-align:middle"'); ?>
              </form>
            </td>
          </tr>
          <tr>
            <td><?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '50'); ?></td>
          </tr>
        </table>
      </td>
      <!-- body_text_eof //-->
    </tr>
  </table>
  <script type="text/javascript">
    function fetchTicketNrAndDescription(activityDate) {
      var arrTicketResult = window.showModalDialog("<?php echo FILENAME_TIMEREGISTRATION_TICKET_LOOKUP; ?>?activityDate=" + activityDate, null, "center: yes; dialogHeight: 400px; dialogWidth: 600px; help: no; resizable: no; status: no;");
      if (arrTicketResult.length == 2) {
        document.forms['activity_entry']['activity_ticket_number'].value = arrTicketResult[0];
        document.forms['activity_entry']['activity_comment'].value = decodeURIComponent(arrTicketResult[1]);
        maxLength(document.forms['activity_entry']['activity_comment'], 255);
      }
    }
  </script>
<!-- body_eof //-->
<?php echo JSHelper::javascript_maxlength(); ?>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<!-- application_bottom //-->
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<!-- application_bottom_eof //-->
