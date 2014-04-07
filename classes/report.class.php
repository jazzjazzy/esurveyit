<?php

/**
 * Report Class 
 * <pre>
 * This class is based on the table Report 
 *
 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
 * </pre> 
 * 
 * @author Jennifer Erator <jason@lexxcom.com>
 * @version 0.1 alpha of the Framework generator
 * @package PeopleScope
 */

require_once('survey.class.php');

class report extends survey{
	
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

	public function showReportDetails(){ 
		
		$qArray = $this->getSurveyArray($this->sid);
		$list = '';
		$count=1;
		$script = '<script language="javascript" type="text/javascript" src="js/jhighlite/highcharts.js"></script>'."\n";
		$divs = '';
		
		foreach($qArray['question'] AS $qid => $type ){
		
			require_once(DIR_ROOT.'question-plugin/'.$type.'/'.$type.'.class.php');
			
			//if($type != "text" && $type != "textarea" ){
				$class = $type."Class";
				$question = new $class();
				$script .= $this->graphChart($count, $question->report($this->sid, $qid));
				$divs .= "<div id =\"chart".$count."\"></div>";
				$count++;
			//}
		}
		//$script .= "\n".'})</script>';
		
		$this->template->page('report.tpl.html');
		$this->template->assign('script', $script);
		$this->template->assign('question', $divs);
		$this->template->assign('FUNCTION', "<input type=\"image\" class=\"button\" value=\"submit\">");

		
		echo $this->template->fetch();	
		
		
		
		//return $list;
	}
	
	public function graphChart($id, $list){
		$script = "
		<script class=\"code\" type=\"text/javascript\">
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'chart".$id."',
						defaultSeriesType: 'column',
						margin: [ 50, 50, 150, 80]
					},
					title: {
						text: '".$list['question_label']."'
					},
					xAxis: {
						categories: [".$list['response']."],
						labels: {
							rotation: -45,
							align: 'right',
							style: {
								 font: 'normal 13px Verdana, sans-serif'
							}
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: '# Responses'
						}
					},
					legend: {
						enabled: true
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.x +'</b><br/>'+
								  Highcharts.numberFormat(this.y, 0);
						}
					},
				    series: ".$list['value']."
				});	
			});</script>
        
        ";
		return $script;
	}
}
