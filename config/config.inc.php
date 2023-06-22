<?php
// Session Start
@session_start();



/*
| -------------------------------------------------------------------
| SET PROJECT DEFUALT TIMEZONE
| -------------------------------------------------------------------
*/

date_default_timezone_set("Asia/Kolkata");


/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF CONSTANT
| -------------------------------------------------------------------
*/

/*
|-------------------------------------------------------------------
| "CONNECTION" The connection of your database server like you will
|  connect your project with database or not.
| YES
| NO
| -------------------------------------------------------------------
*/
define("CONNECTION", "NO");


/*
|-------------------------------------------------------------------
| "HOST" The hostname of your database server.
| -------------------------------------------------------------------
*/
define("HOST", "localhost");

/*
|-------------------------------------------------------------------
| "DRIVER" The database driver. e.g.: mysqli.
|-------------------------------------------------------------------
*/
define("DRIVER", "mysql");

/*
|-------------------------------------------------------------------
| "USER" The username used to connect to the database.
|-------------------------------------------------------------------
*/
define("USER", "root");

/*
|-------------------------------------------------------------------
| "PASSWORD" The password used to connect to the database.
|-------------------------------------------------------------------
*/
define("PASSWORD", "");

/*
|-------------------------------------------------------------------
| "DATABASE" The name of the database you want to connect to.
|-------------------------------------------------------------------
*/
define("DATABASE", "blog");

/* 
|-------------------------------------------------------------------
| "CHAR_SET" The character set of database.
|-------------------------------------------------------------------
*/
define("CHAR_SET", 'utf8');

/*
|-------------------------------------------------------------------
| "CHAR_SET" The collation of database.
|-------------------------------------------------------------------
*/
define("DATABASE_COLLECT", "utf8_general_ci");




/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your Dates_PHP root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| WARNING: You MUST set this value!
|
| If it is not set, then Dates_PHP will try guess the protocol and path
| your installation, but due to security concerns the hostname will be set
| to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
| The auto-detection mechanism exists only for convenience during
| development and MUST NOT be used in production!
|
| If you need to allow multiple domains, remember that this file is still
| a PHP script and you can easily do that on your own.
|
*/

define("BASE_URL", "http://localhost/Dates_PHP/");

/*
|--------------------------------------------------------------------------
| Project Base Path
|--------------------------------------------------------------------------
|
| Base path to your Dates_PHP root. Typically this will be your base path,
| WITH a trailing slash:
|
| C:\xampp\htdocs\project 
|
*/

define("BASE_PATH", dirname(__DIR__, 1) . "/");

/*
|---------------------------------------------------------------
| APPLICATION ENVIRONMENT
|---------------------------------------------------------------
|
| You can load different configurations depending on your
| current environment. Setting the environment also influences
| things like logging and error reporting.
|
| This can be set to anything, but default usage is:
|
|     development
|     testing
|     production
|
| NOTE: If you change these, also change the error_reporting() code from execute.php
 */
$_SERVER['DP_ENV'] = "development";

define("ENVIRONMENT", isset($_SERVER["DP_ENV"]) ? $_SERVER["DP_ENV"] : "development");

/*
|---------------------------------------------------------------
| CORE DIRECTORY NAME
|---------------------------------------------------------------
|
| This variable must contain the name of your "core" directory.
| Set the path if it is not in the same directory as this file.
 */

$core_path = BASE_PATH . 'core';


/*
 |---------------------------------------------------------------
 | APP DIRECTORY NAME
 |---------------------------------------------------------------
 |
 | If you want this front controller to use a different "app"
 | directory than the default one you can set its name here. The directory
 | can also be renamed or relocated anywhere on your server. If you do,
 | use an absolute (full) server path.
 | For more info please see the user guide:
 |
 |
 */
$app_folder = BASE_PATH . 'app';

/*
|-------------------------------------------------------
| REQUIRED MINIMUM PHP VERSION                      
|-------------------------------------------------------
*/
define("REQUIRED_PHP_VERSION", "7.0.0");

/*
|-------------------------------------------------------
| Dates PHP Version                      
|-------------------------------------------------------
*/

define("DP_VERSION", "1.0.0");


/*
|-------------------------------------------------------
| Server PHP Version                      
|-------------------------------------------------------
*/

define("CURRENT_PHP_VERSION", phpversion());

/*
|-------------------------------------------------------
| LOAD PROJECT FILES                      
|-------------------------------------------------------
*/

include BASE_PATH . "core/setting.php";
