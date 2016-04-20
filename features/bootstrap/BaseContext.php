<?php
use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Context\Step\Then,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

require_once 'features/bootstrap/Bootstrap.php';

class BaseContext extends MinkContext
{
  private $data = [];
  public $phactory = null;

  public function __construct(array $parameters)
  {
    $pdo = new Pdo("mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME, DATABASE_USER, DATABASE_PASS);
    $this->phactory = new Phactory\Sql\Phactory($pdo);
  }

  public function __set($name, $value)
  {
		if(is_array($value)){
			$new_value = serialize($value);
			return $this->setData($name, $new_value);
		}else{
	    return $this->setData($name, $value);
		}
  }

  public function __get($name)
  {
		$value = $this->getData($name);
		if(is_object($value) || is_array($value)){
			return $value;
		}else{
			$array_value = @unserialize($value);
			if($array_value !== FALSE){
				return $array_value;
			}else{
			  return $value;
			}
		}
  }

  public function setData($key, $value)
  {
    $this->data[$key] = $value;
  }

  public function getData($key)
  {
    return $this->data[$key];
  }
  
  /** @BeforeScenario */
  public static function setupFeature()
  {
    resetDatabase();
  }
  
  /**
  * @Then /^I should debug$/
  */
  public function iShouldDebug()
  {
    echo 'Page Content: ' . PHP_EOL . $this->getSession()->getPage()->getContent()
    	. PHP_EOL;
  }

	/**
   * @When /^I wait for "([^"]*)" to appear$/
   */
  public function iWaitForToAppear($text){
		  $this->spin(function(FeatureContext $context) use ($text) {
		      try {
		          $context->assertPageContainsText($text);
		          return true;
		      }
		      catch(ResponseTextException $e) {
		          // NOOP
		      }
		      return false;
		  });
	}

	/**
     * @Given /^I wait to press "([^"]*)"$/
     */
  public function iWaitToPress($button_name){
		  $this->spin(function(FeatureContext $context) use ($button_name) {
		      try {
							$context->getSession()->getPage()->findButton($button_name)->click();
		          return true;
		      }
		      catch(ResponseTextException $e) {
		          // NOOP
		      }
		      return false;
		  });
	}

	public function spin($lambda, $wait = 80)
	{
		  $time = time();
		  $stopTime = $time + $wait;
		  while (time() < $stopTime)
		  {
		      try {
		          if ($lambda($this)) {
		              return;
		          }
		      } catch (\Exception $e) {
		          // do nothing
		      }

		      sleep(2);
		  }

		  throw new \Exception("Spin function timed out after {$wait} seconds");
	}

  /**
  * @Then /^I should find "([^"]*)"$/
  */
  public function iShouldFind($string)
  {
    $content = $this->getSession()->getPage()->getContent();
		if(strstr($content,$string) === FALSE){
			throw new Exception("The text $string was not found in the page");
		}
  } 

  /**
   * @When /^I refresh the page$/
   */
  public function iRefreshThePage()
  {
      $this->reload();
  }
  
  /**
   * @Then /^I show the page$/
   */
  public function iShowThePage()
  {
      print_r($this->getSession()->getPage()->getContent());
      
  }
  
  /**
   * @When /^I follow the link "([^"]*)"$/
   */
  public function iClickTheLink($locator) 
  {
     $page =  $this->getSession()->getPage();
     $element = $page->find("css", "a:contains('$locator')");
     $element->click();
  }

  public function page()
  {
     $page = $this->getSession()->getPage();
     return $page;
  }
  
  /**
   * @When /^I check the "([^"]*)" radio button$/
   */
   public function iCheckTheRadioButton($labelText)
   {
     print_r($labelText);
         $i=0;
       foreach ($this->getMainContext()->getSession()->getPage()->findAll('css', 'label') as $label) {
            $i++;
           if ($labelText === $label->getText() && $label->has('css', 'input[type="radio"]')) {
               $this->getMainContext()->fillField($label->find('css', 'input[type="radio"]')->getAttribute('id'), $label->find('css', 'input[type="radio"]')->getAttribute('value'));
               return;
           }
       }
       
       $page=   $this->getSession()->getPage();
       throw new \Exception('Radio button "'.$labelText.' " not found');
   }
  
  
  /**
   * @Then /^Radio button with id "([^"]*)" should be checked$/
   */
  public function RadioButtonWithIdShouldBeChecked($sId)
  {
      $elementByCss = $this->getSession()->getPage()->find('css', 'input[type="radio"]:checked#'.$sId);
      if (!$elementByCss) {
          throw new Exception('Radio button with id ' . $sId.' is not checked');
      }
  }

}
