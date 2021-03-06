<?php

	class ExpenseMapper extends BaseMapper{

		public function __construct(){
			$this->table_name = "expenses";
		}

		public function findAllExpensesByUserId($user_id){
			$select = new Select();
			$result = $select->fromTable($this->table_name)
											 ->where(["user_id = :user_id"])
											 ->withData(["user_id" => $user_id])
											 ->execute();

			$expenses = [];
			while($row = $select->fetchPDOAssoc($result)){
				$expenses[] = ExpenseModel::createObject($row);
			}
			return $expenses;
		}
	
	}

?>
