<?php
class UserMapper extends BaseMapper{

	public function __construct(){
		$this->table_name = "users";
	}	
	
	public function findOneByEmail($email){
		$select = new Select();
		$result = $select->fromTable($this->table_name)
									->where(array("email = :email"))
									->withData(array("email" => $email))
									->execute();

		$user = null;
		while($row = $select->fetchPDOAssoc($result)){
			$user = UserModel::createObject($row,UserModel::$attributes);
		}		
		return $user;
	}
	
}
