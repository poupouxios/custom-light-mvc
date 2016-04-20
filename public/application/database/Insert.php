<?php
class Insert extends BaseDatabase{

	private $table = "";
	private $data = [];

	public function __construct(){
		parent::__construct();
	}

	public function intoTable($table){
		$this->table = $table;
		return $this;
	}

	public function withValues($data){
		$this->data = $data;
		return $this;
	}

	public function execute(){
    if (empty($this->data) || !is_array($this->data))
        return false;
		$insert = null;
		try{
			$placeholders = implode(",",$this->getFieldAlias($this->data));
			$fields = implode(",",array_keys($this->data));
			$insert = $this->pdo->prepare("Insert into {$this->table} ($fields) Values ($placeholders)");
			$result = $insert->execute($this->data);
			return true;
		}catch(PDOException $e){
			trigger_error(implode(" ",$insert->errorInfo())." ".$e->getMessage());
      return false;
		}
	}

	private function getFieldAlias($rows){
		$values = [];
		foreach($rows as $key => $value){
			$values[] = ":$key";
		}
		return $values;
	}

	public function InsertID()
	{
		$last_insert_id = $this->pdo->lastInsertId();
		return $last_insert_id;
	}


}
