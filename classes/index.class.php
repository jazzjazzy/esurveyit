<?php 
class index {
	/**
	 * Connect to PDO object through database class
	 * @var Object
	 */
	protected $db_connect;
	
	/**
	 * Database class object 
	 * @var Object
	 */
	protected $db;
	
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
	 * Show index page
	 * 
	 * <pre>This method will return a template page of the information requested 
	 * the method use the template class to format the information ready to display the 
	 * the user 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * @param Integer $id the primary id of the row to show 
	 */
	Public function showIndexDetails($error = NULL){
		$this->template->page('index.tpl.html');

		if(account::isLoggedIn()){
			$this->template->assign('Login', $this->stats());
		}else{
			$this->template->assign('Login', $this->loginForm());
		}
		    	
		if(!empty($error)){
			$this->template->assign('errorLogin', warning($error));	
		}else{
			$this->template->assign('errorLogin', '&nbsp;');
		}

		echo $this->template->fetch();	
	}
	
	/**
	 * Will check the login and forward to the correct page
	 * @param String $email
	 * @param String $password
	 */
	Public function loginIndexDetails($email, $password, $URI=NULL){
		
		if($this->login($email, $password)){
			if(!empty($URI)){
				header('Location:'.$URI);	
			}else{
				$this->showIndexDetails();
			}
		}else{
			$error="you are not logged in check you email or password ";
			$this->showIndexDetails($error);
		}
	}
	
	/**
	 * Will check the login and return boolean 
	 * 
	 * @param String $email
	 * @param String $password
	 * @return boolean 
	 */
	public function login($email, $password){
		$sql = "SELECT * FROM account WHERE email = '".$email."' AND password ='".$password."';";
		
		try{
			 $result = $this->db->select($sql);
		}catch(CustomException $e){
			 echo $e->queryError($sql);
		}
		
		$resultArray=@$result[0];
		
		if (is_assoc($resultArray)){
			$_SESSION['user']['account_id'] = $result[0]['account_id'];
			$_SESSION['user']['name'] = $result[0]['name'];
			$_SESSION['user']['surname'] = $result[0]['surname'];
			$_SESSION['user']['company'] = $result[0]['company'];
			return true;
		}
		
		return false;
	
	}
	
	private function loginForm(){
		$html=' 			<div class="register">
	                             <h3>Login</h3>
	                              <h5>Login here to use your account</h5>
	                              <div>{*errorLogin*}</div>
	                              <div id="login-body">
	                             	  <form method="POST" action="index.php?action=login" >
	                             	  <div class="label">Email</div> <div class="value"><input type="input" name="email" /></div><br class="clear"/>
									  <div class="label">Password</div> <div class="value"><input type="password" name="password" /></div><br class="clear"/>
		                              <br /><input type="image" src="images/button.png" alt="Login" /></input><br />
		                              </form>
		                              <a href="#">Forgotten Password</a>|<a href="account.php?action=create">Registor</a>
								  </div>
	                            </div>';
		
		return $html;
	}
	
	public function logoutIndexDetails($URL){
		unset($_SESSION['user']);
		header('Location:'.$URL);
		exit();
	}
	
	private function stats(){
		return "this is what I am looking for";	
	}
}
?>
