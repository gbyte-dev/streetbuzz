<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


define('SITE_TITLE', 'StreetBuzz');

$language_list = array();
$app_language_list = array();
$language_list['en'] = 'english';
$app_language_list['en'] = 'English';


define('LANGUAGE_LIST',serialize($language_list));
define('APP_LANGUAGE_LIST',serialize($app_language_list));

//custom variables from env
define('REDIS_SOCKET_TYPE', getenv('REDIS_SOCKET_TYPE'));
define('REDIS_SOCKET', getenv('REDIS_SOCKET'));
define('REDIS_TIMEOUT', getenv('REDIS_TIMEOUT'));
define('REDIS_HOST', getenv('REDIS_HOST'));
define('REDIS_PASSWORD', getenv('REDIS_PASSWORD'));
define('REDIS_PORT', getenv('REDIS_PORT'));

define('HTTP_PROTOCOL' ,getenv('HTTP_PROTOCOL'));
define('SERVER_NAME' ,getenv('SERVER_NAME'));

define('SITE_NAME', getenv('SITE_NAME'));
define('SMTP_HOST', getenv('SMTP_HOST'));
define('SMTP_USER', getenv('SMTP_USER'));
define('SMTP_PASS', getenv('SMTP_PASS'));
define('SMTP_PORT', getenv('SMTP_PORT'));
define('PROTOCOL', getenv('PROTOCOL')); 
define('MAILPATH', '');
define('MAILTYPE', 'html');

define('FROM_EMAIL', getenv('FROM_EMAIL'));
define('FROM_EMAIL_TITLE', getenv('FROM_EMAIL_TITLE'));
define('NO_REPLY_EMAIL', getenv('EMAIL_NOREPLY_NAME'));
define('NO_REPLY_EMAIL_TITLE', getenv('NO_REPLY_EMAIL_TITLE'));    

define('TIME_LINE_DAYS' ,getenv('TIME_LINE_DAYS'));

define('AUTH_TOKEN', 'Authkey');
define('FB_API_KEY',getenv('FB_API_KEY'));
define('FB_API_SECRETE',getenv('FB_API_SECRETE'));
define('ANDROID_GOOGLE_CLIENT_ID', getenv('ANDROID_GOOGLE_CLIENT_ID'));
define('GOOGLE_APPLICATION_NAME', getenv('GOOGLE_APPLICATION_NAME'));
switch (ENVIRONMENT)
{
	case 'development':
		define('PROJECT_FOLDER_NAME', getenv('FOLDER_NAME'));
		define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].PROJECT_FOLDER_NAME."/");
		define('COOKIE_NAME', 'ci_session_sqa');
		define('SERVER_IP', HTTP_PROTOCOL.'://'.SERVER_NAME);
		define('WEBSITE_URL' ,SERVER_IP."/");
	break;
	case 'testing':
		define('PROJECT_FOLDER_NAME', getenv('FOLDER_NAME'));
		define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].PROJECT_FOLDER_NAME."/");
		define('COOKIE_NAME', 'ci_session_sdev');
		define('SERVER_IP', HTTP_PROTOCOL.'://'.SERVER_NAME);
		define('WEBSITE_URL' ,SERVER_IP."/".PROJECT_FOLDER_NAME."/");
	break;
	case 'production':
		define('PROJECT_FOLDER_NAME', getenv('FOLDER_NAME'));
		define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].PROJECT_FOLDER_NAME."/");
		define('COOKIE_NAME', 'ci_session_spr');
		define('SERVER_IP', HTTP_PROTOCOL.'://'.SERVER_NAME);
		define('WEBSITE_URL' ,SERVER_IP."/".PROJECT_FOLDER_NAME."/");
	break;
	default:
	break;
}
