<?php
/**
 * Category Class 
 * <pre>
 * This class is based on the table category
 *
 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
 * </pre> 
 * 
 * @author Jennifer Erator <jason@lexxcom.com>
 * @version 1.1 of the Framework generator
 * @package PeopleScope
 */

require_once(DIR_ROOT.'classes/question.class.php');

class selectClass extends question{
	
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
	

	public function __construct(){
		$this->db = new db();

		try {
			$this->db_connect = $this->db->dbh;
		} catch (CustomException $e) {
			$e->logError();
		}
	}
	
	Public function save($sid, $qid, $obj, $sort){
		list($junk, $qid) = explode('_',$qid);
		
		$this->saveMulti($sid, $qid, $obj, $sort);
	}
	
	Public function get($sid, $qid, $xml=false){
		return $this->layout($this->getMulti($sid, $qid), $xml);
	}
	
	Public function response($rid, $sid, $qid, $val){
		$this->responseMulti($rid, $sid, $qid, $val);
	} 
	
	Public function report($sid, $qid){
		return $this->reportMulti($sid, $qid);
	} 
	
	Public function layout($field, $xml=false){
			
			$width = (empty($field['question_options']->width))? '' : 'width:'.$field['question_options']->width.';';
			$height = (empty($field['question_options']->height))? '' : 'font-size:'.$field['question_options']->height.';';
			$text = ((empty($field['question_options']->text)) || ($field['question_options']->text=='start'))? '' : 'text-align:'.$field['question_options']->text.';';
			$size = (empty ($field['question_options']->size) || $field['question_options']->size <= 2) ? '' : ' multiple="multiple" size="'.$field['question_options']->size.'"';
			$caption = ((empty($field['question_options']->caption)))? '': $field['question_options']->caption ;
			
			$rows = '';
			if(isset($field['row'])){
				foreach($field['row'] AS $key=>$row){
					$rows .= '<option value="'.$row['question_row'].'">'.$row['row_label'].'</option>'."\n";
				}
			}
			
			if($xml){
				$ret = "
				<question>
					<style>
						<width>$width</width>
						<height>$height</height>
						<textStyle>$text</textStyle>
					</style>
					<id>qid_".$field['question_id']."</id>
					<class>".$field['question_type']."</class>
					<label>".$field['question_label']."</label>
					<class>".$field['question_type']."</class>
					<input>
						<id type=\"select\">q".$field['question_id']."</id>
						<name>q".$field['question_id']."</name>
						<options>
						".$rows."
						</options>
					</input>
					<caption>
						<id>q".$field['question_id']."_caption</id>
						<text>".$caption."</text>
					</caption>
				</question>";
			}else{
			
				$ret = '<li id="qid_'.$field['question_id'].'" class="'.$field['question_type'].'">
	                 	<div class="question-label">'.$field['question_label'].'</div>
	                   	<div class="question-block" style="'.$text.'">
	                        <select id = "q'.$field['question_id'].'" name="q'.$field['question_id'].'[]" '.$size.' style="'.$width.';line-height:200%;'.$height.'">
	                        	'.$rows.'
	                         </select>
	                     <div id="q'.$field['question_id'].'_caption" class="caption">'.$caption.'</div>
	                     
		           	   	</div>
	                    <div style="clear:both" />
	               </li>';
			}
			return $ret;
	}
	
}