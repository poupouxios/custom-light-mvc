<?php

require_once 'features/bootstrap/BaseContext.php';
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Step;
use Behat\Mink\Exception\UnsupportedDriverActionException,
    Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Driver\GoutteDriver;  
use Behat\MinkBundle\Driver\SymfonyDriver;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

class FeatureContext extends BaseContext
{

	public $pid;

  public function __construct(array $parameters)
  {
    parent::__construct($parameters);
    $this->useAllContexts();
  }


	/** @BeforeScenario */
	public function before($scope)
	{
 		$this->getSession()->getDriver()->resizeWindow(1920, 1080);
	}

	/** @AfterScenario */
	public function after($scope)
	{
		$this->getSession()->restart();
	}

  public function helper($helper_name)
  {
    $helper_class_name = $helper_name . 'Helper';
    $helper_instance = $this->getHelperInstance($helper_class_name);

    return $helper_instance;
  }

  private function getHelperInstance($helper_name)
  {
    $main_context = $this->getMainContext();
    return new $helper_name($main_context);
  }

	private function useAllContexts()
	{
		$context_names = $this->getClassNamesFrom(['directory' => realpath(dirname(__FILE__))]);
		foreach($context_names as $context_name){
			$class = new $context_name([]);
			$this->useContext($context_name,$class);
		}
	}

	private function getClassNamesFrom($sources)
	{
		$directory = $sources['directory'];
		$files = scandir($directory);
		$filtered_files = $this->filterRelativePaths($files);
		$stripped = $this->stripExtensions($filtered_files);

		//class names same as filenames
		return $stripped;
	}

	private function filterRelativePaths($files)
	{
		return array_diff($files, ['.', '..','FeatureContext.php',"Bootstrap.php","BaseContext.php",
		'helpers']);
	}

	private function stripExtensions($files)
	{
		$stripped_files = [];
		foreach($files as $file){
			$file_parts = explode(".",$file);
			$stripped_files[] = $file_parts[0];
		}
		return $stripped_files;
	}

  /**
   * @When /^I go to the "([^"]*)" url$/
   */
  public function iGoToTheUrl($url)
  {
	    $this->visit('/'.$url);
  }

  /**
   * @When /^I go to the Homepage$/
   */
  public function iGoToTheHomepage()
  {
    $this->visit('/');
  }

  /**
    * @Then /^the Page Should Load Successfully$/
    */
  public function thePageShouldLoadSuccessfully()
  {
    $driver = $this->getSession()->getDriver();
    if ($driver instanceof Behat\Mink\Driver\Selenium2Driver){
      $this->getSession()->wait(500, 'document.readySate == "complete" && typeof jQuery != "undefined" && (0 === jQuery.active)');
    }else{
      $tries=0;
      $max_tries = 5;
      sleep(1);
      while($this->getSession()->getStatusCode()==0 && $tries< $max_tries) {
        sleep(1);
        $tries++;
      }
    }
  }
  
  /**
	 * @Given /^(.*) without redirection$/
	 */
	public function theRedirectionsAreIntercepted($step)
	{
		  $this->canIntercept();
		  $this->getSession()->getDriver()->getClient()->followRedirects(false);

		  return new Step\Given($step);
	}

	/**
	 * @When /^I follow the redirection$/
	 * @Then /^I should be redirected$/
	 */
	public function iFollowTheRedirection()
	{
		  $this->canIntercept();
		  $client = $this->getSession()->getDriver()->getClient();
		  $client->followRedirects(true);
		  $client->followRedirect();
	}

  /**
   * @Given /^I press the last button with class "([^"]*)"$/
   */
  public function iPressTheLastButtonWithClass($fieldClass)
  {
      $this->helper("Base")->clickLastButtonByClass($fieldClass);
  }


}
