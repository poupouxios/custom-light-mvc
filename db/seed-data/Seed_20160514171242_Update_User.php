<?php

  class Seed_20160514171242_Update_User extends BaseSeedData{

    public function create(){}

     public function update(){
      $userModel = UserModel::getMapper()->findOneBy("firstname","Jessica");
      if($userModel){
        $userModel->title="Miss";
        UserModel::getMapper()->setModel($userModel)->save();
      }else{
        throw new Exception("Cannot find user Jessica");
      }
    }

  }

?>
