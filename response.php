<?php 

require_once("config/config.php");
require_once("classes/response.class.php");

$survey_id = (isset($_REQUEST['survey_id'])?$_REQUEST['survey_id']: '');

$response = new response($survey_id);

$list = $response->getResponseList();

$rid = $response->getResponseId();

foreach($list AS $value){
	if(isset($_REQUEST["q".$value['question_id']])){
		$response->save($rid, $survey_id, $value['question_id'], $_REQUEST["q".$value['question_id']], $value['question_type']);
	}
}

header("Location: survey.php");
?>
