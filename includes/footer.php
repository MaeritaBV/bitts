<?php
/****************************************************************************
 * CODE FILE   : footer.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 30 october 2026
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */
/**
 * footer.php
 *
 * ...
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 08 november 2026
 */

use BitTS\Support\DateTimeHelper;
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="1">
      <tr class="footer">
        <td class="footer">&nbsp;&nbsp;<?php echo DateTimeHelper::strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</td>
        <td align="right" class="footer">&nbsp;&nbsp;</td>
      </tr>
    </table>
    <br>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" class="smallText"><?php echo FOOTER_TEXT_BODY; ?></td>
      </tr>
    </table>
    <br>
  </body>
</html>