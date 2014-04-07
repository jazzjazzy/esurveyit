<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";

require_once('package/config.php');

require_once 'PHPUnit/Framework.php';

//require_once('database.class.php');
//require_once('template.class.php');
 
class clientDatabaseTest extends PHPUnit_Framework_TestCase
{

    public function testcreateClientDatabase()
    {	
    	//$_SESSION['user']['client_id'] = '10000000';

		try {
			$conn = new PDO(strtolower(DB_TYPE).":host=".DB_HOST.";port=".DB_PORT, 'root', 'password');
		} catch (PDOException $e) {
			echo "message 1 : ".$e->getMessage();
		}
		
		$conn->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = 'DROP DATABASE IF EXISTS esurveyit;';
		
		try{
			$stmt = $conn->prepare($sql);
			$stmt->execute();
		}catch(CustomException $e){
			echo  "message 2 : ".$e->getMessage();
		}
		
    	$conn->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = 'CREATE DATABASE esurveyit;';
		
		try{
			$stmt = $conn->prepare($sql);
			$stmt->execute();
		}catch(CustomException $e){
			echo  "message 3 : ".$e->getMessage();
		}
		sleep(2);
		try {
			$conn = new PDO(strtolower(DB_TYPE).":dbname=esurveyit;host=".DB_HOST.";port=".DB_PORT, 'root', 'password');
		} catch (PDOException $e) {
			echo "message 4 : ".$e->getMessage();
		}
		
		$s = file('../Document/database/create.sql');
		$script = implode("\n", $s );
		$scriptArry = explode(';', $script );

		foreach($scriptArry AS $sql){
			try{
				$stmt = $conn->prepare($sql);
				$stmt->execute();
			}catch(CustomException $e){
				echo  "message 5 : ".$e->getMessage();
			}
		}
		
		
	/*	$sql = "INSERT into users (user_id, username, password, name, surname, email, create_date) values('1', 'ADMIN', 'PASSWORD', 'Jennifer', 'Erator', 'jason@lexxcom.com', NOW())";	
		
		try{
			$stmt = $conn->prepare($sql);
			$stmt->execute();
		}catch(CustomException $e){
			echo  $e->getMessage();
		}
*/

	}
}