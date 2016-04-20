<?php

	require_once ('config/config.php');
	$baseDirectory = FILESYSTEM_PATH.'../db/seed-data/';
	require_once ($baseDirectory."BaseSeedData.php");
	$seed_files = scandir($baseDirectory);

	foreach($seed_files as $seed_file){
		if(strstr($seed_file,".php") !== FALSE && strstr($seed_file,"BaseSeedData") === FALSE){
			$class_name = str_replace(".php","",$seed_file);
			$seed_migrationModel = SeedMigrationModel::getMapper()->findOneBy("class_name",$class_name);
			if(!$seed_migrationModel){
				echo "Executing $class_name\n";
				require_once $baseDirectory.$seed_file;
				$seed_data = new $class_name();
				$seed_data->create();
				$seed_data->update();
				$seed_data->markMigration();
				echo "Finished $class_name execution\n";
			}
		}
	}
