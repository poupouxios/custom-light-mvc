<?php

  class Seed_20160514171238_Update_User extends BaseSeedData{

    public function create(){}

    public function update(){
      $userModel = UserModel::getMapper()->findOneBy("firstname","John");
      if($userModel){
        $userModel->title="Sir";
        UserModel::getMapper()->setModel($userModel)->save();
      }else{
        throw new Exception("cannot find user John");
      }
    }

  }

?>
