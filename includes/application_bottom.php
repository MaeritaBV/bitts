<?php
/****************************************************************************
 * CODE FILE   : application_bottom.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 17 december 2007
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */
/**
 * application_bottom.php
 *
 * ...
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 08 november 2026
 */

  // Close database
$database = $_SESSION['database'];
$database->close();

// Close session
session_write_close();
