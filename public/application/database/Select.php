<?php
class Select extends BaseDatabase{
	private $table;
	private $fields = " * ";
	private $where = " ";
	private $orderBy = " ";
	private $groupBy = " ";
	private $data = [];
	private $limit = " ";

	public function __construct(){
		parent::__construct();
	}

	public function fromTable($table){
		$this->table = $table;
		return $this;
	}

	public function withFields($fields){
		$fields_to_fetch = count($fields) > 0 ? implode(",",$fields) : " * ";
		$this->fields = $fields_to_fetch;
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

	public function orderBy($order_by){
		$order_statement = count($order_by) > 0 ? " order by ".implode(",",$order_by)." " : " ";
		$this->orderBy = $order_statement;
		return $this;
	}

	public function groupBy($group_by){
		$groupby_statement = count($group_by) > 0 ? " group by ".implode(",",$group_by)." " : " ";
		$this->groupBy = $groupby_statement;	
		return $this;	
	}

	public function limit($limit){
		$this->limit = " LIMIT ".$limit;
		return $this;
	}
	
	public function execute(){
		$select = null;
		try{
			$select = $this->pdo->
				prepare("Select {$this->fields} from {$this->table} {$this->where} {$this->groupBy} {$this->orderBy} {$this->limit}");
			$select->execute($this->data);
		}catch(PDOException $e){
			trigger_error(implode(" ",$select->errorInfo())." ".$e->getMessage());
      return false;
    }
    return $select;	
	}

	public function executeCustomQuery($customQuery){
		$select = null;
		try{
			$select = $this->pdo->prepare($customQuery);
			$select->execute($this->data);
		}catch(PDOException $e){
			trigger_error(implode(" ",$select->errorInfo())." ".$e->getMessage());
      return false;
    }
    return $select;	
	}

}
