<?php
/****************************************************************************
 * CODE FILE   : header_dialog.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 30 october 2026
 * Description : Header file
 *               Contains html declarations and parameters
 *               Simple version for dialogs
 */
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
    <table border="0" width="100%" cellspacing="0" cellpadding="1">
      <tr class="headerNavigation">
        <td class="headerNavigation">&nbsp;</td>
      </tr>
    </table>