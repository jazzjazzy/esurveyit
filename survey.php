<?php
require_once 'config/config.php';
require_once DIR_ROOT.'/classes/survey.class.php';


$action = (!isset($_REQUEST['action']))? '' : $_REQUEST['action'];
$year = ((!isset($_REQUEST['year']))? DEFAULT_YEAR : $_REQUEST['year']);

$survey = new survey($year);

switch($action){
	case 'edit' : 
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$survey->editSurveyDetails($id);
	break;
	
	case 'create' : 
			getSubMenu('create');
			$survey->createSurveyDetails();
	break;

	case 'show' :
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$survey->showSurveyDetails($id);
	break;
	case 'show-print' :
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$survey->showSurveyPrintDetails($id);
	break;
	case 'update' : 
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$survey->updateSurveyDetails($id);
	break;
	case 'save' : 
			getSubMenu('create');
			$survey->saveSurveyDetails();
	break;
	case 'xml' :
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			//header('Content-type: text/xml');
			//header('Content-Disposition: attachment; filename="downloaded.xml"');
			//echo $survey->showXMLDetails($id);
			$xml = simplexml_load_string($survey->showXMLDetails($id));
			pp($xml->page);
			
	break;
	default :
			getSubMenu('list');
			echo $survey->getSurveyList();
	break;
}


function getSubMenu($action){
	global $survey;
	/*if($survey->admin->checkAdminLevel(1)){
				$create_css = ($action == 'create')? 'tab-button-select' : 'tab-button'; 
				$staff->template->assign('Menu', '<!--<a href="staff.php?action=show-print" class="tab-button">print Bulk Profile</a>
							<a href="staff.php?action=show-print&id=1" class="tab-button">Fin Bulk Profile</a> 
							<a href="staff.php?action=show-print&id=2" class="tab-button">FAA Bulk Profile</a>
							<a href="staff.php?action=show-print&id=3" class="tab-button">MAAIS Bulk Profile</a>-->
							<a href="staff.php?action=create" class="'.$create_css.'">Add Staff</a>
							<a href="external.php" class="tab-button">List Externals</a>
							<br class="clear"/><div id="tab-button-divider">');
	}*/
}