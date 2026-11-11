<?php
/****************************************************************************
 * CODE FILE   : header.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 30 october 2026
 * Description : Header file
 *               Contains html declarations and parameters
 *               Creates error- and/or info message on top of page
 * 
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

use BitTS\Support\HTMLHelper;
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo ((getenv('HTTPS') == 'on') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_APPLICATION; ?>">
    <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon">
  </head>
  <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr class="header">
        <td valign="middle"><?php echo '<a href="' . HTMLHelper::href_link(FILENAME_DEFAULT) . '">' . HTMLHelper::image(DIR_WS_IMAGES . COMPANY_BANNER, COMPANY_NAME) . '</a>'; ?></td>
      </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="1">
      <tr class="headerNavigation">
        <td class="headerNavigation">&nbsp;&nbsp;
          <?php if (isset($_SESSION['employee'])) {
            echo HEADER_TEXT_CURRENT_USER . $_SESSION['employee']->fullname . '&nbsp;&nbsp;&nbsp;';
            echo HTMLHelper::draw_form('logout', HTMLHelper::href_link(FILENAME_DEFAULT)) . HTMLHelper::create_parameters(array('action'=>'logout'), null, 'hidden_field') . '[' . HTMLHelper::href_submit(HEADER_TEXT_LOGOUT, 'submitLinkHeaderNavigation') . ']</form>';
          } else {
            echo HEADER_TEXT_NO_CURRENT_USER;
          } ?>
        </td>
        <td align="right" class="headerNavigation">&nbsp;&nbsp;</td>
      </tr>
    </table>

<?php
  if (isset($_POST['error_message']) && !GeneralHelper::is_null($_POST['error_message'])) {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="headerError">
        <td class="headerError"><?php echo htmlspecialchars(urldecode($_POST['error_message'])); ?></td>
      </tr>
    </table>
<?php
  }

  if (isset($_POST['info_message']) && !GeneralHelper::is_null($_POST['info_message'])) {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="headerInfo">
        <td class="headerInfo"><?php echo htmlspecialchars($_POST['info_message']); ?></td>
      </tr>
    </table>
<?php
  }
?>
