<?php

  trait ExtraSeedMethods{

    public function getCurrentDateForSql(){
      $current_date = new DateTime();
      return $current_date->format("Y-m-d h:m:s");
    }

  }
