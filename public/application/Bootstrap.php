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

  public function callHook() {
    $urlArray = array();
    $urlArray = explode("/",$this->request);

    if(!isset($urlArray[0]) || strlen($urlArray[0]) == 0){
      $view = new BaseView();
      return $view->getContentView("home.html");
    }
    $controller = $urlArray[0];
    array_shift($urlArray);

    $action = "";
    $queryString = array();
    if(isset($urlArray[0])){
      $action = $urlArray[0];
      array_shift($urlArray);
      
      while(isset($urlArray[0])){
        $queryString[] = $urlArray[0];
        array_shift($urlArray);
      }
    }
      
    $controllerName = $controller;
    $controller = ucwords($controller);
    $model = rtrim($controller, 's')."Model";
    $controller .= 'Controller';
    $this->controller = $controller;

    if (class_exists($model))
    {
      $dispatch = new $controller();
    }

    if ((int)method_exists($controller, $action)) {
      return call_user_func_array(array($dispatch,$action),$queryString);
    } else {
      return "";
    }
  }

  private function getWrapperTemplate(){
    $wrapper = file_get_contents('application/template.html');
    return $wrapper;
  }

}
