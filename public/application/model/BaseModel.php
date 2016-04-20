<?php

	class BaseModel{
		public $id;
		public static $attributes;

		public function __construct(){
			$this->setObject();		
		}

		public static function getMapper(){
			$mapper_class_name = str_replace("Model","Mapper",get_called_class());
			$mapper = new $mapper_class_name();
			return $mapper;
		}
		
		public function setId($id){
			$this->id = $id;
			return $this;
		}

		public function getId(){
			return $this->id;
		}

		public static function createObject($data){
			$called_class = get_called_class();
			$attributes = $called_class::$attributes;
			$object = new $called_class();
			foreach($data as $key => $value){
				if(array_key_exists($key,$attributes)){
					$object->$key = $value;
				}
			}
			return $object;
		}

		public function setAttributes($data){
			foreach($data as $key=>$value){
				if(isset($this->attributes[$key])){
					$this->attributes[$key] = $value;
				}
			}
		}

		private function setObject(){
			foreach($this::$attributes as $key => $value){
				$this->$key = $value;
			}
		}

}
