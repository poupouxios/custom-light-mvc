<?php

class UserController extends BaseController{
	
	public function add(){
		if(count($_POST) > 0){
			$user = UserModel::createObject($_POST);
			UserModel::getMapper()->setModel($user)->save();
			$this->view->message = "Successfully saved";
		}
		return $this->view->getContentView("user/addForm.phtml");
	}

	public function view(){
		$this->view->attributes = array_keys(UserModel::$attributes);
		$this->view->users = UserModel::getMapper()->findAll();
		return $this->view->getContentView("user/users.phtml");
	}


}
