<?php
	
	abstract class BaseSeedData{

		abstract public function create();

		abstract public function update();

		public function markMigration(){
			$data = [];
			$data['class_name'] = get_called_class();
			$seedMigration = SeedMigrationModel::createObject($data,SeedMigrationModel::$attributes);
			SeedMigrationModel::getMapper()->setModel($seedMigration)->save();
		}

		protected function getCurrentDateForSql(){
			$current_date = new DateTime();
			return $current_date->format("Y-m-d h:m:s");
		}

	}
