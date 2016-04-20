<?php
class Delete extends BaseDatabase{

	private $table;
	private $where = " ";
	private $data = [];

	public function __construct(){
		parent::__construct();
	}

	public function fromTable($table){
		$this->table = $table;
		return $this;
	}

	public function withData($data){
		$this->data = $data;
		return $this;
	}

	public function where($where_clauses){
		$where_statement = count($where_clauses) > 0 ? " Where ".implode(" ",$where_clauses)." " : " ";
		$this->where = $where_statement;
		return $this;
	}

	public function execute(){
		$delete = null;
		try{
			$delete = $this->pdo->
				prepare("Delete from {$this->table} {$this->where}");
			$delete->execute($this->data);
		}catch(PDOException $e){
			trigger_error(implode(" ",$delete->errorInfo())." ".$e->getMessage());
      return false;
    }
    return $delete;	
	}

	public function executeCustomQuery($customQuery){
		$delete = null;
		try{
			$delete = $this->pdo->prepare($customQuery);
			$delete->execute($this->data);
		}catch(PDOException $e){
			trigger_error(implode(" ",$delete->errorInfo())." ".$e->getMessage());
      return false;
    }
    return $delete;	
	}

}
