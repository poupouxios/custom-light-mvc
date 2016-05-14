<?php

  class Seed_20160514171052_New_User extends BaseSeedData{

    public function create(){
      $user = new UserModel();
      $user->title = "Mrs";
      $user->firstname = "Jessica";
      $user->surname = "Smith";
      $user->telephone = "498594895";
      $user->email = "jessica@smith.com";
      UserModel::getMapper()->setModel($user)->save();
    }

    public function update(){}

  }

?>
