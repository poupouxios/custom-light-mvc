<?php

  require_once ('config/config.php');
  $baseDirectory = FILESYSTEM_PATH.'/../db/seed-data/';
  require_once ($baseDirectory."SeedDataInterface.php");
  require_once ($baseDirectory."BaseSeedData.php");
  require_once ($baseDirectory."ExtraSeedMethods.php");
  $type = $argv[1];

  switch($type){
    case "execute":
      $seed_files = scandir($baseDirectory."seed-files/");
      foreach($seed_files as $seed_file){
        if(strstr($seed_file,".php") !== FALSE){
          $class_name = str_replace(".php","",$seed_file);
          $seed_migrationModel = SeedMigrationModel::getMapper()->findOneBy("class_name",$class_name);
          if(!$seed_migrationModel){
            echo "Executing $class_name\n";
            require_once $baseDirectory."seed-files/".$seed_file;
            try{
              $seed_data = new $class_name();
              $seed_data->create();
              $seed_data->update();
              $seed_data->markMigration();
              echo "Finished $class_name execution\n";
            }catch(Exception $e){
              echo "Error: ".$e->getMessage().PHP_EOL;
              exit;
            }
          }
        }
       }
       break;
    case "create":
       if(!isset($argv[2])){
       die();
        displayErrorMessage();
       }else{
        $class_name = $argv[2];
        $date = new DateTime();
        $full_date = $date->format("YmdHis");
        $seed_class_name = "Seed_".$full_date."_".str_replace(" ","",ucwords($class_name,"_"));
        $php_file = fopen($baseDirectory."seed-files/".$seed_class_name.".php", "w");
        $content = "<?php\n
  class $seed_class_name extends BaseSeedData{\n
    public function create(){}\n
    public function update(){}\n
  }\n\n?>";
        fwrite($php_file, $content);
        fclose($php_file);
       }
       break;
    default:
      displayErrorMessage();
  }

function displayErrorMessage(){
  echo "\nWrong type provided. The available types are:
  seed.php execute
  seed.php create <name_of_class>\n\n";
}
