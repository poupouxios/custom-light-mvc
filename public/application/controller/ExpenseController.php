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
			$this->view->warningmessage = "There are no users available to set an expense. Set a user first and then assign an expense";
		}
		return $this->view->getContentView("expense/addForm.phtml");
	}

	public function view(){
		$this->view->attributes = array_keys(ExpenseModel::$attributes);
		$this->view->expenses = ExpenseModel::getMapper()->findAll();
		return $this->view->getContentView("expense/expenses.phtml");
	}


}
