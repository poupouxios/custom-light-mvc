<?php 

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

require_once 'features/bootstrap/helpers/BaseHelper.php';

class MinkSubContext extends BehatContext
{
  public function __call($method_name, $args)
  {
    $main_context = $this->getMainContext();
    return  call_user_func_array([$main_context, $method_name], $args);
  }

  public function __set($name, $value)
  {
    $main_context = $this->getMainContext();
    $main_context->setData($name, $value);
  }

  public function __get($name)
  {
    $main_context = $this->getMainContext();
    $value = $main_context->getData($name);
		if(is_object($value) || is_array($value)){
			return $value;
		}else{
			$array_value = unserialize($value);
			if($array_value !== FALSE){
				return $array_value;
			}else{
				return $value;
			}
		}
  }

}
