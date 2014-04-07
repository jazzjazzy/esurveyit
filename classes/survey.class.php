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

class survey extends question{
	
	/**
	 * Connect to PDO object through database class
	 * @var Object
	 */
	public $db_connect;
	
	/**
	 * Database class object 
	 * @var Object
	 */
	public $db;
	
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
	 * Array of field used in the database if not in this list is dropped from insert or update
	 * @var Array
	 */
	private $fields =array('survey_id', 'account_id', 'survey_title', 'survey_description', 'survey_type', 'start_date', 'end_date', 'create_date', 'modify_date', 'delete_date');
	
	/**
	 * Array of feilds require information when validating 
	 * @var Array|null
	 */
	private $fields_required = NULL;
	
	/**
	 * Array of feilds and there types that are check when validating 
	 * @var Array|null
	 */
	private $fields_validation_type = array ('survey_id'=>'INT', 'account_id'=>'INT', 'survey_title'=>'TEXT','survey_description'=>'TEXTAREA', 'survey_type'=>'TEXT', 'start_date'=>'TEXT', 'end_date'=>'TEXT', 'create_date'=>'TEXT', 'modify_date'=>'TEXT', 'delete_date'=>'TEXT');
	
	/**
	 * Array use to store any error found during Validation function 
	 * @see Validation()
	 * @var Array
	 */
	private $validation_error = array();
	
	/**
	 * Contructor for this method 
	 * 
	 * <pre>
	 * The constructor will setup the required object for this class 
	 * will initiate the database class, the table class and the template 
	 * for this class to use
	 * 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * 
	 * @see db::
	 * @see table
	 * @see template
	 */
	public function __construct(){
		$this->db = new db();

		try {
			$this->db_connect = $this->db->dbh;
		} catch (CustomException $e) {
			$e->logError();
		}
		
		$this->table = new table();
		$this->template = new template();
		$this->template->assign('LOGIN', account::loggedIn());

	}
	
	/**
	 * Show will pull a list from the corresponding Survey survey
	 * 
	 * <pre>
	 * This Method will produce a list of all the element corresponding to the result of Survey
	 * 
	 * I will only pull rows that are not considered delete 
	 * eg. the delete_date field is not "0000-00-00 00:00:00" or set to NULL
	 * 
	 * The parameter $filter expects an array with the key being the field to look for and the
	 * value being the the information to filter on
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * 
	 * @param String $orderby Which single field is used to oder the output
	 * @param String $direction Which direction os required for the orderby output  
	 * @param Array $filter A array of fields to filter, key=$val set (eg array('tile='=>'this title'))  
	 */
	Private function lists($orderby=NULL, $direction='ASC', $filter=NULL){
		
		$sql = "SELECT survey_id,
				account_id,
				survey_title,
				survey_description,
				survey_type,
				CASE 
					WHEN 
						NOW() BETWEEN start_date AND end_date THEN TRUE
					WHEN 
						NOW() > start_date AND end_date ='00-00-0000 00:00:00' THEN TRUE 
					WHEN 
						NOW() < end_date AND start_date ='00-00-0000 00:00:00' THEN TRUE 
					ELSE 
						FALSE
					END 
					AS _isvalid, 
				DATE_FORMAT(start_date,'%D %b %Y') AS start_date,
				DATE_FORMAT(end_date,'%D %b %Y') AS end_date,
				create_date,
				modify_date,
				(SELECT COUNT(*) FROM question WHERE survey_id = survey.survey_id) AS _Question_count,
				(SELECT COUNT(*) FROM survey_response WHERE survey_id = survey.survey_id) AS _Response_count,
				delete_date FROM survey WHERE survey.account_id = ".$_SESSION['user']['account_id']." AND (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)";
						
		if(is_array($filter)){
		  	foreach($filter AS $key=>$value){
		  		if ($value != 'NULL'  && !empty($value)){
		  			$sql .=  " AND ". $value; 
		  		}
		  	}
		}
		  
		if($orderby){
		  	$sql .= " ORDER BY ". $orderby." ".$direction;
		}
		 
		try{
			 $result = $this->db->select($sql);
		}catch(CustomException $e){
			 echo $e->queryError($sql);
		}
		  
		return $result;
	}
	
	/**
	 * This method will take an array and insert it in the database
	 * 
	 * <pre>
	 * This method will insert the formated information into a database, the format for the array 
	 * should be an associated array being the first key should be the table inserting with the keys 
	 * for child array the fields that are being inserted too and the values to insert
	 * 
	 * Array
	 *(
	 *	[users] => Array
	 *		(
	 *			[name] => Dave
	 *			[surname] => Smith
	 *			[email] => dave@dave.com
	 *		)	
	 *	[staff] => Array
	 *		(
	 *			[staff_id] => 1245
	 *			[office_number] => 22
	 *			[drown_code] => bee223
	 *		)
	 * )
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * 
	 *</pre>
	 *
	 * @param Array $source
	 * 
	 * @return Integer Return last inserted primary id  
	 */
	Private function create($source){
			try{
				$this->db_connect->beginTransaction();
				
				foreach($source['survey'] AS $key=>$val){
					$field[] = $key;
					$value[] = ":".$key;
				}

				$sql = "INSERT INTO survey (".implode(', ',$field).") VALUES (".implode(', ',$value).");";
				
				foreach($source['survey'] AS $key=>$val){
					$exec[":".$key] = $val;
				}
				
				try{
					$pid = $this->db->insert($sql, $exec); 
				}catch(CustomException $e){
					throw new CustomException($e->queryError($sql));
				}
				$this->db_connect->commit();
			}

			catch (CustomException $e) {
				$e->queryError($sql);
				$this->db_connect->rollBack();
				return false;
			}

			return $pid;
	}
	
	
	/**
	 * This method will return information as row
	 * 
	 * <pre>
	 * This method is you to get a single row of information from the database 
	 * based ith the primary id and return it as an array 
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 *
	 * @param Integer $id The primary id of the row to show 
	 */
	Private function read($id){
	
		$sql = "SELECT 
					survey.survey_id,
					question_id,
					question_type,
					account_id,
					survey_title,
					survey_description,
					mainContent,
					title_color,
					details_color,
					question_block,
					question_label_bg,
					question_label_color,
					details_font,
					question_label_font,
					question_block_font,
					survey_type,
					start_date,
					end_date,
					network_type,
					network_value,
					create_date,
					modify_date,
					delete_date 
				FROM 
					survey 
					LEFT JOIN question ON survey.survey_id = question.survey_id
				WHERE survey.survey_id = ". $id ." 
				AND (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)
					ORDER BY question.sort" ;

		
			$stmt = $this->db_connect->prepare($sql);
			$stmt->execute();
			
			try{
				 $result = $this->db->select($sql);
			}catch(CustomException $e){
				 echo $e->queryError($sql);
			}
			
			$ret = array();
			foreach($result AS $values){
				$ret['survey_id'] = $values['survey_id'];
				$ret['account_id'] = $values['account_id'];
				$ret['survey_title'] = $values['survey_title'];
				$ret['survey_description'] = $values['survey_description'];
				$ret['mainContent'] = $values['mainContent'];
				$ret['title_color'] = $values['title_color'];
				$ret['details_color'] = $values['details_color'];
				$ret['question_block'] = $values['question_block'];
				$ret['question_label_bg'] = $values['question_label_bg'];
				$ret['question_label_color'] = $values['question_label_color'];
				$ret['details_font'] = $values['details_font'];
				$ret['question_label_font'] = $values['question_label_font'];
				$ret['question_block_font'] = $values['question_block_font'];
				$ret['survey_type'] = $values['survey_type'];
				$ret['start_date'] = $values['start_date'];
				$ret['end_date'] = $values['end_date'];
				$ret['network_type'] = $values['network_type'];
				$ret['network_value'] = $values['network_value'];
				$ret['create_date'] = $values['create_date'];
				$ret['modify_date'] = $values['modify_date'];
				$ret['delete_date'] = $values['delete_date'];
				$ret['question'][$values['question_id']] = $values['question_type'];
			}

			return $ret;
	}
	
	/**
	 * This method will take an array and update a row in the database
	 * 
	 * <pre>
	 * This method will update the formated information into the database, the format for the array 
	 * should be an associated array being the first key should be the table to be updated with the keys 
	 * for child array the fields that are being updated too and the values to be updated
	 * 
	 * Array
	 *(
	 *	[users] => Array
	 *		(
	 *			[name] => Dave
	 *			[surname] => Smith
	 *			[email] => dave@dave.com
	 *		)	
	 *	[staff] => Array
	 *		(
	 *			[staff_id] => 1245
	 *			[office_number] => 22
	 *			[drown_code] => bee223
	 *		)
	 * )
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * 
	 *</pre>
	 *
	 * @param Array $source
	 * 
	 * @return Integer Return last inserted primary id  
	 */
	Private function update($source, $id){
			try{
				$this->db_connect->beginTransaction();
				
				foreach($source['survey'] AS $key=>$val){
					$field[] = $key." = :".$key;
				}

				$sql = "UPDATE survey SET ".implode(', ',$field)." WHERE survey_id =". $id;
				
				foreach($source['survey'] AS $key=>$val){
					$exec[":".$key] = $val;
				}
				
				try{
						$pid = $this->db->update($sql, $exec); 
				}catch(CustomException $e){
						throw new CustomException($e->queryError($sql));
				}
				$this->db_connect->commit();	
			}
			
			catch (CustomException $e) {
				$e->queryError($sql);
				$this->db_connect->rollBack();
				return false;
			}
			
			//header('Location:survey.php?action=show&id='.$id);
			
	}
	
	/**
	 * This method will update a row and make the recored as deleted
	 * 
	 * <pre>
	 * This method will take the id and set the delete_date field to 
	 * the current datetime, which will marking it as deleted
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 *
	 * @param Integer $id The primary id of the row to show
	 * @return Boolean success or failed
	 */
	 
	Private function remove($id){
			if(empty($id)){
				return false;
			}
			
			$sql = "UPDATE survey SET delete_date=NOW() WHERE survey_id =". $id;

			try{
				$result = $this->db->update($sql);
			}catch(CustomException $e){
				echo $e->queryError($sql);
			}
			return true;
	}
	
	
	/******************* END CRUD METHOD*************************/
	
	
	/**
	 * Show list of information corresponding the to this class 
	 * 
	 * <pre>This Method will produce a list of all the element corresponding to the result of Survey
	 * using the base/table.class.php file, which will format the list into a filtable table that
	 * uses ajax class to change the content on filtering
	 * 
	 * There are to response type for this table for the parameter $type 
	 * 
	 * 	TABLE = Will return the content in a table with a filter row and a heading row
	 * 	AJAX = Will return just the content after evaluating the filter or heading infomation
	 * 
	 * The parameter $filter expects an array with the key being the field to look for and the
	 * value being the the information to filter on
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * 
	 * @param String $type Option of type of response for the output of the list  
	 * @param String $orderby Which single field is used to oder the output
	 * @param String $direction Which direction os required for the orderby output  
	 * @param Array $filter A array of fields to filter, key=TEXT set (eg array('tile='=>'this title'))  
	 */
	
	public function getSurveyList($type='TABLE',$orderby=NULL, $direction='ASC', $filter=NULL){
		if(account::isLoggedIn()){
			$result = $this->lists($orderby, $direction, $filter);
			
			$this->template->page('survey-listing.tpl.html');
			
			$html_active = '<h1> Current Active Survey</h1>';
			$html_inactive = '<h1> Current Inactive Survey</h1>';
			foreach($result AS $value){
				$start_date = (empty($value['start_date']))?"Now":$value['start_date'];
				$end_date = (empty($value['end_date']))?"Never":$value['end_date'];
				$html = '
								<br clear="all" />
								<div>
									<div style="width:700px;float:left; padding-bottom:5px;font-size:12px"><h3 title="'.$value['survey_description'].'">'.$value['survey_title'].'</h3></div>
									<div style="width:20px;float:left;padding:0 10px 0 10px"><a href="'.createLink("survey",'show',$value['survey_id']).'"><img src="images/Preview-icon.png" /></a></div>
									<div style="width:20px;float:left;padding:0 10px 0 10px"><a href="'.createLink("survey",'edit',$value['survey_id']).'"><img src="images/edit-icon.png" /></a></div>  
									<div style="width:20px;float:left;padding:0 10px 0 10px"><a href="'.createLink("report",'show',$value['survey_id']).'"><img src="images/graph-icon.png" /></a></div>
								 </div>
								 <br clear="all" />
								 <div style="border-bottom:1px solid #000;margin-bottom:7px">
								 	<div style="width:25%;float:left"><strong>Completed Surveys</strong> : '.$value['_Response_count'].'</div>
									<div style="width:25%;float:left"><strong>Number of Question</strong> : '.$value['_Question_count'].'</div>
									<div style="width:25%;float:left"><strong>Starts On</strong> : '.$start_date.'</div>
									<div style="width:25%;float:left"><strong>ends On</strong> : '.$end_date.'</div>
								 </div>
								 <br clear="all" />' ;
				if($value['_isvalid']){
					$html_active .= $html;
				}else{
					$html_inactive .= $html;
				}
				
			}
			
			$this->template->assign('active', $html_active);
			$this->template->assign('inactive', $html_inactive);         
			echo $this->template->fetch();	
		}else{
			//TODO: Change to non-login page
			$this->template->page('logout.survey.tpl.html');
			echo $this->template->fetch();	
		}
	}
	
	
	/**
	 * Show details of a single Survey from the survey
	 * 
	 * <pre>This method will return a template page of the information requested 
	 * the method use the template class to format the information ready to display the 
	 * the user 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * @param Integer $id the primary id of the row to show 
	 */
	Public function showSurveyDetails($sid){
		$fieldMember = $this->read($sid);
		
		$this->template->page('survey-public.tpl.html');
		//$this->template->assign('mainContent', 'style="background-color:'.rgbTohex($fieldMember['mainContent']).'"');
		$this->template->assign('script', '<STYLE>.mainContent{
			background-color:'.rgbTohex($fieldMember['mainContent']).';
		}
		
		.title{
			color:'.rgbTohex($fieldMember['title_color']).';
		}
		
		.details{
			color:'.rgbTohex($fieldMember['details_color']).';
			font-size:'.$fieldMember['details_font'].';
		}
		
		.question-label{
			color:'.rgbTohex($fieldMember['question_label_color']).';
			backgroound-color:'.rgbTohex($fieldMember['question_label_bg']).';
			font-size:'.$fieldMember['question_label_font'].';
			line-height: 105%;
		}
		
		.question-block, .question-block td, .question-block th{
			color:'.rgbTohex($fieldMember['question_block']).';
			font-size:'.$fieldMember['question_block_font'].';
			line-height: 105%;
		}
		
		</STYLE>');
		$this->templateSurveyLayout($fieldMember);
		$list = $this->getQuestionDetails($sid, $fieldMember['question']);
		$this->template->assign('settings', $this->getSystemSetting($fieldMember));
		$this->template->assign('question', $list);
		
		$this->template->assign('FUNCTION', "<input type=\"submit\" value=\"Please Submit my Survey\" id=\"submit-form\" />");
		
		
		echo $this->template->fetch();	
	}
		
	/**
	 * Show Survey as an XML details page 
	 * 
	 * @param Integer $id the primary id of the row to show
	 * @param Boolean $onlypage if true only show he page details no question, if false show page details and questions  
	 */
	Public function showXMLDetails($sid, $onlypage=false){
		$fieldMember = $this->read($sid);
		
		$xml ='<?xml version="1.0" encoding="utf-8"?>'."\n".
			'<content>
			<page>
				<background_color>'.rgbTohex($fieldMember['mainContent']).'</background_color>
				<title_color>'.rgbTohex($fieldMember['title_color']).'</title_color>
				<details_color>'.rgbTohex($fieldMember['details_color']).'</details_color>
				<details_font_size>'.$fieldMember['details_font'].'</details_font_size>
				<question_label_color>'.rgbTohex($fieldMember['question_label_color']).'</question_label_color>
				<question_label_backgroound_color>'.rgbTohex($fieldMember['question_label_bg']).'</question_label_backgroound_color>
				<question_label_font_size>'.$fieldMember['question_label_font'].'</question_label_font_size>
				<question_label_line_height>105%</question_label_line_height> 
				<question_block_color>'.rgbTohex($fieldMember['question_block']).'</question_block_color>
				<question_block_font_size>'.$fieldMember['question_block_font'].'</question_block_font_size>
				<question_block_line_height>105%</question_block_line_height>
				<survey_title>'.$fieldMember['survey_title'].'</survey_title>
				<survey_discription>'.$fieldMember['survey_description'].'</survey_discription>
			</page>'."\n";
		
		if ($onlypage) {
			$xml .= $this->getQuestionDetails($sid, $fieldMember['question'],true);
		}
		
		$xml .= "\n".'</content>';
		return $xml; 
	}


	
	/**
	 * Show the details ready to edit of a single Survey from the survey 
	 * 
	 * <pre>
	 * This method is used to display and editable page to the use, so that they 
	 * maybe able to edit any of the fields releated to the row in question. 
	 * The method uses the template class to format the information ready to display the 
	 * the user 
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>     
	 * </pre>
	 * @param Integer $sid The primary id of the row to show 
	 */
	Public function editSurveyDetails($sid){
		
		$fieldMember = $this->read($sid);
		
		$script = '<script type="text/javascript" src="js/survey.js"></script>
		<script type="text/javascript" src="js/farbtastic/farbtastic.js"></script>
		<script type="text/javascript" src="js/parser.js"></script>
		<link href="js/boxy/stylesheets/boxy.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/boxy/javascripts/jquery.boxy.js"></script>
		
		<STYLE>.mainContent{
			background-color:'.rgbTohex($fieldMember['mainContent']).';
		}
		
		.title{
			color:'.rgbTohex($fieldMember['title_color']).';
		}
		
		.details{
			color:'.rgbTohex($fieldMember['details_color']).';
			font-size:'.$fieldMember['details_font'].';
		}
		
		.question-label{
			color:'.rgbTohex($fieldMember['question_label_color']).';
			backgroound-color:'.rgbTohex($fieldMember['question_label_bg']).';
			font-size:'.$fieldMember['question_label_font'].';
			line-height: 105%;
		}
		
		.question-block, .question-block td, .question-block th{
			color:'.rgbTohex($fieldMember['question_block']).';
			font-size:'.$fieldMember['question_block_font'].';
			line-height: 105%;
		}
		
		</STYLE>';
		
		$this->template->page('survey.tpl.html');
		$this->template->assign('script', $script);
		$this->template->assign('survey_id', $fieldMember['survey_id']);
		$this->templateSurveyLayout($fieldMember);
		if(account::isActiveAccount()){
			//$fieldMember['survey_type']='account';
			$fieldMember['isAccount']='';
		}else{
			//$fieldMember['survey_type']='free';
			$fieldMember['isAccount']=' class="NoAccount"';
		}
		$this->template->assign('settings', $this->getSystemSetting($fieldMember));
		$list = $this->getQuestionDetails($sid, $fieldMember['question']);
		$this->template->assign('question', $list);
		
		if(!empty($fieldMember['mainContent'])){
			$this->template->assign('mainContent', 'style="background-color:'.rgbTohex($fieldMember['mainContent']).'"');
		}
		
		/*if(!empty($fieldMember['title_color'])){
			$this->template->assign('title_color', $fieldMember['title_color']);
			$this->template->assign('details_color', $fieldMember['details_color']);
			$this->template->assign('details_font', $fieldMember['details_font']);
		}
		$this->template->assign('question_block', $fieldMember['question_block']);
		
		$this->template->assign('question_label_bg', $fieldMember['question_label_bg']);
		
		$this->template->assign('question_label_color', $fieldMember['question_label_color']);
		
		$this->template->assign('details_font', $fieldMember['details_font']);
		$this->template->assign('question_label_font', $fieldMember['question_label_font']);
		$this->template->assign('question_block_font', $fieldMember['question_block_font']);*/
		//if($this->checkAdminLevel(1)){
			//$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"location.href='survey.php?action=edit&id=".$id."'\">Edit</div>");
			$this->template->assign('FUNCTION', '<div class="button" id="save-survey">Update</div>');
		//}
		
		echo $this->template->fetch();	
	}
	
	/**
	 * update the information in a single Survey from the survey 
	 * 
	 * <pre>
	 * This method is used to take the information from editDepartmentDetails and try to validate it thought the 
	 * validation method and on success will format it ready for input into the datebase through the update method 
	 * 
	 * if the validate fails then the user is show a page that mimics the editDepartmentDetails method and point out 
	 * error in there input 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>    
	 * </pre>
	 * 
	 * @see editDepartmentDetails()
	 * @see Validate()
	 * @see update()
	 * @param Integer $id The primary id of the row to updated 
	 */
	Public function updateSurveyDetails($id, $request = NULL){

		if(!$request){
			$request = $_REQUEST;
		}
		
		//if ($this->Validate($request)){
			
				$table = 'survey';
				
				$startdate = (empty($request->start_date))? '' : formatDateUI($request->start_date);
				$enddate = (empty($request->end_date))? '' : formatDateUI($request->end_date);
				
				$save[$table]['account_id'] = $id;
				$save[$table]['survey_title'] = $request->survey_title;
				$save[$table]['survey_description'] = $request->survey_description;
				$save[$table]['mainContent'] = $request->mainContent;
				$save[$table]['title_color'] = $request->title_color;
				$save[$table]['details_color'] = $request->details_color;
				$save[$table]['question_block'] = $request->question_block;
				$save[$table]['question_label_bg'] = $request->question_label_bg;
				$save[$table]['question_label_color'] = $request->question_label_color;
				$save[$table]['details_font'] = $request->details_font;
				$save[$table]['question_label_font'] = $request->question_label_font;
				$save[$table]['question_block_font'] = $request->question_block_font;
				$save[$table]['survey_type'] = $request->survey_type;
				$save[$table]['start_date'] = $startdate;
				$save[$table]['end_date'] = $enddate;
				$save[$table]['network_type'] = $request->network_type;
				$save[$table]['network_value'] = $request->network_value;
				
				$save[$table]['modify_date'] = date('Y-m-d h:i:s');
				
				$this->update($save, $id );
				//header('Location: survey.php?action=show&id='.$id);
		/*	}else{
				
				$fieldMember = $this->valid_field;
				$error = $this->validation_error;
				
				$name = 'editSurvey';
		
				$this->template->page('survey.tpl.html');
				
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $error[$key])."</spam>");
				}
				
				$this->template->assign('FORM-HEADER', '<form action="survey.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
				$this->templateSurveyLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='survey.php?action=show&id=".$id."'\">Cancel</div>");
				//}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}*/
	}
	
	/**
	 * This method will provide a page to the to add a single row Survey to the survey table
	 * 
	 * <pre>
	 * The method using the template class to format the information ready to display the 
	 * the user, so that they may be able to add any of the fields releated to a row in the database. 
	 * The method uses the template class to format the information ready to display the 
	 * the user
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>   
	 * </pre>  
	 */
	Public function createSurveyDetails(){
		if(account::isLoggedIn()){
			$name = 'createSurvey';
			
			$script = '<script type="text/javascript" src="js/survey.js"></script>
			<script type="text/javascript" src="js/farbtastic/farbtastic.js"></script>
			<script type="text/javascript" src="js/parser.js"></script>
			<link href="js/boxy/stylesheets/boxy.css" rel="stylesheet" type="text/css" />
			<script type="text/javascript" src="js/boxy/javascripts/jquery.boxy.js"></script>
			<script>
				$(document).ready(function(){ 
					new Boxy("#survey-create", {title: "Create New Survey", modal : true});
				})
			</script>
			';
			
			$this->template->page('survey.tpl.html');
			
			if(account::isActiveAccount()==1){
				$fields['survey_type']='account';
				$fields['isAccount']='';
			}else{
				$fields['survey_type']='free';
				$fields['isAccount']=' class="NoAccount"';
			}
			
			$this->template->assign('script', $script);
			$this->template->assign('survey_id', '0');
			$this->template->assign('settings', $this->getSystemSetting($fields));
			$this->template->assign('FORM-HEADER', '<form action="survey.php?action=save" method="POST" name="'.$name.'">');
			
			$this->templateSurveyLayout('');
			
			//$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Save</div><div class=\"button\" onclick=\"location.href='survey.php?action=list'\">Cancel</div>");
			$this->template->assign('FUNCTION', '<div class="button" id="save-survey">Save</div>');
		
			$this->template->display();
		}else{
			//TODO: Change to non-login page
			$this->template->page('logout.create.tpl.html');
			echo $this->template->fetch();	
		}
	} 
	
	/**
	 * save the information in a single Survey to the survey table 
	 * 
	 * <pre>
	 * This method is used to take the information from createDepartmentDetails and try to validate it thought the 
	 * validation method and on success will format it ready for inserted into the datebase through the insert method 
	 * 
	 * if the validate fails then the user is show a page that mimics the createDepartmentDetails method and point out 
	 * error in there input 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>   
	 * </pre>
	 * 
	 * @see createDepartmentDetails()
	 * @see Validate()
	 * @see update()
	 * @param Integer $id The primary id of the row to updated 
	 */
	Public function saveSurveyDetails($id, $request = NULL){

		//if ($this->Validate($_REQUEST)){
				
		if(!$request){
			$request = $_REQUEST;
		}
				$table = 'survey';
				
				$startdate = (empty($request->start_date))? '' : formatDateUI($request->start_date);
				$enddate = (empty($request->end_date))? '' : formatDateUI($request->end_date);
				
				$save[$table]['account_id'] = $id;
				$save[$table]['survey_title'] = $request->survey_title;
				$save[$table]['survey_description'] = $request->survey_description;
				$save[$table]['mainContent'] = $request->mainContent;
				$save[$table]['title_color'] = $request->title_color;
				$save[$table]['details_color'] = $request->details_color;
				$save[$table]['question_block'] = $request->question_block;
				$save[$table]['question_label_bg'] = $request->question_label_bg;
				$save[$table]['question_label_color'] = $request->question_label_color;
				$save[$table]['details_font'] = $request->details_font;
				$save[$table]['question_label_font'] = $request->question_label_font;
				$save[$table]['question_block_font'] = $request->question_block_font;
				$save[$table]['survey_type'] = $request->survey_type;
				$save[$table]['start_date'] = $startdate;
				$save[$table]['end_date'] = $enddate;
				$save[$table]['network_type'] = $request->network_type;
				$save[$table]['network_value'] = $request->network_value;
				
				$save[$table]['modify_date'] = date('Y-m-d h:i:s');
				
				$id = $this->create($save);
				return $id;
			/*}else{
			
				$fieldMember = $this->valid_field;

				$error = $this->validation_error;
	
				$name = 'createSurvey';
	
				$this->template->page('survey.tpl.html');
	
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $value)."</spam>");
				}

				$this->template->assign('FORM-HEADER', '<form action="survey.php?action=save" method="POST" name="'.$name.'">');
		
				$this->templateSurveyLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='survey.php\">Cancel</div>");
				//}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}*/
	}
	
	/**
	 * Set a row to be marked as deleted 
	 * 
	 * <pre>
	 * This method will take the id and set the delete_date field to 
	 * the current datetime, which will marking it as deleted
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * @param Integer $id The primary id of the row to marked as delete 
	 */
	Public function deleteSurveyDetails($id){
		$this->remove($id);
		header('Location: survey.php');
	}
	
	/**
	 * This method assigns the associate array values to the template
	 * 
	 * <pre>
	 * This method is used to incorperate the standards elements of the templates to a single 
	 * function across all tempatled methods
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>  
	 * </pre>
	 * @todo find out what $inputArray is used for 
	 * 
	 * @param Array $fielddata An associative array of fields that need to be assigned to the template object
	 * @param Boolean $input If false then just assign the value if true the add the value to corresponding form element 
	 * @param Array $inputArray Not sure :S
	 */
	private function templateSurveyLayout($fieldMember, $input = false, $inputArray=array() ){
				
				$id = @$fieldMember['survey_id'];
				
				@$this->template->assign('survey_id', ($input)? $this->template->input('text', 'survey_id', $fieldMember['survey_id']):$fieldMember['survey_id']);
				//@$this->template->assign('account_id', ($input)? $this->template->input('text', 'account_id', $fieldMember['account_id']):$fieldMember['account_id']);
				@$this->template->assign('survey_title', ($input)? $this->template->input('text', 'survey_title', $fieldMember['survey_title']):$fieldMember['survey_title']);
				@$this->template->assign('survey_type', ($input)? $this->template->input('text', 'survey_type', $fieldMember['survey_type']):$fieldMember['survey_type']);
				@$this->template->assign('survey_description', ($input)? $this->template->input('textarea', 'survey_description', $fieldMember['survey_description']):$fieldMember['survey_description']);
				@$this->template->assign('start_date', ($input)? $this->template->input('text', 'start_date', $fieldMember['start_date']):$fieldMember['start_date']);
				@$this->template->assign('end_date', ($input)? $this->template->input('text', 'end_date', $fieldMember['end_date']):$fieldMember['end_date']);
				@$this->template->assign('create_date', ($input)? $this->template->input('text', 'create_date', $fieldMember['create_date']):$fieldMember['create_date']);
				@$this->template->assign('modify_date', ($input)? $this->template->input('text', 'modify_date', $fieldMember['modify_date']):$fieldMember['modify_date']);
				@$this->template->assign('delete_date', ($input)? $this->template->input('text', 'delete_date', $fieldMember['delete_date']):$fieldMember['delete_date']);

				
				/*if(isset($id)){
					$this->template->assign('COMMENTS', $this->comment->getCommentBox($id, 'survey'));
				}*/
	
	}
	
	
	/**
	 * This medthod is used to validate inputs from form information 
	 * <pre>
	 * This method will first check the if the fields are in the valid_field array and strip out any that are not 
	 * Then it check that fields that require a value in them from the fields_required have a value, if not add an error to validation_error array 
	 * Then it will check all the values to find out if the value match the type found in the fields_validation_type array, if not add an error to validation_error array 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * 
	 * @see fields
	 * @see fields_required
	 * @see fields_validation_type
	 * @see validation_error
	 * 
	 * @param Array $request
	 */
	public function Validate($request){
	
		unset($this->valid_field);
		unset($this->validation_error);
		$isvalid = True;
		
		$validfields = $this->fields;
		$requiredfields = $this->fields_required;
		$fieldsvalidationtype = $this->fields_validation_type;
		
		foreach ($request AS $key=>$value){ //lets strip put unwanted or security violation fields  
			if(in_array($key, $validfields)){
				$this->valid_field[$key] = $value; //pure fields
			}
		}
		
		foreach ($validfields AS $value){ //now lets just add fields as blank if they didn't come though so we can check them, helps with checkboxs
			if(!isset($this->valid_field[$value])){
				$this->valid_field[$value] = ''; 
			}
		}
		
		if(count($requiredfields) > 0 ){
			foreach($requiredfields AS $value){ // lets check all the required fields have a value 
				if (empty($this->valid_field[$value]) || $this->valid_field[$value] == 'NULL'){ 
					$this->validation_error[$value][] = 'Field is Required'; //error field
					$isvalid = false;
				}
			}
		}
	
		
		
		//now lets validate
		foreach ($this->valid_field AS $key=>$value){
			$value = trim($value);
			if(!empty($value)){ // don't cheak if empty, alread done in required check 
				
				switch(@$fieldsvalidationtype[$key]){
					case 'TEXTAREA': if (strlen($value) > 1024) {
									$this->validation_error[$key][] = 'Field longer then 1024 charactors'; 
									$isvalid = false;
								} break;
					case 'TEXT': if (strlen($value) > 1024) {
									$this->validation_error[$key][] = 'Field longer then 1024 charactors'; 
									$isvalid = false;
								} break;
					case 'SAP': if ((!is_numeric($value)) || (strlen($value) != 10)) {
									$this->validation_error[$key][] = 'not a valid SAP number'; 
									$isvalid = false;
								} break;
					case 'DECIMAL': if (!is_numeric($value)) {
									$this->validation_error[$key][] = 'Decimal value expected';
									$isvalid = false;									
								} break;
					case 'BOOL': if ((!is_bool($value)) && (strtoupper($value)!="YES") && ($value != 1)) {
									$this->validation_error[$key][] = 'Please check'; 
									$isvalid = false;
								} break;
					case 'INT': if (!is_numeric($value) && $value != 'NULL' ){
									$this->validation_error[$key][] = 'Numeric value expected';
									$isvalid = false;
								} break;
					case 'DATE': //$date = str_replace('/', '-', $value);
								 //$date = str_replace("\\", '-', $date);
									@list($day, $month, $year) = explode('/', $value);
									if(!checkdate($month,$day, $year)){
										$this->validation_error[$key][] = 'incorrect date format, expecting dd/mm/yyyy'; 
										$isvalid = false;
									} break;	
					case 'YEAR':  if(!checkYear($value)){
										$this->validation_error[$key][] = 'incorrect year format, expecting yyyy'; 
										$isvalid = false;
								   } break;	
					
				}
			}
		}
	
		return $isvalid;
	
	}
	
	public function getQuestionDetails($sid,$qArray, $xml=false){
		if(!is_assoc($qArray)){
			return false;
		}	
		
		$list = '';
		
		foreach($qArray AS $qid => $type ){
			$list .= $this->getQuestion($sid, $qid, $type, $xml);
		}
		return $list;
	}
	
	public function getQuestionById($sid,$qid, $xml=false){
		$qid = str_replace('qid_', '', $qid);
		
		if(!$type = $this->getQuestionTypeById($qid)){
			throw new SoapFault('server','Not type set');
		}
		
		if(!$list = $this->getQuestion($sid,$qid, $type, $xml)){
			throw new SoapFault('server','No List');
		}
		
		if($xml){
			return '<?xml version="1.0" encoding="utf-8"?>'."\n".
				'<content>
					'.$list.'
				</content>';
		}else{
			return $list;
		}
	}
	
	protected function getQuestion($sid, $qid, $type, $xml){
		
		require_once(DIR_ROOT."question-plugin/$type/$type.class.php");
		
		$class = $type."Class";
		$question = new $class();
		return $question->get($sid, $qid, $xml);
	}
	
	public function appendNewQuestion( $id, $type, $title){
		
		require_once(DIR_ROOT.'question-plugin/'.$type.'/'.$type.'.class.php');
		$fields['question_label'] = $title;
		$fields['question_type'] = $type;
		$fields['question_id'] = $id;
		$fields['question_options'] = "";
		$class = $type."Class";
		$question = new $class();
		$list = $question->layout($fields);
		
		return $list;
	}
	
	public function getSurveyArray($sid){
		return $this->read($sid);
	}
	
	private function getSystemSetting($fields = NULL){
			
			$free = $single = $account = ''; 

			if(isset($fields['survey_type'])){
				switch($fields['survey_type']){
					case 'free' : $free = ' checked="checked"'; break;
					case 'single' : $single = ' checked="checked"'; break;
					case 'account' : $account = ' checked="checked"'; break;
					default : $account = ' checked="checked"';
				}
			}else{
				$account = ' checked="checked"';
			}
			
			if(isset($fields['isAccount'])){
				$disable = 'disabled="disabled"';
			}else{
				$fields['isAccount'] ='';
				$disable = '';
			}
			
			$display = '<div id="display"  style="padding:5px 0px;overflow:hidden">
					 <div class="top-left-box"></div>
				    <div class="top-right-box"></div>
				    <div class="inside-box" style="height:14px;">
				     	Display: <span id="display-field"></span><span class="close-frame" style="float:right"><img src="images/frame-dn.png" /></span>
				     	<br /><br />
				     	<div style="font-size: 10px">
				     	<p><h4><input type="radio"  name="display-type" value="Free"'.$free.'/>Free Useage</h4>
				     	Free Account that will allow you to display your survey, the survey is free for you to link to, but will contain advertising that you can not remove </p><br />
				     	<p><h4><input type="radio"  name="display-type" value="Single"'.$single.' />Single pay</h4>
				     	Single pay allows you to create a survey and display it without any advertising, With single pay you can allow us cloud technoloy to create the survey within yourr own website using a designed API, you will be charge $9.95 foe each month your survey is running </p><br />
				     	<div '.$fields['isAccount'].'><h4><input type="radio" name="display-type" value="Account"'.$account.' '.$disable.' />Payed Account</h4>
				     	if you have a payed account then your survey can be displayed under the requirments for the account you have payed for. <BR />you can change these account to free Usage or Single pay, but will take the requirments of that pay type </div><br />
				    	</div>
				    </div>
				    <div class="bottom-left-box"></div>
				    <div class="bottom-right-box"></div>
				</div>';
				$now = $never = '';
				$startday = $startmonth = $startyear = '';
				$endday = $endmonth = $endyear = '';
				
				if(empty($fields['start_date']) || $fields['start_date'] == '0000-00-00 00:00:00'){
					$now = ' checked="checked"';
				}else{
					list($date, $junk) = explode(" ",$fields['start_date']);
					list($Syear, $Smonth, $Sday) = explode("-", $date);
				}
				for($d=1; $d<=31; $d++){
						$select = ($d == intval(@$Sday))? " selected=selected": '';
						$startday .= "<option".$select.">$d</option>\n";
					}
				for($m=1; $m<=12; $m++){
						$select = ($m == intval(@$Smonth))? " selected=selected": '';
						$startmonth .= "<option".$select.">$m</option>\n";
				}
				
				$currdate = date('Y');
				$past = (empty($Syear) || $Syear >= ($currdate-5)) ?($currdate-5):$Syear;
				$future = ($currdate+10);
				for($y=$past; $y<=$future; $y++){
						$select = ($y == intval(@$Syear))? " selected=selected": '';
						$startyear .= "<option".$select.">$y</option>\n";
				}
				
				if(empty($fields['end_date']) || $fields['end_date'] == '0000-00-00 00:00:00'){
					$never = ' checked="checked"';
				}else{
					list($date, $junk) = explode(" ",$fields['end_date']);
					list($Eyear, $Emonth, $Eday) = explode("-",$date);
				}	
				
				for($d=1; $d<=31; $d++){
						$select = ($d == intval(@$Eday))? " selected=selected": '';
						$endday .= "<option".$select.">$d</option>\n";
					}
				for($m=1; $m<=12; $m++){
						$select = ($m == intval(@$Emonth))? " selected=selected": '';
						$endmonth .= "<option".$select.">$m</option>\n";
				}
				
				$currdate = date('Y');
				$past = (empty($Eyear) || $Eyear >= ($currdate-5)) ?($currdate-5):$Eyear;
				$future = ($currdate+10);
				for($y=$past; $y<=$future; $y++){
						$select = ($y == intval(@$Eyear))? " selected=selected": '';
						$endyear .= "<option".$select.">$y</option>\n";
				}
				
				$daterange = '<div id="daterange" style="padding:5px 0px;overflow:hidden">
					 <div class="top-left-box"></div>
				    <div class="top-right-box"></div>
				    <div class="inside-box" style="height:14px">
				     	Date range: <span id="daterange-field"></span><span class="close-frame" style="float:right"><img src="images/frame-dn.png" /></span>
				     	<br /><br />
				     	<div style="font-size: 10px">
				     	<p><h4>Set Display Date Range </h4>
				     	You can set the date range that you would like to display this survey, if you select Start date and then never end then the survey will start displaying on the display day and never end </p><br />
				     	<p>Start Date <select name="day" class="start">
				     					'.$startday.'
				     				  </select>/
				     				  <select name="month" class="start">
				     				  	'.$startmonth.'
				     				  </select>/
				     				  <select name="year" class="start">
				     				  	'.$startyear.'
				     				  </select>
				     				  <br /> --OR-- <input type="checkbox" id="startdate" name="startdate" value="now" '.$now.' /> Start Now</p><br />
				     	<p>End Date <select name="day" class="end">
				     					'.$endday.'
				     				  </select>/
				     				  <select name="month" class="end">
				     				  	'.$endmonth.'
				     				  </select>/
				     				  <select name="year" class="end">
				     				  	'.$endyear.'
				     				  </select>
				     				  <br /> --OR-- <input type="checkbox" id="enddate" name="enddate" value="never" '.$never.' /> Never end</p><br />
				     	
				    	</div>
				    </div>
				    <div class="bottom-left-box"></div>
				    <div class="bottom-right-box"></div>
				</div>';
				
				$open = $country= $range = $CIDR = '';
				$ipstart = $ipend = $startIP1 = $startIP2 = $startIP3 = $startIP4 = $endIP1 = $endIP2 = $endIP3 = $endIP4 = '';
				$ip = $CIDRIP1 = $CIDRIP2 = $CIDRIP3 = $CIDRIP4 = $CIDRIP5 = '';
				$countryCode = ''; 
				switch(trim(@$fields['network_type'])){
					case 'open' : $open = ' checked="checked"'; break;
					case 'country' : $country = ' checked="checked"'; 
									$countryCode = $fields['network_value'];
									
							break;
					case 'range' : $range = ' checked="checked"';
								 list($ipstart,$ipend)= explode('-',$fields['network_value']);
								 list($startIP1,$startIP2,$startIP3,$startIP4)= explode('.',$ipstart);
								 list($endIP1,$endIP2,$endIP3,$endIP4)= explode('.',$ipend);
							break;
					case 'CIDR' : $CIDR = ' checked="checked"';
								 list($ip,$CIDRIP5)= explode('/',$fields['network_value']);
								 list($CIDRIP1,$CIDRIP2,$CIDRIP3,$CIDRIP4)= explode('.',$ip);
							break;
					default : $open = ' checked="checked"';
				}
				
				$network = '<div id="network" style="padding:5px 0px;overflow:hidden">
					 <div class="top-left-box"></div>
				    <div class="top-right-box"></div>
				    <div class="inside-box" style="height:14px">
				     	Network : <span id="network-field"></span><span class="close-frame" style="float:right"><img src="images/frame-dn.png" /></span>
				     	<br /><br />
				     	You can limit who can see this surey by resticting view of the surey by either by IP Range or Country.
				     	<br /><br />
				     	<input type="radio" name="network" value="open"'.$open.' /> Open Network 
				  		<br /><br />
				     	<input type="radio" name="network" value="country"'.$country.' /> In this Courtry
				     	<select name="country" class="countryip">
								'.$this->getCountryList($countryCode).'
						</select><br /><br />
						<input type="radio" name="network" value="range"'.$range.' /> IP Range<br />
						Start	<input type="text" size="3" class="startIP" name="startIP1" value="'.trim($startIP1).'"/>.
								<input type="text" size="3" class="startIP" name="startIP2" value="'.trim($startIP2).'"/>.
								<input type="text" size="3" class="startIP" name="startIP3" value="'.trim($startIP3).'"/>.
								<input type="text" size="3" class="startIP" name="startIP4" value="'.trim($startIP4).'"/><br />
						End 	<input type="text" size="3" class="endIP" name="endIP1" value="'.trim($endIP1).'"/>.
								<input type="text" size="3" class="endIP" name="endIP2" value="'.trim($endIP2).'"/>.
								<input type="text" size="3" class="endIP" name="endIP3" value="'.trim($endIP3).'"/>.
								<input type="text" size="3" class="endIP" name="endIP4" value="'.trim($endIP4).'"/><br />
						<br />
						<input type="radio" name="network" value="CIDR"'.$CIDR.' /> Classless Inter-Domain Routing<br />
						<input type="text" class="CIDRIP" name="CIDRIP1" size="3" value="'.trim($CIDRIP1).'"/>.
						<input type="text" class="CIDRIP" name="CIDRIP2" size="3" value="'.trim($CIDRIP2).'"/>.
						<input type="text" class="CIDRIP" name="CIDRIP3" size="3" value="'.trim($CIDRIP3).'"/>.
						<input type="text" class="CIDRIP" name="CIDRIP4" size="3" value="'.trim($CIDRIP4).'"/>/
						<input type="text" class="CIDRIP" name="CIDRIP5" size="3" value="'.trim($CIDRIP5).'"/>
						<br clear="all" />
						
				    </div>
				    <div class="bottom-left-box"></div>
				    <div class="bottom-right-box"></div>
				</div>
				<br claer="all" />';
				
				return $display.$daterange.$network;
	}
	
	public function getCountryList($code = Null){
	
		if(empty($code)){
			$code = 'AU'; //Todo: if code is empty the get the code from IP_ADDRS using geoLocation
		}
		
		$sql = "SELECT * FROM country"; 
		
		try{
			 $result = $this->db->select($sql);
		}catch(CustomException $e){
			 echo $e->queryError($sql);
		}
		
		$row = '';
		foreach($result AS $value){
			$selected = ($value['code'] == $code)? 'selected=selected' : '';
			$row .= '<option value="'.$value['code'].'"'.$selected.'>'.$value['label'].'</option>'."\n";
		}
		return $row;
	}
	
	public function markDeletedQuestion($sid){
			
			$sql = "UPDATE question SET delete_check = 1 WHERE survey_id = $sid";
				
			try{
				$this->db->update($sql); 
			}catch(CustomException $e){
				throw new CustomException($e->queryError($sql2));
			}
	}
	
	public function removeDeletedQuestion($sid){
			
			$sql = "DELETE FROM question WHERE survey_id = $sid  AND delete_check = 1";
						
			try{
				$this->db->delete($sql); 
			}catch(CustomException $e){
				throw new CustomException($e->queryError($sql2));
			}
	}
}