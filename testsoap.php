<?php
if(file_exists('config/config.php')){
	require_once('config/config.php');
}
require_once('classes/soap.class.php');


$soap = new SoapSurvey();
header("Content-Type: 'xml/text'");
//echo $soap->getQuestionById(1, 1);
echo $soap->getQuestionById(1, 2, true);

?>