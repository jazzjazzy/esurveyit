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

class arrayClass extends question{
	
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
		
		$this->saveArray($sid, $qid, $obj, $sort);
	}

	Public function get($sid, $qid, $xml=false){
		return $this->layout($this->getArray($sid, $qid), $xml);
	}
	
	Public function response($rid, $sid, $qid, $val){
		$this->responseArray($rid, $sid, $qid, $val);
	} 
	
	Public function report($sid, $qid){
		return $this->reportArray($sid, $qid);
	} 
	
	public function layout($fields = NULL, $xml=false){
			
			$caption = ((empty($fields['question_options']->caption)))? '': $fields['question_options']->caption;
			
			$header = '';
			$rows = '';
			if(!empty($fields['column'])){
				foreach($fields['column'] AS $key=>$column){
					if($xml){
						$header .= '<header>
										<label>'.$column['row_label'].'</label>
										<class>header-'.$key.'</class>
									</header>';	
					}else{
						$header .= '<th class="header-'.$key.'">'.$column['row_label'].'</th>'."\n";
					}
				}
			}	
			if(!empty($fields['row'])){	
				foreach($fields['row'] AS $key=>$row){
					if($xml){
						$singleRow = '';
						foreach($fields['column'] AS $colkey=>$column){
							$singleRow .= "
											<column>
												<class>col".$colkey."</class>
												<name>q".$fields['question_id']."[".$key."]</name>
												<value>".$colkey."</value>
											</column>
							";
						}
						$rows .= '<row id="'.$key.'">
										<columns>'.$singleRow.'</columns>
								</row>';
					}else{
						$rows .= '<tr><td class="label" id="label-'.$key.'">'.$row['row_label'].'</td>';
						foreach($fields['column'] AS $colkey=>$column){
							$rows .= '<td class="col'.$colkey.'"><input type="radio" name="q'.$fields['question_id'].'['.$key.']" value="'.$colkey.'" /></td>';
						}
						$rows .= '</tr>';
					}
				}
			}
			
			if($xml){
				$ret ="<question>
							<id>qid_".$fields['question_id']."</id>
							<class>".$fields['question_type']."</class>
							<label>".$fields['question_label']."</label>
							<class>".$fields['question_type']."</class>
							<header-list>
							".$header."
							</header-list>
							<rows>
							".$rows."
							</rows>
							<caption>
								<id>q".$fields['question_id']."_caption</id>
								<text>".$caption."</text>
							</caption>
						</question>";
			}else{
				$ret = '<li id="qid_'.$fields['question_id'].'" class="'.$fields['question_type'].'">
	                 	<div class="question-label">'.$fields['question_label'].'</div>
	                       <div class="question-block">
	                            <table width="100%" border="0" id="q'.$fields['question_id'].'">
	                              <theader >
	                              	<th class="blank"></th>
	                                '.$header.'
	                              </theader >
	                              '.$rows.'
	                            </table>
	                            <div id="q'.$fields['question_id'].'_caption" class="caption">'.$caption.'</div>
	                        </div>
	                 </li>';
			}
			return $ret;
	}
}