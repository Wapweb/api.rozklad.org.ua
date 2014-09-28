<?php
define('HOME','http://'.$_SERVER['HTTP_HOST']);
define('ROOT',  dirname( __FILE__ ) );

date_default_timezone_set("Europe/Kiev");
error_reporting(0);
set_include_path(get_include_path()
    .PATH_SEPARATOR.'application/controllers'
    .PATH_SEPARATOR.'application/models'
    .PATH_SEPARATOR.'application/utilities'
    .PATH_SEPARATOR.'application/views');

function auto($class) {
    require_once $class.'.php';
}

spl_autoload_register('auto');

try
{
	$api = FrontApiController::getInstance();

	$cacheData = BaseApiController::getDataFromCache($api->getQuery());
	if($cacheData !== null) {
		$status = 200;
		header("HTTP/1.1 " . $status . " OK" );
        header('Content-Type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
		echo json_encode($cacheData,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}
	else
	{
		$db = new PDO("mysql:dbname=DATABASE_NAME;host=HOST_NAME","USER_NAME","USER_PASS");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->query("SET NAMES utf8");
		Registry::set('db',$db);

		echo $api->processApi();
	}

} catch (Exception $e)
{
    header("HTTP/1.0 404 Not Found");
    echo "<h1><center>404 Not Found</center></h1>";
}