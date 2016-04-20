<?php

class ExpenseController extends BaseController{
	
	public function add(){
		if(count($_POST) > 0){
			$expense = ExpenseModel::createObject($_POST);
			ExpenseModel::getMapper()->setModel($expense)->save();
			$this->view->message = "Successfully saved";
		}
		$this->view->users = UserModel::getMapper()->findAll();
		if(count($this->view->users) == 0){
			$this->view->warningmessage = "There are no users available to set an expense. Set a user first and then assign an expense.";
		}
		return $this->view->getContentView("expense/addForm.phtml");
	}

	public function edit($id){
		$expense_id = intval($id);
		if(count($_POST) > 0){
			$expense = ExpenseModel::getMapper()->findOneBy("id",$expense_id);
			if($expense){
				$expense->updateValues($_POST);
				ExpenseModel::getMapper()->setModel($expense)->save();
				$this->view->message = "Successfully updated";
			}else{
				$this->view->warningmessage = "Error updating.";
			}
		}

		$this->view->expense = ExpenseModel::getMapper()->findOneBy("id",$expense_id);
		$this->view->users = UserModel::getMapper()->findAll();
		if(count($this->view->users) == 0){
			$this->view->warningmessage = "There are no users available to set an expense. Set a user first and then assign an expense.";
		}

		return $this->view->getContentView("expense/edit.phtml");
	}

	public function delete($id){
		$this->view->expense = ExpenseModel::getMapper()->findOneBy("id",intval($id));
		if(!$this->view->expense){
			$this->view->warningmessage = "Cannot find specific expense.";
		}
		return $this->view->getContentView("expense/confirmDelete.phtml");
	}

	public function successDelete($id){
		$this->view->expense = ExpenseModel::getMapper()->findOneBy("id",intval($id));
		if(!$this->view->expense){
			$this->view->warningmessage = "Cannot find specific expense.";
		}else{
			ExpenseModel::getMapper()->setModel($this->view->expense)->delete();
			$this->view->message = "Succesfully deleted expense with ID ".$id;
		}
		return $this->view->getContentView("expense/delete.phtml");
	}


	public function view(){
		$this->view->attributes = array_keys(ExpenseModel::$attributes);
		$this->view->expenses = ExpenseModel::getMapper()->findAll();
		return $this->view->getContentView("expense/expenses.phtml");
	}


}
