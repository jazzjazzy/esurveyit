<?php

require_once('config/config.php');
require_once('classes/survey.class.php');

$survey = new survey();

$val = array();

$val = json_decode($_REQUEST['val']);

$page = $val->survey->page;
unset($val->survey->page);

if($page->survey_id >= 1){
	$survey->updateSurveyDetails($page->survey_id, $page);
}else{
	$page->survey_id = $survey->saveSurveyDetails($_SESSION['user']['account_id'], $page);
}

$sort = 1;

$survey->markDeletedQuestion($page->survey_id);

foreach($val->survey AS $key=>$val ){
	
	$type = $val->type;
	
	require_once 'question-plugin/'.$type.'/'.$type.'.class.php';
	
	//Function Name to get class for a type 
	$class = $type.'Class';
	$obj = new $class();
	
	try {
		$obj->save($page->survey_id, $key, $val, $sort);
	}
	catch(CustomException $e){
		echo $e->queryError($sql);
	}
	$sort++;

}

$survey->removeDeletedQuestion($page->survey_id);

?>


