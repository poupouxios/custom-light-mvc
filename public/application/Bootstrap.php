<?php
class Bootstrap
{
  public $view;
  public $controller;

  public function __construct($request){
    $this->request = $request;
		$this->view = $this->callHook();
  }

  public function render(){
    echo $this->view;
  }

	function callHook() {
			$urlArray = array();
			$urlArray = explode("/",$this->request);

			if(!isset($urlArray[0])){
				$view = new BaseView();
				return $view->getContentView("home.html");
			}
			$controller = $urlArray[0];
			array_shift($urlArray);
			$action = explode("?",$urlArray[0]);
			if(isset($action[1])){
    		$queryString = array($action[1]);
      }else{
        $queryString = array();
      }

			$controllerName = $controller;
			$controller = ucwords($controller);
			$model = rtrim($controller, 's')."Model";
			$controller .= 'Controller';
			$this->controller = $controller;

			if (class_exists($model))
			{
        //echo $model."<br/>".$controller."<br/>".$action[0]."<br/>",print_r($queryString);
				$dispatch = new $controller();
			}

			if ((int)method_exists($controller, $action[0])) {
				return call_user_func_array(array($dispatch,$action[0]),$queryString);
			} else {
				return "";
			}
		}

		private function getWrapperTemplate(){
		  $wrapper = file_get_contents('application/template.html');
		  return $wrapper;
		}

}
