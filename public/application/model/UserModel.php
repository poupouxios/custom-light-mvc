<?php
  class UserModel extends BaseModel{
  
    public static $attributes = array(
      "id" => 0,
      "title" => "",
      "firstname" => "",
      "surname" => "",
      "telephone" => "",
      "email" => "",
      "created_at" => "",
      "updated_at" => "");
    
    public function calculateTotalExpenses(){
      $expenses = ExpenseModel::getMapper()->findAllExpensesByUserId($this->id);
      $total_expenses = 0;
      foreach($expenses as $expense){
        $total_expenses += floatval(abs($expense->expense_value));
      }
      return abs($total_expenses);
    }

    public function calculateTotalSavings(){
      $savings = SavingModel::getMapper()->findAllSavingsByUserId($this->id);
      $total_savings = 0;
      foreach($savings as $saving){
        $total_savings += floatval($saving->saving_value);
      }
      return $total_savings;
    }

  }
