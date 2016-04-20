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

	public function edit($id){
		$user_id = intval($id);
		if(count($_POST) > 0){
			$user = UserModel::getMapper()->findOneBy("id",$user_id);
			if($user){
				$user->updateValues($_POST);
				UserModel::getMapper()->setModel($user)->save();
				$this->view->message = "Successfully updated";
			}else{
				$this->view->warningmessage = "Error updating.";
			}
		}

		$this->view->user = UserModel::getMapper()->findOneBy("id",$user_id);
		return $this->view->getContentView("user/edit.phtml");
	}

	public function delete($id){
		$this->view->user = UserModel::getMapper()->findOneBy("id",intval($id));
		if(!$this->view->user){
			$this->view->warningmessage = "Cannot find specific user.";
		}
		return $this->view->getContentView("user/confirmDelete.phtml");
	}

	public function successDelete($id){
		$this->view->user = UserModel::getMapper()->findOneBy("id",intval($id));
		if(!$this->view->user){
			$this->view->warningmessage = "Cannot find specific user.";
		}else{
			UserModel::getMapper()->setModel($this->view->user)->delete();
			$this->view->message = "Succesfully deleted user with ID ".$id;
		}
		return $this->view->getContentView("user/delete.phtml");
	}

	public function view(){
		$this->view->attributes = array_keys(UserModel::$attributes);
		$this->view->users = UserModel::getMapper()->findAll();
		return $this->view->getContentView("user/users.phtml");
	}


}
