<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');

$pathSet = ':';
if(strstr(@$_SERVER[SERVER_SIGNATURE], "Win32") !== FALSE){
	$pathSet = ';';
}

if($_SERVER['HTTP_HOST'] == 'dev'){ //INTERNAL HOME STAGEING
	define('SITE_ROOT','http://'.$_SERVER['HTTP_HOST'].'/esurveyit/');
	define('DIR_ROOT',$_SERVER['DOCUMENT_ROOT'].'/esurveyit/');
	define('TEMPLATE_ROOT',$_SERVER['DOCUMENT_ROOT'].'/esurveyit/templates');
	define('DB_USER','root');
	define('DB_PASS','password');
	define('DB_HOST','localhost');
	define('DB_DBASE','esurveyit');
	define('DB_TYPE','mysql');
	define('DB_PORT','3306');
	define('DEBUG', true);
	define('ERROR_LEVEL', 'dev');

}else{
	define('SITE_ROOT','http://'.$_SERVER['HTTP_HOST'].'/');
	define('DIR_ROOT',$_SERVER['DOCUMENT_ROOT']."/");
	define('TEMPLATE_ROOT',$_SERVER['DOCUMENT_ROOT'].'/templates');
	define('DB_USER','jazzjazzy');
	define('DB_PASS','');
	define('DB_HOST','localhost');
	define('DB_DBASE','esurveyit');
	define('DB_TYPE','mysql');
	define('DB_PORT','3306');
	define('DEBUG', false);
	define('ERROR_LEVEL', 'dev');
}


define('CLASS_ROOT', DIR_ROOT.'classes');
define('ASSETS_ROOT', DIR_ROOT.'assets');
define('CONFIG_ROOT', DIR_ROOT.'config');

set_include_path(get_include_path().PATH_SEPARATOR. DIR_ROOT.'assets/PEAR/');
set_include_path(get_include_path().PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/classes/base/');

require_once($_SERVER['DOCUMENT_ROOT'].'/config/standard.inc.php');
require_once('constants.php');

if(isset($_REQUEST['js'])){
	echo "const SITE_ROOT = '".SITE_ROOT."';";
	echo "const DIR_ROOT = '".DIR_ROOT."';";
	echo "const TEMPLATE_ROOT = '".TEMPLATE_ROOT."';";
	echo "const DEBUG = '".DEBUG."';";
}

?>
