<?php 
require_once __DIR__.'/../vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__."/../");
$dotenv->load();

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
           $headers = [];
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}

$api_version = "v1";
if (php_sapi_name() == 'cli') {

    $environment = 'development';
    if (isset($argv)) {
        // grab the --env argument, and the one that comes next
        $key = (array_search('--env', $argv));
        $environment = $argv[$key + 1];
        // get rid of them so they don't get passed in to our method as parameter values
        unset($argv[$key], $argv[$key + 1]);
    }
    $_SERVER['CI_ENV'] = $environment;
}else{
	$all_headers = getallheaders();
	if(isset($all_headers['Version']) && in_array($all_headers['Version'], array("v1"))){
		$api_version = $all_headers['Version'];
	}

	switch($_SERVER['SERVER_NAME'])
	{
		case 'localhost':
			$_SERVER['CI_ENV'] = 'development';
		break;
		case 'streetbuzz.co':
			$_SERVER['CI_ENV'] = 'testing';
		break;
		default:
			$_SERVER['CI_ENV'] = 'production';
		break;
	}
}

define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
defined('API_VERSION') OR define('API_VERSION', $api_version);