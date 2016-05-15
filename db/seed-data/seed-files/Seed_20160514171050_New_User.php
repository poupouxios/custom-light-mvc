<?php

  class Seed_20160514171050_New_User extends BaseSeedData{

    public function create(){
      $user = new UserModel();
      $user->title = "Mr";
      $user->firstname = "John";
      $user->surname = "Smith";
      $user->telephone = "498594895";
      $user->email = "john@smith.com";
      UserModel::getMapper()->setModel($user)->save();
    }

    public function update(){}

  }

?>
