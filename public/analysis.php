<?php
/**
 * analysis.php
 *
 * HTML Utility class voor diverse weergave functies in de user interface
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 29 oktober 2026
 */

// application_top //
require '../includes/application_top.php';

use BitTS\Support\DateTimeHelper;
use BitTS\Support\GeneralHelper;
use BitTS\Support\HTMLHelper;

  // Check if user is logged in. If not, redirect to login page
  if (GeneralHelper::is_null($_SESSION['employee']))
    HTMLHelper::redirect(HTMLHelper::href_link(FILENAME_LOGIN));
  // Check if the user is allowed to view this page
  if (!$_SESSION['employee']->profile->right['analysis'])
    HTMLHelper::redirect(HTMLHelper::href_link(FILENAME_DEFAULT));

  switch (HTMLHelper::getPOST('action', '', true)) {
  }

  // header //
  require DIR_WS_INCLUDES . 'header.php'; ?>

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ANALYSIS; ?></td>
                  <td class="pageHeading" align="right"><?php echo HTMLHelper::image(DIR_WS_IMAGES . 'presentation-64x64.png', HEADER_TEXT_ANALYSIS, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0" width="15%" cellspacing="0" cellpadding="1" class="infoBox">
                <tr>
                  <?php // If period is not set, use current period
                  HTMLHelper::getPOST('period', DateTimeHelper::dateToPeriod(), true);
                  echo HTMLHelper::draw_form('period_back', HTMLHelper::href_link(FILENAME_ANALYSIS)) . HTMLHelper::create_parameters(array('period'=>DateTimeHelper::getNextPeriod(HTMLHelper::getPOST('period'), -1)), array('mPath'), 'hidden_field'); ?>
                    <td align="left" class="infoBoxHeading">
                      <?php echo HTMLHelper::image_submit('arrow_left.gif', TEXT_ANALYSIS_BACK, '', DIR_WS_IMAGES); ?>
                    </td>
                  </form>
                  <td align="center" class="infoBoxHeading"><?php echo TEXT_ANALYSIS_PERIOD . HTMLHelper::getPOST('period'); ?></td>
                  <?php echo HTMLHelper::draw_form('period_forward', HTMLHelper::href_link(FILENAME_ANALYSIS)) . HTMLHelper::create_parameters(array('period'=>DateTimeHelper::getNextPeriod(HTMLHelper::getPOST('period'), 1)), array('mPath'), 'hidden_field'); ?>
                    <td align="right" class="infoBoxHeading">
                      <?php echo HTMLHelper::image_submit('arrow_right.gif', TEXT_ANALYSIS_FORWARD, '', DIR_WS_IMAGES); ?>
                    </td>
                  </form>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0px" width="20%" cellspacing="0" cellpadding="3" class="infoBoxContents">
                <tr>
                  <td align="center" class="boxText"><?php echo DateTimeHelper::strftime(DATE_FORMAT_SHORT, DateTimeHelper::dateToUTS('%Y-%m-%d', DateTimeHelper::getPeriodStartDate(HTMLHelper::getPOST('period')))) . '&nbsp;&nbsp;-&nbsp;&nbsp;' . DateTimeHelper::strftime(DATE_FORMAT_SHORT, DateTimeHelper::dateToUTS('%Y-%m-%d', DateTimeHelper::getPeriodEndDate(HTMLHelper::getPOST('period')))); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <?php echo HTMLHelper::draw_form('report_employees', HTMLHelper::href_link(FILENAME_REPORT), 'post') . HTMLHelper::create_parameters(array('action'=>'report_employees'), array('period'), 'hidden_field'); ?>
                      <table border="0" width="100%" cellspacing="0" cellpadding="10" class="report_listing">
                        <tr>
                          <td colspan="2" class="boxTitle"><?php echo REPORT_NAME_EMPLOYEES; ?></td>
                        </tr>
                        <tr>
                          <td valign="top" width="65"><?php echo HTMLHelper::image_submit('button_pdf.gif', TEXT_BUTTON_PDF); ?></td>
                          <td class="boxText">
                            <?php echo HTMLHelper::draw_checkbox_field('show_user_rights', true, false) . REPORT_CHECKBOX_SHOW_USER_RIGHTS . '<br>';
                            echo HTMLHelper::draw_checkbox_field('show_timesheet_info', true, true) . REPORT_CHECKBOX_SHOW_TIMESHEET_INFO . '<br>';
                            echo HTMLHelper::draw_checkbox_field('show_travel_distance_and_expenses', true, true) . REPORT_CHECKBOX_SHOW_TRAVEL_DISTANCE_AND_EXPENSES . '<br>';
                            echo HTMLHelper::draw_checkbox_field('show_all_employees', true, false) . REPORT_CHECKBOX_SHOW_ALL_EMPLOYEES; ?>
                          </td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>
                    <?php echo HTMLHelper::draw_form('report_projects', HTMLHelper::href_link(FILENAME_REPORT), 'post') . HTMLHelper::create_parameters(array('action'=>'report_projects'), array('period'), 'hidden_field'); ?>
                      <table border="0" width="100%" cellspacing="0" cellpadding="10" class="report_listing">
                        <tr>
                          <td colspan="2" class="boxTitle"><?php echo REPORT_NAME_PROJECTS; ?></td>
                        </tr>
                        <tr>
                          <td valign="top" width="65"><?php echo HTMLHelper::image_submit('button_pdf.gif', TEXT_BUTTON_PDF); ?></td>
                          <td class="boxText">
                            <?php echo HTMLHelper::draw_checkbox_field('per_employee', true, false) . REPORT_CHECKBOX_PER_EMPLOYEE . '<br>';
                            echo HTMLHelper::draw_checkbox_field('show_tariff', true, true) . REPORT_CHECKBOX_SHOW_TARIFF . '<br>';
                            echo HTMLHelper::draw_checkbox_field('show_travel_distance', true, true) . REPORT_CHECKBOX_SHOW_TRAVEL_DISTANCE . '<br>';
                            echo HTMLHelper::draw_checkbox_field('show_expenses', true, true) . REPORT_CHECKBOX_SHOW_EXPENSES . '<br>';
                            echo HTMLHelper::draw_checkbox_field('show_comment', true, true) . REPORT_CHECKBOX_SHOW_COMMENTS . '<br>';
                            echo HTMLHelper::draw_checkbox_field('show_signature', true, false) . REPORT_CHECKBOX_SHOW_SIGNATURE; ?>
                          </td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>
                    <?php echo HTMLHelper::draw_form('report_travel_distances_and_expenses', HTMLHelper::href_link(FILENAME_REPORT), 'post') . HTMLHelper::create_parameters(array('action'=>'report_travel_distances_and_expenses'), array('period'), 'hidden_field'); ?>
                      <table border="0" width="100%" cellspacing="0" cellpadding="10" class="report_listing">
                        <tr>
                          <td colspan="2" class="boxTitle"><?php echo REPORT_NAME_TRAVEL_DISTANCES_AND_EXPENSES; ?></td>
                        </tr>
                        <tr>
                          <td valign="top" width="65"><?php echo HTMLHelper::image_submit('button_pdf.gif', TEXT_BUTTON_PDF); ?></td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>
                    <?php echo HTMLHelper::draw_form('report_consolidated_projects_per_employee', HTMLHelper::href_link(FILENAME_REPORT), 'post') . HTMLHelper::create_parameters(array('action'=>'report_consolidated_projects_per_employee'), array('period'), 'hidden_field'); ?>
                      <table border="0" width="100%" cellspacing="0" cellpadding="10" class="report_listing">
                        <tr>
                          <td colspan="2" class="boxTitle"><?php echo REPORT_NAME_CONSOLIDATED_PROJECTS_PER_EMPLOYEE; ?></td>
                        </tr>
                        <tr>
                          <td valign="top" width="65"><?php echo HTMLHelper::image_submit('button_pdf.gif', TEXT_BUTTON_PDF); ?></td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>
                    <?php echo HTMLHelper::draw_form('export_projects', HTMLHelper::href_link(FILENAME_EXPORT), 'post') . HTMLHelper::create_parameters(array('action'=>'export_activities'), array('period'), 'hidden_field'); ?>
                      <table border="0" width="100%" cellspacing="0" cellpadding="10" class="report_listing">
                        <tr>
                          <td colspan="2" class="boxTitle"><?php echo EXPORT_NAME_ACTIVITIES; ?></td>
                        </tr>
                        <tr>
                          <td valign="top" width="65"><?php echo HTMLHelper::image_submit('button_csv.gif', TEXT_BUTTON_CSV); ?></td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </tr>
              </table>
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
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<!-- application_bottom //-->
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<!-- application_bottom_eof //-->
