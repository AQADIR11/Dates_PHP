<?php
/*
|-----------------------------------------------------------------
|  Dates PHP Custom Header
|-----------------------------------------------------------------
*/

if(!headers_sent()){
	header("Powered-By: Dates PHP/1.0");
}


/*
| Check for the required PHP version
|
| Dies if requirements are not met.
|
| @access private
|
| @Constent string REQUIRED_PHP_VERSION The required PHP version string.
| @Constent string DP_VERSION The WordPress version string.
 */

if (version_compare(REQUIRED_PHP_VERSION, CURRENT_PHP_VERSION, '>')) {
    header(sprintf('500 Internal Server Error'), true, 500);
	header('Content-Type: text/html; charset=utf-8');
	printf( 'Your server is running PHP version %1$s but Dates PHP %2$s requires at least %3$s.', CURRENT_PHP_VERSION, DP_VERSION, REQUIRED_PHP_VERSION);
	exit(1);
}


include BASE_PATH . "core/execute.php";