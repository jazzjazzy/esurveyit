<?php
if(file_exists('../config/config.php')){
	require_once('../config/config.php');
}
require_once('survey.class.php');


class SoapSurvey{
	
	function __construct(){
		$this->db = new db();

		try {
			$this->db_connect = $this->db->dbh;
		} catch (CustomException $e) {
			$e->logError();
		}
		
		$this->survey = new survey();
		$this->question = new question();
		
	}
	
	function getSurveyListbyUserId(){
		
	}
	
	function getUserLogin($username, $password){
		//if(!$page = $this->survey->showXMLDetails($id,true)){
			throw new SoapFault("1", "<ERROR>Not logged in</ERROR>");
		//}
		//return $page;
	}
	
	function getSurveybyId($id){
		if(!$page = $this->survey->showXMLDetails($id,true)){
			throw new SoapFault("1", "<ERROR>Not matching position advertisment</ERROR>");
		}
		return $page;
	}
	
	function getSurveyPageDetails($id){
		if(!$survey = $this->survey->showXMLDetails($id)){
			throw new SoapFault("1", "<ERROR>Not matching position advertisment</ERROR>");
		}
		return $survey;
	} 
	
	function getQuestionById($id, $qid){
		if(!$question = $this->survey->getQuestionById($id, $qid, true)){
			throw new SoapFault("1", "<ERROR>Not matching Question for this application!</ERROR>");
		}
		return $question;
	}
	
	function getQuestionHtml($id, $qid){
		if(!$question = $this->survey->getQuestionById($id, $qid)){
			throw new SoapFault("1", "<ERROR>Not matching Question for this application!</ERROR>");
		}
		return $question;
	}
}

$soap = new SoapServer(DIR_ROOT.'esurveyit.wsdl', array('cache_wsdl'=>WSDL_CACHE_NONE));

$soap->setClass('SoapSurvey');
$soap->handle(); 
?>