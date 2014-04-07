<?php
require_once 'config/config.php';
require_once DIR_ROOT.'/classes/index.class.php';

/*$admin->isLoggedIn();*/

$action = (!isset($_REQUEST['action']))? '' : $_REQUEST['action'];
$year = ((!isset($_REQUEST['year']))? DEFAULT_YEAR : $_REQUEST['year']);

$index = new index();

switch($action){	
	case 'login' : 
			$email = (!isset($_REQUEST['email']))? NULL : $_REQUEST['email'];
			$password = (!isset($_REQUEST['password']))? NULL : $_REQUEST['password'];
			$index->loginIndexDetails($email, $password, $_SERVER['HTTP_REFERER']);
			exit();
	break;
	case 'logout' : 
			$index->logoutIndexDetails($_SERVER['HTTP_REFERER']);
			exit();
	break;
	default :
			//getSubMenu('list');
			$index->showIndexDetails();
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