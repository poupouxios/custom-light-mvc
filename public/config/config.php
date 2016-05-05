<?php

define('FILESYSTEM_PATH', realpath(dirname(__FILE__). DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR));

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

function stb_autoloader($class_name) {
	$classesDir = [
	FILESYSTEM_PATH.'/application/',
	FILESYSTEM_PATH.'/application/model/',
	FILESYSTEM_PATH.'/application/mapper/',
	FILESYSTEM_PATH.'/application/controller/',
	FILESYSTEM_PATH.'/application/helper/',
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
