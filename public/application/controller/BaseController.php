<?php
class BaseController
{
	public $view;

	public function __construct(){
		$this->view = new BaseView();
		$this->view->message = "";
		$this->view->warningmessage = "";
	}

}
