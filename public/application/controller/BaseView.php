<?php
class BaseView
{
	private $data = [];
	private $template = "default";

	public function __construct(){
	}

  public function __set($name, $value)
  {
    return $this->setData($name, $value);
  }

  public function __get($name)
  {
    return $this->getData($name);
  }

  public function setData($key, $value)
  {
    $this->data[$key] = $value;
  }

  public function getData($key)
  {
    return $this->data[$key];
  }

	public function getContentView($viewName){
		$template = file_get_contents(FILESYSTEM_PATH."/application/templates/".$this->template.".phtml");
		ob_start();
		include(FILESYSTEM_PATH."/application/view/$viewName");
		$content = ob_get_contents();
		ob_end_clean();
		$template = str_replace("[[CONTENT]]",$content,$template);
		return $template;
	}

}
