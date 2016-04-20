<?php

	class BaseMapper{
	
		public $model = null;
		protected $table_name = "";
		
		public function __construct(){
		
		}
		
		public function setModel($model){
			$this->model = $model;
			return $this;
		}
		
		public function getModel(){
			return $this->model;
		}

		public function getModelName(){
			return str_replace("Mapper","Model",get_called_class());
		}

		public function save(){
			$strQuery = "";
			$id = $this->model->id;
			if(isset($id) && ($id > 0)){
				$success = $this->buildUpdate();
			}else{
				$success = $this->buildInsert();
			}
			if($success){
				return $this->model;
			}else{
				return null;
			}
		}

		public function findOneBy($field,$value,$orderBy=array()){
			$modelName = $this->getModelName();
			$attributes = $modelName::$attributes;
			$whereClause = array("$field = :$field");
			$data = array($field => $value);

			$select = new Select();
			$select->fromTable($this->table_name)
										->where($whereClause)
										->withData($data);
			if(count($orderBy) > 0){
				$select->orderBy($orderBy);
			}
			$result = $select->execute();

			$model = null;
			$row = $select->fetchPDOAssoc($result);
			if($row){
				$model = $modelName::createObject($row,$attributes);
			}
			return $model;
		}

		public function findAll($orderBy=array()){
			$modelName = $this->getModelName();
			$attributes = $modelName::$attributes;
			$whereClause = array();
			$data = array();

			$select = new Select();
			$select->fromTable($this->table_name)
										->where($whereClause)
										->withData($data);
			if(count($orderBy) > 0){
				$select->orderBy($orderBy);
			}
			$result = $select->execute();

			$models = array();
			while($row = $select->fetchPDOAssoc($result)){
				$models[] = $modelName::createObject($row,$attributes);
			}		
			return $models;
		}		

		public function findAllBy($field,$value,$orderBy=array()){
			$modelName = $this->getModelName();
			$attributes = $modelName::$attributes;
			$whereClause = array("$field = :$field");
			$data = array($field => $value);

			$select = new Select();
			$select->fromTable($this->table_name)
										->where($whereClause)
										->withData($data);
			if(count($orderBy) > 0){
				$select->orderBy($orderBy);
			}
			$result = $select->execute();

			$models = array();
			while($row = $select->fetchPDOAssoc($result)){
				$models[] = $modelName::createObject($row,$attributes);
			}		
			return $models;
		}

		public function convertModelToArray($model){
			$arrayModel = array();
			if($model){
				$arrayModel = get_object_vars($model);
			}
			return $arrayModel;
		}

		public function buildUpdate(){
			$update = new Update();
			$update->intoTable($this->table_name);
			$current_date = new DateTime();

			$modelName = $this->getModelName();
			foreach($modelName::$attributes as $key=>$value){
				if(isset($this->model->$key) && !is_null($this->model->$key)
						&&($key != "id")){
					if($key == "updated_at"){
						$attributes[$key] =  $current_date->format("Y-m-d H:i:s");
					}else{
						$attributes[$key] = $this->model->$key;
					}
				}
			}

			return $update->withValues($attributes)
						 				->where(array('ID' => $this->model->id))
					 	 				->execute();
		}

		public function buildInsert(){
			$insert = new Insert();
			$insert->intoTable($this->table_name);
			$current_date = new DateTime();

			$attributes = array();
			$modelName = $this->getModelName();
			foreach($modelName::$attributes as $key=>$value){
				if(isset($this->model->$key) && !is_null($this->model->$key) 
					&&($key != "id")){
					if($key == "created_at" || $key == "updated_at"){
						$attributes[$key] =  $current_date->format("Y-m-d H:i:s");
					}else{
						$attributes[$key] = $this->model->$key;
					}
				}
			}
			return $insert->withValues($attributes)
					 	 				->execute();
		}

		
	}
