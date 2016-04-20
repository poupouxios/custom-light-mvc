<?php
class Update extends BaseDatabase{
	private $table = "";
	private $data = [];
	private $where = [];

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

	public function where($where){
		$this->where = $where;
		return $this;	
	}

	public function execute(){
    if (empty($this->data) || !is_array($this->data))
        return false;
    if (empty($this->where) || !is_array($this->where))
        return false;

		$update = null;
		try{
			$fields = $this->getSetFields($this->data);
			$where_fields = $this->getSetFields($this->where);
			$where_statement = count($where_fields) > 0 ? " where ".implode(" And ",$where_fields)." " : " ";
			$query = "Update {$this->table} set  ".implode(",",$fields)." $where_statement ";
			$update = $this->pdo->prepare("Update {$this->table} set  ".implode(",",$fields)." $where_statement ");
			$result = $update->execute($this->data);
			return true;
		}catch(PDOException $e){
			trigger_error(implode(" ",$update->errorInfo())." ".$e->getMessage());
      return false;
		}
	}

	private function getSetFields($data){
		$fields = [];
		foreach($data as $key => $value){
			$fields[] = $key." = :$key";
			if(!isset($this->data[$key])){
				$this->data[$key] = $value;
			}
		}
		return $fields;
	}

}
