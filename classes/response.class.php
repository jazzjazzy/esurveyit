<?php
/**
 * Survey Class 
 * <pre>
 * This class is based on the table survey
 *
 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
 * </pre> 
 * 
 * @author Jennifer Erator <jason@lexxcom.com>
 * @version 0.1 alpha of the Framework generator
 * @package PeopleScope
 */

require_once('question.class.php');

class response extends question{
	
	/**
	 * Connect to PDO object through database class
	 * @var Object
	 */
	private $db_connect;
	
	/**
	 * Database class object 
	 * @var Object
	 */
	private $db;
	
	/**
	 * Table class object  
	 * @var Object
	 */
	public $table;
	
	/**
	 * Template class object 
	 * @var Object
	 */
	public $template;

	
	/**
	 * Array use to store any error found during Validation function 
	 * @see Validation()
	 * @var Array
	 */
	private $validation_error = array();
	
	private $sid;
	
	public function __construct($sid){
		$this->db = new db();

		try {
			$this->db_connect = $this->db->dbh;
		} catch (CustomException $e) {
			$e->logError();
		}
		$this->sid = $sid;
		$this->table = new table();
		$this->template = new template();
		$this->template->assign('LOGIN', account::loggedIn());
	
	}
	
	public function save($rid, $sid, $qid, $value, $type){
			
		require_once(DIR_ROOT.'question-plugin/'.$type.'/'.$type.'.class.php');
			
		$class = $type."Class";
		$question = new $class();
		$list = $question->response($rid, $sid, $qid, $value);

	}
	
	public function getResponseId(){
		$sql = "INSERT INTO survey_response (survey_id)VALUES ( ".$this->sid.")";
							
		try{
			$rid = $this->db->insert($sql); 
		}catch(CustomException $e){
			throw new CustomException($e->queryError($sql));
		}
		
		return $rid;
	}
	
	public function getResponseList(){
	
		$sql = "SELECT 
					question_id,
					question_type
				FROM 
					survey 
					LEFT JOIN question ON survey.survey_id = question.survey_id
				WHERE survey.survey_id = ". $this->sid ." 
				AND (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)
					ORDER BY question.sort" ;

		
			$stmt = $this->db_connect->prepare($sql);
			$stmt->execute();
			
			try{
				 $result = $this->db->select($sql);
			}catch(CustomException $e){
				 echo $e->queryError($sql);
			}
			
			return $result;
	}
}