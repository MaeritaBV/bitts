<?php
/****************************************************************************
 * CODE FILE   : administration.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 04 november 2026
 * Description : Administration main form
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
  if (!$_SESSION['employee']->profile->right['administration'])
    HTMLHelper::redirect(HTMLHelper::href_link(FILENAME_DEFAULT));

  if (!isset($_POST['action'])) $_POST['action'] = '';
  switch ($_POST['action']) {
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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION; ?></td>
                  <td class="pageHeading" align="right"><?php echo HTMLHelper::image(DIR_WS_IMAGES . 'wheel-64x64.png', HEADER_TEXT_ADMINISTRATION, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
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
