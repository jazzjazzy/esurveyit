<?php
require_once '../config/config.php';
require_once DIR_ROOT.'/classes/survey.class.php';

define('FULL_LIKE', true);// do like as %search_term% not search_term%

$action = $_REQUEST['action'];
$orderby = (isset($_REQUEST['orderby']))? $_REQUEST['orderby'] : '';
$dir =(isset($_REQUEST['dir']))? $_REQUEST['dir'] : '';
$year =(!empty($_REQUEST['year']))? $_REQUEST['year'] : DEFAULT_YEAR;

$survey = new survey($year);


$filter=$survey->table->buildWhereArrayFromRequest();

switch($action){
	case 'list':$page="staff.php?action=show" ;
				$filter=$survey->table->buildWhereArrayFromRequest();
				$survey->getSurveyList('AJAX', $orderby, $dir, $filter); break;
	
	case 'append' : $question = (isset($_REQUEST['question']))? $_REQUEST['question'] : '';	
					$type = (isset($_REQUEST['type']))? $_REQUEST['type'] : '';	
					$id = (isset($_REQUEST['id']))? $_REQUEST['id'] : '';	
					echo $survey->appendNewQuestion($id, $type, $question);
					break;	
	default : echo ("<tr class=\"row\"><td colspan=\"10\">No action given</td></tr>");
}