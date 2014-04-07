<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once('../classes/account.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class AccountTest extends PHPUnit_Extensions_OutputTestCase
{
	protected static $account;
	protected static $id;
	protected static $_REQUEST;
	protected static $db;
	protected static $fieldtest;
	protected static $newfieldval;
	
	
	/**
	 * @group Account
	 */
	protected function setUp()
	{	
		// setup array to insert 
    	$_SESSION['user']['client_id'] = '10000000';

		self::$_REQUEST['email'] = "";
		self::$_REQUEST['password'] = "";
		self::$_REQUEST['name'] = "";
		self::$_REQUEST['surname'] = "";
		self::$_REQUEST['company'] = "";
		self::$_REQUEST['phone'] = "";
		self::$_REQUEST['address'] = "";
		self::$_REQUEST['address2'] = "";
		self::$_REQUEST['suburb'] = "";
		self::$_REQUEST['postcode'] = "";
		self::$_REQUEST['state'] = "";
		self::$_REQUEST['modifiy_date'] = "";
		
		
		
		self::$fieldtest = 'title';
		self::$newfieldval = '' ;
		
	   	//instigate classes
		self::$account = new account();
    	self::$db = new db();
	}
	
	/**
	 * @group Account
	 */
	public function testSaveAccountDetails()
    {	
		$_REQUEST = self::$_REQUEST;
		    	
    	//setup request for update
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$account->saveAccountDetails();
    	
    	$_REQUEST[self::$fieldtest] = self::$_REQUEST[self::$fieldtest];

		//Save the information  
    	self::$account->saveAccountDetails();
    	
    	//get last insert for ID 
    	$id1 = self::$db->select('SELECT MAX(account_id) AS id FROM account');
    	self::$id = $id1[0]['id']; 
    	//Uuse last ID to find the record
    	$fields = self::$db->select('SELECT * FROM account WHERE account_id = '.self::$id);

    	//Assertions check for all information put in 
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['email'], $fields[0]['email'], 'this is email');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['password'], $fields[0]['password'], 'this is password');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['name'], $fields[0]['name'], 'this is name');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['surname'], $fields[0]['surname'], 'this is surname');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['company'], $fields[0]['company'], 'this is company');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['phone'], $fields[0]['phone'], 'this is phone');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['address'], $fields[0]['address'], 'this is address');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['address2'], $fields[0]['address2'], 'this is address2');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['suburb'], $fields[0]['suburb'], 'this is suburb');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['postcode'], $fields[0]['postcode'], 'this is postcode');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['state'], $fields[0]['state'], 'this is state');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['modifiy_date'], $fields[0]['modifiy_date'], 'this is modifiy_date');
		
    }
	
    /**
	 * @group Account
	 */
	public function testShowAccountDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="location.href=\'(.+?)\.php\?action=edit&id=([0-9]+)\'">Edit<\/div>/');
    	self::$account->showAccountDetails(self::$id);
    }
	
    /**
	 * @group Account
	 */
    public function testGetAccountList(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	self::$account->getAccountList();
    	self::$account->getAccountList('AJAX', self::$fieldtest, 'DESC', array('='=>self::$_REQUEST[self::$fieldtest]));
    }
    
    /**
	 * @group Account
	 */
	public function testCreateAccountDetails(){
    	$this->expectOutputRegex('/value=\"\"/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Save<\/div>/');
    	self::$account->createAccountDetails(self::$id);
    }
    
    /**
	 * @group Account
	 */
    public function testEditAccountDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Update<\/div>/');
    	self::$account->editAccountDetails(self::$id);
    }
	
    /**
	 * @group Account
	 */
	public function testUpdateAccountDetails(){
    	
    	//setup request for update
		$_REQUEST = self::$_REQUEST;
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$account->updateAccountDetails(self::$id);
    	
    	$_REQUEST[self::$fieldtest] = self::$newfieldval ;
    	
    	//update with change
    	self::$account->updateAccountDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is not deleted  
    	$fields = self::$db->select("SELECT * FROM account WHERE account_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST[self::$fieldtest], $fields[0][self::$fieldtest], 'this is title2');
    	PHPUnit_Framework_Assert::assertEquals(self::$_REQUEST[''], $fields[0][''], 'Start date title2');
    	PHPUnit_Framework_Assert::assertEquals(false, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));
    }
    
	/**
	 * @group Account
	 */
    public function testDeleteAccountDetails(){
    	$blankval ='';
    	self::$account->deleteAccountDetails($blankval);
    	
    	$fields = self::$db->select("SELECT delete_date FROM account WHERE account_id = ".self::$id );
    
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] == '0000-00-00 00:00:00' || empty($fields[0]['delete_date'])));    	
    	
    	//mark entry as delete
    	self::$account->deleteAccountDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is deleted 
    	$fields = self::$db->select("SELECT delete_date FROM account WHERE account_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));

    }
 }  
 
 
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumAccountTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
	    $this->setBrowser("*firefox");
	    $this->setBrowserUrl("http://dev/");
  }
	/**
	 * @group Account
	 */
  public function testWebAccountDetails()
  {
  
  }
}
 