<?php

define('BEHAT_ERROR_REPORTING', E_ALL ^ E_STRICT);
if(!defined('BASE_PATH'))
{
  define('BASE_PATH', realpath(dirname(__FILE__) . '/../../'));
}

if(!defined('APPLICATION_ENV')){
  define('APPLICATION_ENV', 'testing');
}

require_once __DIR__ . '/../../vendor/behat/behat/src/Behat/Behat/Exception/ErrorException.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../public/config/config.php';

function resetDatabase(){
	$command = "mysql -u ".DATABASE_USER." -p".DATABASE_PASS." ".DATABASE_NAME.
			" < ".__DIR__ . "/../../db/lightmvc-testing.sql";
	exec($command);
}

$pid = exec("nohup phantomjs --webdriver=8643 > /dev/null 2>&1 & echo $!");
if ($pid){
register_shutdown_function(function() use ($pid){
  exec ("kill ".$pid);
});
}else{
  die('Failed to run phantomjs');
}
