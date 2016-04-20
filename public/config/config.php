<?php

define('FILESYSTEM_PATH', realpath(dirname(__FILE__). DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR));
//define('AIRBRAKE_API_KEY',"ed8b53e1514785e0e0b6c21fb0e05adc");

$app_env = getenv('APPLICATION_ENV');
if(!isset($app_env) || strlen($app_env) == 0){
	$app_env = APPLICATION_ENV;
}

if($app_env == 'local'){
	define("DATABASE_HOST","localhost");
	define("DATABASE_USER", "lightmvc");
	define("DATABASE_NAME", "lightmvc");
	define("DATABASE_PASS", "password");
	define("BASE_URL","http://192.168.50.200/");
}else if($app_env == 'testing'){
	define("DATABASE_HOST","localhost");
	define("DATABASE_USER", "lightmvc-testing");
	define("DATABASE_NAME", "lightmvc-testing");	
	define("DATABASE_PASS", "password");
	define("BASE_URL","http://192.168.50.200:81/");
}

#if(ENABLE_ERRBIT){
#	$airbrake_config = [
#		"environmentName" => $app_env,
#		"url" => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
#		"host" => "errbit.llamadigital.net",
#		"secure" => true,
#		"action" => "",
#		"component" => "",
#		'timeout' => 2,
#		"errorReportingLevel" => ini_get('error_reporting')
#	];
#}


function stb_autoloader($class_name) {
	$classesDir = [
	FILESYSTEM_PATH.'/application/',
	FILESYSTEM_PATH.'/application/model/',
	FILESYSTEM_PATH.'/application/mapper/',
	FILESYSTEM_PATH.'/application/controller/',
	FILESYSTEM_PATH.'/application/database/'];

	foreach ($classesDir as $directory) {   
		if (file_exists($directory . $class_name . '.php')) {
			require_once ($directory . $class_name . '.php');                 
			return;         
		}
	} 
} 
spl_autoload_register("stb_autoloader");
?>
