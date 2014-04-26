<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once('../classes/survey.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class SurveyTest extends PHPUnit_Extensions_OutputTestCase
{
	protected static $survey;
	protected static $id;
	protected static $_REQUEST;
	protected static $db;
	protected static $fieldtest;
	protected static $newfieldval;
	
	
	/**
	 * @group Survey
	 */
	protected function setUp()
	{	
		// setup array to insert 
    	$_SESSION['user']['client_id'] = '10000000';

		self::$_REQUEST['account_id'] = "";
		self::$_REQUEST['survey_title'] = "";
		self::$_REQUEST['survey_type'] = "";
		self::$_REQUEST['start_date'] = "";
		self::$_REQUEST['end_date'] = "";
		
		
		
		self::$fieldtest = 'title';
		self::$newfieldval = '' ;
		
	   	//instigate classes
		self::$survey = new survey();
    	self::$db = new db();
	}
	
	/**
	 * @group Survey
	 */
	public function testSaveSurveyDetails()
    {	
		$_REQUEST = self::$_REQUEST;
		    	
    	//setup request for update
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$survey->saveSurveyDetails();
    	
    	$_REQUEST[self::$fieldtest] = self::$_REQUEST[self::$fieldtest];

		//Save the information  
    	self::$survey->saveSurveyDetails();
    	
    	//get last insert for ID 
    	$id1 = self::$db->select('SELECT MAX(survey_id) AS id FROM survey');
    	self::$id = $id1[0]['id']; 
    	//Uuse last ID to find the record
    	$fields = self::$db->select('SELECT * FROM survey WHERE survey_id = '.self::$id);

    	//Assertions check for all information put in 
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['account_id'], $fields[0]['account_id'], 'this is account_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['survey_title'], $fields[0]['survey_title'], 'this is survey_title');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['survey_type'], $fields[0]['survey_type'], 'this is survey_type');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['start_date'], $fields[0]['start_date'], 'this is start_date');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['end_date'], $fields[0]['end_date'], 'this is end_date');
		
    }
	
    /**
	 * @group Survey
	 */
	public function testShowSurveyDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="location.href=\'(.+?)\.php\?action=edit&id=([0-9]+)\'">Edit<\/div>/');
    	self::$survey->showSurveyDetails(self::$id);
    }
	
    /**
	 * @group Survey
	 */
    public function testGetSurveyList(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	self::$survey->getSurveyList();
    	self::$survey->getSurveyList('AJAX', self::$fieldtest, 'DESC', array('='=>self::$_REQUEST[self::$fieldtest]));
    }
    
    /**
	 * @group Survey
	 */
	public function testCreateSurveyDetails(){
    	$this->expectOutputRegex('/value=\"\"/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Save<\/div>/');
    	self::$survey->createSurveyDetails(self::$id);
    }
    
    /**
	 * @group Survey
	 */
    public function testEditSurveyDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Update<\/div>/');
    	self::$survey->editSurveyDetails(self::$id);
    }
	
    /**
	 * @group Survey
	 */
	public function testUpdateSurveyDetails(){
    	
    	//setup request for update
		$_REQUEST = self::$_REQUEST;
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$survey->updateSurveyDetails(self::$id);
    	
    	$_REQUEST[self::$fieldtest] = self::$newfieldval ;
    	
    	//update with change
    	self::$survey->updateSurveyDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is not deleted  
    	$fields = self::$db->select("SELECT * FROM survey WHERE survey_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST[self::$fieldtest], $fields[0][self::$fieldtest], 'this is title2');
    	PHPUnit_Framework_Assert::assertEquals(self::$_REQUEST[''], $fields[0][''], 'Start date title2');
    	PHPUnit_Framework_Assert::assertEquals(false, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));
    }
    
	/**
	 * @group Survey
	 */
    public function testDeleteSurveyDetails(){
    	$blankval ='';
    	self::$survey->deleteSurveyDetails($blankval);
    	
    	$fields = self::$db->select("SELECT delete_date FROM survey WHERE survey_id = ".self::$id );
    
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] == '0000-00-00 00:00:00' || empty($fields[0]['delete_date'])));    	
    	
    	//mark entry as delete
    	self::$survey->deleteSurveyDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is deleted 
    	$fields = self::$db->select("SELECT delete_date FROM survey WHERE survey_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));

    }
 }  
 
 
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumSurveyTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
	    $this->setBrowser("*firefox");
	    $this->setBrowserUrl("http://dev/");
  }
	/**
	 * @group Survey
	 */
  public function testWebSurveyDetails()
  {
  
  }
}
 