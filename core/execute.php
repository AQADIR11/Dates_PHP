<?php
/*
|---------------------------------------------------------------
| ERROR REPORTING
|---------------------------------------------------------------
|
| Different environments will require different levels of error reporting.
| By default development will show errors but testing and live will hide them.
*/
switch (ENVIRONMENT) {
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
		break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>=')) {
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		} else {
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
		break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

/*
|--------------------------------------------------------------------
|END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
|--------------------------------------------------------------------
|
|
|
|---------------------------------------------------------------
| Resolve the core path for increased reliability
|---------------------------------------------------------------
*/

// Set the current directory correctly for CLI requests
if (defined('STDIN')) {
	chdir(dirname(__FILE__));
}

if (($_temp = realpath($core_path)) !== FALSE) {
	$core_path = $_temp . DIRECTORY_SEPARATOR;
} else {
	echo rtrim($core_path, '/\\');
	// Ensure there's a trailing slash
	$core_path = strtr(rtrim($core_path, '/\\'), '/\\', DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
}


// Is the core path correct?
if (!is_dir($core_path)) {
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your core folder path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME);
	exit(3); // EXIT_CONFIG
}


/*
|---------------------------------------------------------------
| Resolve the app path for increased reliability
|---------------------------------------------------------------
 */

// Set the current directory correctly for CLI requests
if (defined('STDIN')) {
	chdir(dirname(__FILE__));
}

if (($_temp = realpath($app_folder)) !== FALSE) {
	$app_folder = $_temp . DIRECTORY_SEPARATOR;
} else {
	// Ensure there's a trailing slash
	$app_folder = strtr(
		rtrim($app_folder, '/\\'),
		'/\\',
		DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR
	) . DIRECTORY_SEPARATOR;
}

// Is the app path correct?
if (!is_dir($app_folder)) {
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your app folder path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME);
	exit(3); // EXIT_CONFIG
}


spl_autoload_register(function ($className) {
	include "classes/$className.php";
});

DP_Routes::App();
