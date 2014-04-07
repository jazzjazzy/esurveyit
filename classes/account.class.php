<?php
/**
 * Account Class 
 * <pre>
 * This class is based on the table account
 *
 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
 * </pre> 
 * 
 * @author Jennifer Erator <jason@lexxcom.com>
 * @version 0.1 alpha of the Framework generator
 * @package PeopleScope
 */

class account {
	
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
	private $fields =array('account_id', 'email', 'password', 'name', 'surname', 'company', 'phone', 'address', 'address2', 'suburb', 'postcode', 'state', 'create_date', 'modifiy_date', 'delete_date');
	
	/**
	 * Array of feilds require information when validating 
	 * @var Array|null
	 */
	private $fields_required =  array('email', 'password', 'name', 'surname', 'phone');
	
	/**
	 * Array of feilds and there types that are check when validating 
	 * @var Array|null
	 */
	private $fields_validation_type = array ('account_id'=>'TEXT', 'email'=>'EMAIL', 'password'=>'TEXT', 'name'=>'TEXT', 'surname'=>'TEXT', 'company'=>'TEXT', 'phone'=>'PHONE', 'address'=>'TEXT', 'address2'=>'TEXT', 'suburb'=>'TEXT', 'postcode'=>'TEXT', 'state'=>'TEXT', 'create_date'=>'TEXT', 'modifiy_date'=>'TEXT', 'delete_date'=>'TEXT');
	
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
		
		$this->template = new template();
		$this->template->assign('LOGIN', account::loggedIn());
	
	}
	
	/**
	 * Show will pull a list from the corresponding Account account
	 * 
	 * <pre>
	 * This Method will produce a list of all the element corresponding to the result of Account
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
		
		$sql = "SELECT account_id,
email,
password,
name,
surname,
company,
phone,
address,
address2,
suburb,
postcode,
state,
create_date,
modifiy_date,
delete_date FROM account WHERE (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)";
		
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
				
				foreach($source['account'] AS $key=>$val){
					$field[] = $key;
					$value[] = ":".$key;
				}

				$sql = "INSERT INTO account (".implode(', ',$field).") VALUES (".implode(', ',$value).");";
				
				foreach($source['account'] AS $key=>$val){
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
	
		$sql = "SELECT account_id,
email,
password,
name,
surname,
company,
phone,
address,
address2,
suburb,
postcode,
state,
create_date,
modifiy_date,
delete_date FROM account WHERE account_id = ". $id ." AND (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)" ;
		
			try{
				 $result = $this->db->select($sql);
			}catch(CustomException $e){
				 echo $e->queryError($sql);
			}

			return $result[0];
		
			
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
				
				foreach($source['account'] AS $key=>$val){
					$field[] = $key." = :".$key;
				}

				$sql = "UPDATE account SET ".implode(', ',$field)." WHERE account_id =". $id;

				foreach($source['account'] AS $key=>$val){
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
			
			header('Location:account.php?action=show&id='.$id);
			
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
			
			$sql = "UPDATE account SET delete_date=NOW() WHERE account_id =". $id;

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
	 * <pre>This Method will produce a list of all the element corresponding to the result of Account
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
	
	public function getAccountList($type='TABLE',$orderby=NULL, $direction='ASC', $filter=NULL){
		
		$result = $this->lists($orderby, $direction, $filter);
		
		$this->table->removeColumn(array('account_id'));
		
		switch(strtoupper($type)){
		
			case 'AJAX' : $this->table->setRowsOnly(); 
						  $this->table->setIdentifier('account_id');
						  $this->table->setIdentifierPage('account');
						  echo $this->table->genterateDisplayTable($result);
						  
				BREAK;
			case 'TABLE' :
			DEFAULT :
				$this->table->setHeader(array(
						'account_id'=>'Account Id',
'email'=>'Email',
'password'=>'Password',
'name'=>'Name',
'surname'=>'Surname',
'company'=>'Company',
'phone'=>'Phone',
'address'=>'Address',
'address2'=>'Address2',
'suburb'=>'Suburb',
'postcode'=>'Postcode',
'state'=>'State',
'create_date'=>'Create Date',
'modifiy_date'=>'Modifiy Date',
'delete_date'=>'Delete Date'));
				
				$this->table->setFilter(array(	
						'account_id'=>'TEXT',
'email'=>'TEXT',
'password'=>'TEXT',
'name'=>'TEXT',
'surname'=>'TEXT',
'company'=>'TEXT',
'phone'=>'TEXT',
'address'=>'TEXT',
'address2'=>'TEXT',
'suburb'=>'TEXT',
'postcode'=>'TEXT',
'state'=>'TEXT',
'create_date'=>'TEXT',
'modifiy_date'=>'TEXT',
'delete_date'=>'TEXT'));
				
				$this->table->setIdentifier('account_id');
				
				$this->template->content(Box($this->table->genterateDisplayTable($result),'Account List', 'Shows the current listings for the Account. To create a new Listing <a href="account.php?action=create">Click Here</a>'));
				
				$this->template->display();
		}
	}
	
	
	/**
	 * Show details of a single Account from the account
	 * 
	 * <pre>This method will return a template page of the information requested 
	 * the method use the template class to format the information ready to display the 
	 * the user 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * @param Integer $id the primary id of the row to show 
	 */
	Public function showAccountDetails($id){
		$fieldMember = $this->read($id);
		
		$this->template->page('account.tpl.html');
		
		$this->templateAccountLayout($fieldMember);

		//if($this->checkAdminLevel(1)){
			$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"location.href='account.php?action=edit&id=".$id."'\">Edit</div>");
		//}
		
		echo $this->template->fetch();	
	}
		
	
	/**
	 * Show the details ready to edit of a single Account from the account 
	 * 
	 * <pre>
	 * This method is used to display and editable page to the use, so that they 
	 * maybe able to edit any of the fields releated to the row in question. 
	 * The method uses the template class to format the information ready to display the 
	 * the user 
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>     
	 * </pre>
	 * @param Integer $id The primary id of the row to show 
	 */
	Public function editAccountDetails($id){
		
		$fieldMember = $this->read($id);
		
		$name = 'editAccount';
		
		$this->template->page('account.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="account.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
		$this->templateAccountLayout($fieldMember, true);
		
		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='account.php?action=show&id=".$id."'\">Cancel</div>");
		
		$this->template->display();
	}
	
	/**
	 * update the information in a single Account from the account 
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
	Public function updateAccountDetails($id){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'account';

				$save[$table]['email'] = $request['email'];
				$save[$table]['password'] = $request['password'];
				$save[$table]['name'] = $request['name'];
				$save[$table]['surname'] = $request['surname'];
				$save[$table]['company'] = $request['company'];
				$save[$table]['phone'] = $request['phone'];
				$save[$table]['address'] = $request['address'];
				$save[$table]['address2'] = $request['address2'];
				$save[$table]['suburb'] = $request['suburb'];
				$save[$table]['postcode'] = $request['postcode'];
				$save[$table]['state'] = $request['state'];
				$save[$table]['modifiy_date'] = $request['modifiy_date'];
				
				$save[$table]['modify_date'] = date('Y-m-d h:i:s');
				
				$this->update($save, $id );
				header('Location: account.php?action=show&id='.$id);
			}else{
				
				$fieldMember = $this->valid_field;
				$error = $this->validation_error;
				
				$name = 'editAccount';
		
				$this->template->page('account.tpl.html');
				
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $error[$key])."</spam>");
				}
				
				$this->template->assign('FORM-HEADER', '<form action="account.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
				$this->templateAccountLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='account.php?action=show&id=".$id."'\">Cancel</div>");
				//}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
	}
	
	/**
	 * This method will provide a page to the to add a single row Account to the account table
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
	Public function createAccountDetails(){
		
		$name = 'createAccount';
		
		$this->template->page('account.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="account.php?action=save" method="POST" name="'.$name.'">');
		
		$this->templateAccountLayout('', true);
		
		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Save</div><div class=\"button\" onclick=\"location.href='account.php?action=list'\">Cancel</div>");
		

		$this->template->display();
	} 
	
	/**
	 * save the information in a single Account to the account table 
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
	Public function saveAccountDetails(){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'account';

				$save[$table]['email'] = $request['email'];
				$save[$table]['password'] = $request['password'];
				$save[$table]['name'] = $request['name'];
				$save[$table]['surname'] = $request['surname'];
				$save[$table]['company'] = $request['company'];
				$save[$table]['phone'] = $request['phone'];
				$save[$table]['address'] = $request['address'];
				$save[$table]['address2'] = $request['address2'];
				$save[$table]['suburb'] = $request['suburb'];
				$save[$table]['postcode'] = $request['postcode'];
				$save[$table]['state'] = $request['state'];
				//$save[$table]['modifiy_date'] = $request['modifiy_date'];
				
				$save[$table]['create_date'] = date('Y-m-d h:i:s');
				
				$id = $this->create($save);
				header('Location: account.php?action=show&id='.$id);
			}else{
			
				$fieldMember = $this->valid_field;

				$error = $this->validation_error;
	
				$name = 'createAccount';
	
				$this->template->page('account.tpl.html');
	
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $value)."</spam>");
				}

				$this->template->assign('FORM-HEADER', '<form action="account.php?action=save" method="POST" name="'.$name.'">');
		
				$this->templateAccountLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Save</div><div class=\"button\" onclick=\"location.href='account.php\">Cancel</div>");
				//}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
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
	Public function deleteAccountDetails($id){
		$this->remove($id);
		header('Location: account.php');
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
	private function templateAccountLayout($fieldMember, $input = false, $inputArray=array() ){
				
				$id = @$fieldMember['account_id'];

								@$this->template->assign('account_id', ($input)? $this->template->input('text', 'account_id', $fieldMember['account_id']):$fieldMember['account_id']);
				@$this->template->assign('email', ($input)? $this->template->input('text', 'email', $fieldMember['email']):$fieldMember['email']);
				@$this->template->assign('password', ($input)? $this->template->input('text', 'password', $fieldMember['password']):$fieldMember['password']);
				@$this->template->assign('name', ($input)? $this->template->input('text', 'name', $fieldMember['name']):$fieldMember['name']);
				@$this->template->assign('surname', ($input)? $this->template->input('text', 'surname', $fieldMember['surname']):$fieldMember['surname']);
				@$this->template->assign('company', ($input)? $this->template->input('text', 'company', $fieldMember['company']):$fieldMember['company']);
				@$this->template->assign('phone', ($input)? $this->template->input('text', 'phone', $fieldMember['phone']):$fieldMember['phone']);
				@$this->template->assign('address', ($input)? $this->template->input('text', 'address', $fieldMember['address']):$fieldMember['address']);
				@$this->template->assign('address2', ($input)? $this->template->input('text', 'address2', $fieldMember['address2']):$fieldMember['address2']);
				@$this->template->assign('suburb', ($input)? $this->template->input('text', 'suburb', $fieldMember['suburb']):$fieldMember['suburb']);
				@$this->template->assign('postcode', ($input)? $this->template->input('text', 'postcode', $fieldMember['postcode']):$fieldMember['postcode']);
				@$this->template->assign('state', ($input)? $this->template->input('text', 'state', $fieldMember['state']):$fieldMember['state']);
				@$this->template->assign('create_date', ($input)? $this->template->input('text', 'create_date', $fieldMember['create_date']):$fieldMember['create_date']);
				@$this->template->assign('modifiy_date', ($input)? $this->template->input('text', 'modifiy_date', $fieldMember['modifiy_date']):$fieldMember['modifiy_date']);
				@$this->template->assign('delete_date', ($input)? $this->template->input('text', 'delete_date', $fieldMember['delete_date']):$fieldMember['delete_date']);

				
				/*if(isset($id)){
					$this->template->assign('COMMENTS', $this->comment->getCommentBox($id, 'account'));
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
									$this->validation_error[$key][] = 'Field too longer (1024)'; 
									$isvalid = false;
								} break;
					case 'TEXT': if (strlen($value) > 255) {
									$this->validation_error[$key][] = 'Field too longer (255)'; 
									$isvalid = false;
								} break;
					case 'EMAIL': if (!check_email_address($value)) {
									$this->validation_error[$key][] = 'Not Email format'; 
									$isvalid = false;
								} break;
					case 'PHONE': if (!check_phone_number($value)) {
									$this->validation_error[$key][] = 'Not Valid phone Number'; 
									$isvalid = false;
								} break;
					case 'SAP': if ((!is_numeric($value)) || (strlen($value) != 10)) {
									$this->validation_error[$key][] = 'Not a valid SAP number'; 
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
	
	public function isLoggedIn(){
		return (isset($_SESSION['user']))?true:false;
	}
	
	public function getAccountId(){
		return (isset($_SESSION['user']['account_id']))?$_SESSION['user']['account_id']:false;
	}
	
	public function isActiveAccount(){
		if(!$account_id = account::getAccountId()){
			return false;
		}
		
		$sql = "SELECT * FROM invoice WHERE account_id = ".$account_id;

		try{
			 $result = $this->db->select($sql);
		}catch(CustomException $e){
			 echo $e->queryError($sql);
		}

		return (isset($result[0]))?true:false;

	}
	
	public function LoggedIn(){
		if(account::isLoggedIn()){
			return '<div class="loggedIn">You are Currently logged in <br /> '.$_SESSION['user']['name'].' '.$_SESSION['user']['surname'].'<br /><a href="index.php?action=logout">logout</a></div>';
		}
		return '';
	}
	
}