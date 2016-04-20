<?php
	class BaseHelper{
	
		public static function debugMessage($message){
			echo "<pre>";
			if(is_array($message) || is_object($message)){
				print_r($message);
			}else{
				echo $message;
			}
			echo "</pre>";
		}
	}
