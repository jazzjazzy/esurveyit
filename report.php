<?php 

require_once("config/config.php");
require_once("classes/report.class.php");

$action = (!isset($_REQUEST['action']))? '' : $_REQUEST['action'];
$id = ((!isset($_REQUEST['id']))? '' : $_REQUEST['id']);

$report = new report($id);

switch($action){
	case 'show' :
			$report->showReportDetails();
	break;
	default :
			getSubMenu('list');
			echo $survey->getSurveyList();
	break;
}

?>

