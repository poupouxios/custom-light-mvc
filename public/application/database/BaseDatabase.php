<?php

class BaseDatabase
{
	protected $pdo = null;
	
	public function __construct()
	{
		global $pdo;
		if(!$pdo){
			$pdo = $this->connectToPDO();
		}
		$this->pdo = $pdo;
	}

	public function getNumberOfRows($result){
		if(!$result){
			return 0;
		}else{
			return $result->rowCount();
		}
	}

	public function connectToPDO(){
		$pdo = new Pdo("mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME.";charset=UTF8", 
			DATABASE_USER, DATABASE_PASS,
			array(
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
				PDO::ATTR_PERSISTENT => true
			)
		);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo;
	}

	public function fetchPDOAssoc($result){
		if(!$result){
			return 0;
		}else{
			$result->setFetchMode(PDO::FETCH_ASSOC);
			return $result->fetch();
		}
	}

	public function fetchPDOArray($result){
		if(!$result){
			return 0;
		}else{
			return $result->fetchAll();
		}
	}

  public function Update($table, $data = array(), $where = array())
  {
      if (empty($data) || !is_array($data))
          return false;
      if (empty($where) || !is_array($where))
          return false;
 
			$fields = $this->getSetFields($data);
			$where_fields = $this->getSetFields($where);
			$update = $this->pdo->prepare("Update $table set  ".implode(",",$fields)." where ".implode(" And ",$where_fields));
			$result = $update->execute($data);
      if (!$result){
          trigger_error(implode(" ",$update->errorInfo()));
          return false;
      }
      return true;
  }

	private function getSetFields($data){
		$fields = [];
		foreach($data as $key => $value){
			$fields[] = $key." = :$key";
		}
		return $fields;
	}

}
?>
