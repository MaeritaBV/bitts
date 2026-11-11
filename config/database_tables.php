<?php
/**
 * database_tables.php
 *
 * Definition of the database table names used in the project
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 11 november 2026
 */

define('TABLE_ACTIVITIES',      DB_TABLES_PREFIX . 'activities');
define('TABLE_BENEFITS',        DB_TABLES_PREFIX . 'benefits');
define('TABLE_BUSINESS_UNITS',  DB_TABLES_PREFIX . 'business_units');
define('TABLE_CATEGORIES',      DB_TABLES_PREFIX . 'categories');
define('TABLE_CONFIGURATION',   DB_TABLES_PREFIX . 'configuration');
define('TABLE_CUSTOMERS',       DB_TABLES_PREFIX . 'customers');
define('TABLE_EMPLOYEES_ROLES', DB_TABLES_PREFIX . 'employees_roles');
define('TABLE_PROFILES',        DB_TABLES_PREFIX . 'profiles');
define('TABLE_EMPLOYEES',       DB_TABLES_PREFIX . 'employees');
define('TABLE_UNITS',           DB_TABLES_PREFIX . 'units');
define('TABLE_LANGUAGES',       DB_TABLES_PREFIX . 'languages');
define('TABLE_PROJECTS',        DB_TABLES_PREFIX . 'projects');
define('TABLE_ROLES',           DB_TABLES_PREFIX . 'roles');
define('TABLE_SESSIONS',        DB_TABLES_PREFIX . 'sessions');
define('TABLE_TARIFFS',         DB_TABLES_PREFIX . 'tariffs');
define('TABLE_TIMESHEETS',      DB_TABLES_PREFIX . 'timesheets');
define('TABLE_PUNCH_CLOCK',     DB_TABLES_PREFIX . 'punch_clock');

define('VIEW_BENEFITS',         DB_TABLES_PREFIX . 'v_benefits');
