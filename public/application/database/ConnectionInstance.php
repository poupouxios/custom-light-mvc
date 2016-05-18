<?php

  class ConnectionInstance{

    private static $instance;
    private $pdo;
    
    private function __construct(){
    
    }
    
    public function __clone(){}
    
    public static function getInstance(){
      if(!self::$instance){
        self::$instance = new ConnectionInstance();
      }
      return self::$instance;
    }
    
    public function getPdoConnection(){
      if(!$this->pdo){
        $this->pdo = new Pdo("mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME.";charset=UTF8", 
          DATABASE_USER, DATABASE_PASS,
          array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
            PDO::ATTR_PERSISTENT => true
          )
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      return $this->pdo;
    }
  }
