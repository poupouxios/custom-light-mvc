<?php
  require_once('config/config.php');
  $request_string = $_SERVER['REQUEST_URI'];
  $request_string = trim($request_string, '/');
  $request_string = trim($request_string);

  $request = new Bootstrap($request_string);

  $request->render();

?>
