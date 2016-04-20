<?php

	class SavingMapper extends BaseMapper{

		public function __construct(){
			$this->table_name = "savings";
		}	

		public function findAllSavingsByUserId($user_id){
			$select = new Select();
			$result = $select->fromTable($this->table_name)
											 ->where(["user_id = :user_id"])
											 ->withData(["user_id" => $user_id])
											 ->execute();

			$savings = [];
			while($row = $select->fetchPDOAssoc($result)){
				$savings[] = SavingModel::createObject($row);
			}
			return $savings;
		}
	}	
