<?php

class SavingController extends BaseController{
	
	public function add(){
		if(count($_POST) > 0){
			$saving = SavingModel::createObject($_POST);
			SavingModel::getMapper()->setModel($saving)->save();
			$this->view->message = "Successfully saved";
		}
		$this->view->users = UserModel::getMapper()->findAll();
		if(count($this->view->users) == 0){
			$this->view->warningmessage = "There are no users available to set an saving. Set a user first and then assign an saving.";
		}
		return $this->view->getContentView("saving/addForm.phtml");
	}

	public function edit($id){
		$saving_id = intval($id);
		if(count($_POST) > 0){
			$saving = SavingModel::getMapper()->findOneBy("id",$saving_id);
			if($saving){
				$saving->updateValues($_POST);
				SavingModel::getMapper()->setModel($saving)->save();
				$this->view->message = "Successfully updated";
			}else{
				$this->view->warningmessage = "Error updating.";
			}
		}

		$this->view->saving = SavingModel::getMapper()->findOneBy("id",$saving_id);
		$this->view->users = UserModel::getMapper()->findAll();
		if(count($this->view->users) == 0){
			$this->view->warningmessage = "There are no users available to set an saving. Set a user first and then assign an saving.";
		}

		return $this->view->getContentView("saving/edit.phtml");
	}

	public function delete($id){
		$this->view->saving = SavingModel::getMapper()->findOneBy("id",intval($id));
		if(!$this->view->saving){
			$this->view->warningmessage = "Cannot find specific saving.";
		}
		return $this->view->getContentView("saving/confirmDelete.phtml");
	}

	public function successDelete($id){
		$this->view->saving = SavingModel::getMapper()->findOneBy("id",intval($id));
		if(!$this->view->saving){
			$this->view->warningmessage = "Cannot find specific saving.";
		}else{
			SavingModel::getMapper()->setModel($this->view->saving)->delete();
			$this->view->message = "Succesfully deleted saving with ID ".$id;
		}
		return $this->view->getContentView("saving/delete.phtml");
	}


	public function view(){
		$this->view->attributes = array_keys(SavingModel::$attributes);
		$this->view->savings = SavingModel::getMapper()->findAll();
		return $this->view->getContentView("saving/savings.phtml");
	}


}
