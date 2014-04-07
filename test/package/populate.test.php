<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once '/usr/share/php/PHPUnit/Extensions/OutputTestCase.php';

require_once('../classes/survey.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class populate extends PHPUnit_Extensions_OutputTestCase{
	
	
}
 
 
require_once '/usr/share/php/PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumPopulate extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
	    $this->setBrowser("*firefox");
	    $this->setBrowserUrl("http://dev/");
  }
  
  /*public function testAddAccountCase()
  {
    $this->open("/esurveyit/index.php");
    $this->click("link=Registor");
    $this->waitForPageToLoad("500");
    $this->type("email", "jason@lexxcom.com");
    $this->type("//div[@id='register-body']/fieldset/div[4]/input", "jason@lexxcom.com");
    $this->type("password", "cowcow");
    $this->type("//div[@id='register-body']/fieldset/div[8]/input", "cowcow");
    $this->type("name", "jason");
    $this->type("surname", "Stewart");
    $this->type("phone", "0405833502");
    $this->click("css=div.button");
    $this->waitForPageToLoad("500");
    $this->click("link=Home");
    $this->waitForPageToLoad("500");
    $this->type("email", "jason@lexxcom.com");
    $this->type("password", "cowcow");
    $this->click("css=input[type=image]");
    $this->waitForPageToLoad("500");
  }*/

  
	/**
	 * @group Survey
	 */
  public function testWebSurveyDetails()
  {
    $this->open("/esurveyit/");
    $this->click("link=Registor");
    $this->waitForPageToLoad("500");
    $this->type("email", "jason@lexxcom.com");
    $this->type("//div[@id='register-body']/fieldset/div[4]/input", "jason@lexxcom.com");
    $this->type("password", "cowcow");
    $this->type("//div[@id='register-body']/fieldset/div[8]/input", "cowcow");
    $this->type("name", "jason");
    $this->type("surname", "Stewart");
    $this->type("phone", "0405833502");
    $this->click("css=div.button");
    $this->waitForPageToLoad("500");
    $this->click("link=Home");
    $this->waitForPageToLoad("500");
    $this->type("email", "jason@lexxcom.com");
    $this->type("password", "cowcow");
    $this->click("css=input[type=image]");
    $this->waitForPageToLoad("500");
    $this->click("link=New Survey");
    $this->waitForPageToLoad("1000");
    $this->assertEquals("Create New Survey", $this->getText("css=div.title-bar > h2"));
    $this->click("newtitle");
    $this->type("newtitle", "Test 1");
    $this->type("newdetails", "This is a test");
    $this->click("add-survey");
    $this->click("create");
    $this->type("newquestion", "Question 1");
    $this->click("add-question");
    $this->click("//table[2]/tbody/tr[2]/td[2]/div[1]/a/img");
    sleep(1);
    $this->click("//li[@id='qid_1']/div[1]");
    sleep(1);
    $this->type("array-heading", "Column 1");
    $this->click("array-heading-add");
    $this->type("array-heading", "Column 2");
    $this->click("array-heading-add");
    $this->type("array-heading", "Column 3");
    $this->click("array-heading-add");
    $this->type("array-heading", "Column 4");
    $this->click("array-heading-add");
    $this->click("create");
    $this->type("newquestion", "Question 2");
    $this->select("questionType", "label=Text");
    $this->click("add-question");
    $this->click("//table[3]/tbody/tr[2]/td[2]/div[1]/a/img");
    $this->type("array-row", "Row 1");
    $this->click("array-row-add");
    $this->type("array-row", "Row 2");
    $this->click("array-row-add");
    $this->type("array-row", "Row 3");
    $this->click("array-row-add");
    $this->type("array-row", "Row 4");
    $this->click("array-row-add");
    $this->type("array-row", "Row 5");
    $this->click("array-row-add");
    $this->click("//div[@id='question-box']/div[3]/div/img");
    $this->click("create");
    $this->select("questionType", "label=TextArea");
    $this->type("newquestion", "Question 3");
    $this->click("add-question");
    $this->click("//table[4]/tbody/tr[2]/td[2]/div[1]/a/img");
    $this->click("create");
    $this->type("newquestion", "Question 4");
    $this->select("questionType", "label=Select");
    $this->click("//table[5]/tbody/tr[2]/td[2]/div[1]/a/img");
    $this->click("create");
    $this->click("add-question");
    $this->click("//table[6]/tbody/tr[2]/td[2]/div[1]/a/img");
    $this->click("//li[@id='qid_4']/div[1]");
    sleep(1);
    $this->type("select-row", "Select 1");
    $this->click("select-row-add");
    $this->type("select-row", "Select 2");
    $this->click("select-row-add");
    $this->type("select-row", "Select 3");
    $this->click("select-row-add");
    $this->type("select-row", "Select 4");
    $this->click("select-row-add");
    $this->type("select-row", "Select 5");
    $this->click("select-row-add");
    $this->click("select-row-add");
    $this->type("rowlabel-6", "Select 6");
    $this->click("//div[@id='question-box']/div[3]/div/img");
    $this->click("content");
    $this->click("create");
    $this->type("newquestion", "Question 5");
    $this->select("questionType", "label=Radio");
    $this->click("add-question");
    $this->click("//table[7]/tbody/tr[2]/td[2]/div[1]/a/img");
    $this->click("//li[@id='qid_5']/div[1]");
    sleep(1);
    $this->type("radio-row", "Radio 1");
    $this->click("radio-row-add");
    $this->type("radio-row", "Radio 2");
    $this->click("radio-row-add");
    $this->type("radio-row", "Radio 3");
    $this->click("radio-row-add");
    $this->type("radio-row", "Radio 4");
    $this->click("radio-row-add");
    $this->type("radio-row", "Radio 5");
    $this->click("radio-row-add");
    $this->click("//div[@id='question-box']/div[3]/div/img");
    $this->click("create");
    $this->select("questionType", "label=Checkbox");
    $this->type("newquestion", "Question 6");
    $this->click("add-question");
    $this->click("//table[8]/tbody/tr[2]/td[2]/div[1]/a/img");
    $this->click("//li[@id='qid_6']/div[1]");
    sleep(1);
    $this->type("checkbox-row", "Checkbox 1");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Checkbox 2");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Checkbox 3");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Checkbox 4");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Checkbox 5");
    $this->click("checkbox-row-add");
    $this->click("//div[@id='question-box']/div[3]/div/img");
    $this->click("save-survey");
  }
  
  public function testWebSurvey1Details()
  {
    $this->open("/esurveyit/");
    $this->click("link=Registor");
    $this->waitForPageToLoad("500");
    $this->type("email", "jason@lexxcom.com.au");
    $this->type("//div[@id='register-body']/fieldset/div[4]/input", "jason@lexxcom.com.au");
    $this->type("password", "moomoo");
    $this->type("//div[@id='register-body']/fieldset/div[8]/input", "moomoo");
    $this->type("name", "john");
    $this->type("surname", "smith");
    $this->type("phone", "0405833502");
    $this->click("css=div.button");
    $this->waitForPageToLoad("500");
    $this->click("link=Home");
    $this->waitForPageToLoad("500");
    $this->type("email", "jason@lexxcom.com.au");
    $this->type("password", "moomoo");
    $this->click("css=input[type=image]");
    $this->waitForPageToLoad("500");
    $this->click("link=New Survey");
    $this->waitForPageToLoad("1000");
    sleep(1);
    $this->type("newtitle", "Jason New Test Case");
    $this->type("newdetails", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis convallis cursus velit quis tincidunt. Nam porta nisi at lectus ullamcorper viverra. Integer ultrices mi pulvinar metus fermentum blandit. Aliquam ut urna nec lacus lobortis facilisis. Sed eu tortor sed nulla consectetur gravida. Nulla rutrum hendrerit ligula, sed convallis urna tincidunt vel. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id dui et nisi facilisis egestas. Sed ullamcorper mattis neque et pharetra. Phasellus a facilisis nisl.");
    $this->click("add-survey");
    $this->click("create");
    $this->type("newquestion", "What is the best dog");
    $this->click("add-question");
    $this->click("//table[2]/tbody/tr[2]/td[2]/div[1]/a/img");
    sleep(1);
    $this->click("//li[@id='qid_1']/div[1]");
    sleep(1);
    $this->type("array-heading", "Ductin");
    $this->click("array-heading-add");
    $this->type("array-heading", "Chiwuwa");
    $this->click("array-heading-add");
    $this->type("array-heading", "Great Dan");
    $this->click("array-heading-add");
    $this->type("array-heading", "Snoopy");
    $this->click("array-heading-add");
    $this->type("array-heading", "Mute");
    $this->click("array-heading-add");
    $this->type("array-row", "Walking");
    $this->click("array-row-add");
    $this->type("array-row", "Feeding");
    $this->click("array-row-add");
    $this->type("array-row", "Smelling");
    $this->click("array-row-add");
    $this->type("array-row", "Shooting");
    $this->click("array-row-add");
    $this->type("array-row", "Riding");
    $this->click("array-row-add");
    $this->click("//div[@id='question-box']/div[3]/div/img");
    $this->click("create");
    $this->select("questionType", "label=Select");
    $this->type("newquestion", "What color Dog would you like");
    $this->click("//table[3]/tbody/tr[2]/td[2]/div[1]/a/img");
    $this->click("create");
    $this->click("add-question");
    sleep(1);
    $this->click("//table[4]/tbody/tr[2]/td[2]/div[1]/a/img");
    sleep(1);
    $this->click("//li[@id='qid_2']/div[1]");
    sleep(1);
    $this->type("select-row", "White");
    $this->click("select-row-add");
    $this->type("select-row", "Patchy");
    $this->click("select-row-add");
    $this->type("select-row", "Black");
    $this->click("select-row-add");
    $this->type("select-row", "Spotted");
    $this->click("select-row-add");
    $this->type("select-row", "Green");
    $this->click("select-row-add");
    $this->click("//div[@id='question-box']/div[3]/div/img");
    $this->click("create");
    $this->type("newquestion", "How tall would you like your dog");
    $this->select("questionType", "label=Radio");
    $this->click("add-question");
    $this->click("//table[5]/tbody/tr[2]/td[2]/div[1]/a/img");
    sleep(1);
    $this->click("//li[@id='qid_3']/div[1]");
    sleep(1);
    $this->type("radio-row", "Small");
    $this->click("radio-row-add");
    $this->type("radio-row", "Medium");
    $this->click("radio-row-add");
    $this->type("radio-row", "Large");
    $this->click("radio-row-add");
    $this->type("radio-row", "Huge");
    $this->click("radio-row-add");
    $this->click("//div[@id='question-box']/div[3]/div/img");
    $this->click("create");
    $this->type("newquestion", "What Devices would you like for your dog");
    $this->select("questionType", "label=Checkbox");
    $this->click("add-question");
    $this->click("//table[6]/tbody/tr[2]/td[2]/div[1]/a/img");
    sleep(1);
    $this->click("//li[@id='qid_4']/div[1]");
    sleep(1);
    $this->type("checkbox-row", "Collar");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Bowl");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Kennel");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Chew Toy");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Leash");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Mouth cover");
    $this->click("checkbox-row-add");
    $this->type("checkbox-row", "Rear mounted torrant gun");
    $this->click("checkbox-row-add");
    $this->click("//div[@id='question-box']/div[3]/div/img");
    $this->click("save-survey");
  }


	/**
	 * @group POP
	 * @group POP1
	 */
  public function testWebSurvey3Details()
  { 
  	$this->open("/esurveyit/");
  	$this->click("link=Home");
    $this->waitForPageToLoad("500");
    $this->type("email", "jason@lexxcom.com");
    $this->type("password", "cowcow");
    $this->click("css=input[type=image]");
    $this->waitForPageToLoad("500");
  	
  	$str = explode(' ', "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed erat nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Morbi massa nibh, consequat ut iaculis quis, commodo sit amet justo. Aenean sit amet pretium magna. Fusce at quam eu nulla iaculis commodo. Duis in eros tellus, quis suscipit nisi. Etiam et pharetra dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eget enim ac lacus lobortis lobortis. Vestibulum ac consectetur justo. Phasellus luctus turpis nec justo vestibulum quis elementum enim suscipit. Proin semper ultricies diam, quis sodales metus varius eget. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur id erat nec erat bibendum cursus et ut lacus.");
  	//$a = rand ( 1 , 7 );
  	$cycles = rand ( 50 , 100 );
	for($j = 0; $j<=$cycles; $j++){
	  	$this->open("/esurveyit/survey.php?action=edit&id=1");
	    $this->click("link=My Survey");
	    $this->waitForPageToLoad("1000");
	    $this->click("//div[@id='content']/div/div/div/div/div/div/div/div/div/div/div/div[2]/a/img");
	    $this->waitForPageToLoad("2000");
	    //asnwer Question 1 Array
	    $a = rand ( 0 , 3 );
	    $this->click("document.forms[0].elements['q1[1]'][$a]");
	    $a = rand ( 0 , 3 );
	    $this->click("document.forms[0].elements['q1[2]'][$a]");
	    $a = rand ( 0 , 3 );
	    $this->click("document.forms[0].elements['q1[3]'][$a]");
	    $a = rand ( 0 , 3 );
	    $this->click("document.forms[0].elements['q1[4]'][$a]");
	    $a = rand ( 0 , 3 );
	    $this->click("document.forms[0].elements['q1[5]'][$a]");
		$a = rand ( 1 , 4 );
		unset($teststr);
		for($i = 0; $i <= $a; $i++){
			$b = rand ( 1 , 119 );
			$teststr[] = $str[$b];
		}
		$test = implode(' ', $teststr);
		$this->type("q2", $test);
		$a = rand ( 1 , 15 );
		unset($teststr);
		for($i = 0; $i <= $a; $i++){
			$b = rand ( 1 , 119);
			$teststr[] = $str[$b];
		}
		$test = implode(' ', $teststr);
    	$this->type("q3", $test);
	    $alist = array('Select 1','Select 2','Select 3','Select 4', 'Select 5', 'Select 5');
	    $a = rand ( 0 , 5 );
	    $this->select("q4", "label=".$alist[$a]);
	    //asnwer Question 3 Radio
	    $a = rand ( 0 , 4 );
	    $this->click("document.forms[0].elements['q5[]'][$a]");
	    //asnwer Question 4 Checkbox 
	    $a = rand ( 1 , 7 );
	    for($i = 0; $i <= $a; $i++){
	    	$a2 = rand ( 0 , 4 );
	    	$this->click("document.forms[0].elements['q6[]'][$a2]");
	    }
	    $this->click("submit-form");
	    $this->waitForPageToLoad("1000");
	}
  }
  
	/**
	 * @group POP
	 * @group POP2
	 */
  public function testWebSurvey2Details()
  {	
  	$this->open("/esurveyit/");
  	$this->click("link=Home");
    $this->waitForPageToLoad("500");
    $this->type("email", "jason@lexxcom.com.au");
    $this->type("password", "moomoo");
    $this->click("css=input[type=image]");
    $this->waitForPageToLoad("500");
    
  	$cycles = rand ( 50 , 100 );
	for($j = 0; $j<=$cycles; $j++){
	  	$this->open("/esurveyit/survey.php?action=edit&id=1");
	    $this->click("link=My Survey");
	    $this->waitForPageToLoad("1000");
	    $this->click("//div[@id='content']/div/div/div/div/div/div/div/div/div/div/div/div[2]/a/img");
	    $this->waitForPageToLoad("2000");
	    //asnwer Question 1 Array
	    $a = rand ( 0 , 4 );
	    $this->click("document.forms[0].elements['q1[1]'][$a]");
	    $a = rand ( 0 , 4 );
	    $this->click("document.forms[0].elements['q1[2]'][$a]");
	    $a = rand ( 0 , 4 );
	    $this->click("document.forms[0].elements['q1[3]'][$a]");
	    $a = rand ( 0 , 4 );
	    $this->click("document.forms[0].elements['q1[4]'][$a]");
	    $a = rand ( 0 , 4 );
	    $this->click("document.forms[0].elements['q1[5]'][$a]");
		//asnwer Question 2 Select
	    $alist = array('White','Patchy','Black','Spotted', 'Green');
	    $a = rand ( 0 , 4 );
	    $this->select("q2", "label=".$alist[$a]);
	    //asnwer Question 3 Radio
	    $a = rand ( 0 , 3 );
	    $this->click("document.forms[0].elements['q3[]'][$a]");
	    //asnwer Question 4 Checkbox 
	    $this->click("q4[]");
	    $a = rand ( 1 , 7 );
	    for($i = 0; $i <= $a; $i++){
	    	$a2 = rand ( 0 , 6 );
	    	$this->click("document.forms[0].elements['q4[]'][$a2]");
	    }
	    $this->click("submit-form");
	    $this->waitForPageToLoad("1000");
	}
  }
}

