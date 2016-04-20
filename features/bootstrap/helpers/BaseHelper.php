<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class BaseHelper extends BehatContext
{
  private $main_context;

  public function __construct($main_context)
  {
    $this->main_context = $main_context;
  }

	public function convertArrayToObject($data){
		$object = new StdClass();
		foreach ($data as $key => $value)
		{
				$object->$key = $value;
		}
		return $object;
	}

  protected function mainContext()
  {
    return $this->main_context;
  }
  
	private function getDriver(){
		return $this->mainContext()->getSession()->getDriver();
	}

  public function fillHiddenField($field,$value)
  {
    $driver = $this->mainContext()->getSession()->getDriver();
    if ($driver instanceof Behat\Mink\Driver\Selenium2Driver){
      $script = <<<Javascript
    document.getElementById('{$field}').value = '{$value}';
Javascript;
      $driver->evaluateScript($script);
    }else{
      $element = $this->mainContext()->getSession()->getPage()
        ->find('css', 'input[name="'.$field.'"]');
      $element->setValue($value);
    }
  }
  
  public function clickSubmitButtonInsideAContainer($fieldValue,$containerId){
		$page = $this->mainContext()->getSession()->getPage();
		$container = $page->find('css','#'.$containerId);
		$field = $container->findButton($fieldValue);
		if($field == null){
			$field = $container->findById($fieldValue);
		}
		$field->click();  
  }

	public function clickLastButtonByClass($fieldClass){
		$this->mainContext()->spin(function($context) use ($fieldClass) {
			$page = $context->getSession()->getPage();
			$buttons = $page->findAll('css','.'.$fieldClass);
			if(count($buttons) > 0){
					$buttons[count($buttons) -1]->click();
					return true;
			}
			return false;
		});

	}

  public function followLinkInsideAContainer($fieldValue,$containerId){
		$page = $this->mainContext()->getSession()->getPage();
		$container = $page->find('css','#'.$containerId);
		$field = $container->findLink($fieldValue);
    $field->click();  
  }
  
  public function setFieldInsideAContainer($fieldId, $containerId, $fieldValue){
		$page = $this->mainContext()->getSession()->getPage();
		$container = $page->find('css','#'.$containerId);
    if ($container){
      $field = $container->findField($fieldId);
			if($field == null){
				$field = $container->findById($fieldId);
			}
      $field->setValue($fieldValue);  
    }
  }

  public function checkRadioButtonByLabelText($labelText)
  {
    $page = $this->mainContext()->getSession()->getPage();

    foreach($page->findAll('css', 'label') as $label){
			
      if ($label->getText() != $labelText) continue;

      if ($label->getAttribute('for')){
        $element = $page->find('css', '#' . $label->getAttribute('for'));
      }else{
        $element = $label->find('css', 'input[type=radio]');
      }

	    if ($this->getDriver() instanceof Behat\Mink\Driver\Selenium2Driver){
				$element->check();
			}else{
				$this->mainContext()->fillField($element->getAttribute('id'), $element->getAttribute('value'));
			}
    }
  }
}
