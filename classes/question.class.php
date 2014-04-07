<?php


class question {

	protected function saveSingle($sid, $qid, $val, $sort){
		try{

				$this->db_connect->beginTransaction();
				
				$sql = "INSERT INTO question ( question_id, survey_id, question_label, question_type, question_options, sort, delete_check) VALUES ( ".$qid.", ".$sid.", '".$val->title."','".$val->type."' ,'".serialize($val->options)."','".$sort."', 0)
							ON DUPLICATE KEY UPDATE question_label='".$val->title."', question_type='".$val->type."', question_options='".addslashes(serialize($val->options))."', sort='".$sort."', delete_check=0;"; 
				
				try{
					$pid = $this->db->insert($sql); 
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
			
			return true;
	}
	
	protected function saveMulti($sid, $qid, $val, $sort){
		try{
				$this->db_connect->beginTransaction();

				$sql = "INSERT INTO question ( question_id, survey_id, question_label, question_type, question_options, sort, delete_check) VALUES ( ".$qid.", ".$sid.", '".$val->title."','".$val->type."' ,'".serialize($val->options)."','".$sort."', 0)
							ON DUPLICATE KEY UPDATE question_label='".$val->title."', question_type='".$val->type."', question_options='".addslashes(serialize($val->options))."', sort='".$sort."', delete_check = 0;"; 
				
				try{
					$pid = $this->db->insert($sql); 
				}catch(CustomException $e){
					throw new CustomException($e->queryError($sql));
				}
				
				$sql = "UPDATE question_multi SET delete_check = 1 WHERE question_id = $qid AND  survey_id = $sid";
				
				try{
						$this->db->update($sql); 
				}catch(CustomException $e){
						throw new CustomException($e->queryError($sql2));
				}
				
				foreach($val->rows AS $rowkey=>$row){
					$sql2 = "INSERT INTO question_multi (question_id, survey_id, question_row, row_label, delete_check) VALUES (".$qid.",".$sid.",".$rowkey.",'".$row."', 0)
					ON DUPLICATE KEY UPDATE row_label = '".$row."', delete_check = 0";
					try{
						$pid = $this->db->insert($sql2); 
					}catch(CustomException $e){
						throw new CustomException($e->queryError($sql2));
					}
				}

				$sql = "DELETE FROM question_multi WHERE question_id = $qid AND  survey_id = $sid  AND delete_check = 1";
						
				try{
					$this->db->delete($sql); 
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
			
			return true;
	}
	
	protected function saveArray($sid, $qid, $val, $sort){
		try{
				$this->db_connect->beginTransaction();
				
				$sql = "INSERT INTO question ( question_id, survey_id, question_label, question_type, question_options, sort, delete_check) VALUES ( ".$qid.", ".$sid.", '".$val->title."','".$val->type."' ,'".serialize($val->options)."','".$sort."', 0)
							ON DUPLICATE KEY UPDATE question_label='".$val->title."', question_type='".$val->type."', question_options='".addslashes(serialize($val->options))."', sort='".$sort."', delete_check=0;\n<br />"; 
				
				try{
					$pid = $this->db->insert($sql); 
				}catch(CustomException $e){
					throw new CustomException($e->queryError($sql));
				}
				
				$sql = "UPDATE question_array SET delete_check = 1 WHERE question_id = $qid AND  survey_id = $sid";
				
				try{
						$this->db->update($sql); 
				}catch(CustomException $e){
						throw new CustomException($e->queryError($sql2));
				}
				
				foreach($val->rows AS $rowkey=>$row){
					foreach($val->columns AS $colkey=>$column){
						$sql2 = "INSERT INTO question_array (question_id, survey_id, question_row, row_label, question_column, column_label, delete_check) VALUES (".$qid.",".$sid.",".$rowkey.",'".$row."',".$colkey.",'".$column."', 0)
						ON DUPLICATE KEY UPDATE row_label='".$row."', column_label='".$column."', delete_check=0\n";
						pp($sql2);
						try{
							$pid = $this->db->insert($sql2); 
						}catch(CustomException $e){
							throw new CustomException($e->queryError($sql2));
						}
					}
				}
				
				$sql = "DELETE FROM question_array WHERE question_id = $qid AND  survey_id = $sid  AND delete_check = 1";
						
				try{
					$this->db->delete($sql); 
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
			
			return true;
	}
	protected function getSingle($sid, $qid){
				
			$sql = "SELECT * FROM question WHERE question_id = $qid;";

			try{
				$result = $this->db->select($sql); 
			}catch(CustomException $e){
				throw new CustomException($e->queryError($sql));
			}
			$result[0]['question_options'] = unserialize($result[0]['question_options']);
			return $result[0];	
	}
	
	protected function getMulti($sid, $qid){
				
			$sql = "SELECT question.question_id, question_label, question_type, question_options, question_row, row_label 
						FROM question 
						LEFT JOIN question_multi ON (question.question_id = question_multi.question_id AND question.survey_id = question_multi.survey_id)
						WHERE question.question_id = $qid AND question.survey_id = $sid;";

			try{
				$result = $this->db->select($sql); 
			}catch(CustomException $e){
				throw new CustomException($e->queryError($sql));
			}
			
			$count = 0;
			foreach($result AS $value){
				$ret['question_id'] = $value['question_id'];	
				$ret['question_label'] = $value['question_label'];	
				$ret['question_type'] = $value['question_type'];	
				$ret['question_options'] = unserialize($value['question_options']);
				$ret['row'][$count]['question_row'] = $value['question_row'];	
				$ret['row'][$count]['row_label'] = $value['row_label'];	
				$count++;	
			}
			
			return $ret;	
	}
	
	protected function getArray($sid, $qid){
				
			$sql = "SELECT question.question_id, question_label, question_type, question_options, question_row, row_label, question_column, column_label 
							FROM question 
							LEFT JOIN question_array ON (question.question_id = question_array.question_id AND question.survey_id = question_array.survey_id)
							WHERE question.question_id = $qid AND question.survey_id = $sid;";

			try{
				$result = $this->db->select($sql); 
			}catch(CustomException $e){
				throw new CustomException($e->queryError($sql));
			}
			
			$count = 0;
			foreach($result AS $value){
				$ret['question_id'] = $value['question_id'];	
				$ret['question_label'] = $value['question_label'];	
				$ret['question_type'] = $value['question_type'];	
				$ret['question_options'] = unserialize($value['question_options']);
				$ret['row'][$value['question_row']]['row_label'] = $value['row_label'];	
				$ret['column'][$value['question_column']]['row_label'] = $value['column_label'];	
			}
			
			return $ret;	
	}
	
	protected function responseSingle($rid, $sid, $qid, $val){
		try{
				$this->db_connect->beginTransaction();
				
				$sql = "INSERT INTO response_single ( response_id, question_id, survey_id, single_value) VALUES ( ".$rid.", ".$qid.", ".$sid.", '".$val."');";

				try{
					$pid = $this->db->insert($sql); 
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
			
			return true;
	}
	
	
	protected function responseMulti($rid, $sid, $qid, $val){
		try{	
				$this->db_connect->beginTransaction();
				
				foreach($val AS $rowkey=>$row){
					$sql = "INSERT INTO response_multi (response_id, question_id, survey_id, question_row, multi_value) VALUES (".$rid.", ".$qid.",".$sid.",".$row.",'')";
					try{
						$pid = $this->db->insert($sql); 
					}catch(CustomException $e){
						throw new CustomException($e->queryError($sql));
					}
				}
				
				$this->db_connect->commit();
			}

			catch (CustomException $e) {
				$e->queryError($sql);
				$this->db_connect->rollBack();
				return false;
			}
			
			return true;
	}
	
	protected function responseArray($rid, $sid, $qid, $val){
		try{
				$this->db_connect->beginTransaction();
				
				foreach($val AS $row=>$column){
					
						$sql = "INSERT INTO response_array (response_id, question_id, survey_id, question_row, question_column, array_value) VALUES (".$rid.",".$qid.",".$sid.",".$row.",".$column.", 'true')";
						try{
							$pid = $this->db->insert($sql); 
						}catch(CustomException $e){
							throw new CustomException($e->queryError($sql));
						}
				}

				$this->db_connect->commit();
			}

			catch (CustomException $e) {
				$e->queryError($sql);
				$this->db_connect->rollBack();
				return false;
			}
			
			return true;
	}
	
	protected function reportSingle($sid, $qid){
			$sql = "SELECT
					*
				FROM
				response_single
				WHERE response_single.survey_id = ".$sid." AND response_single.question_id = ".$qid;
			
		try{
			$result = $this->db->select($sql); 
		}catch(CustomException $e){
			throw new CustomException($e->queryError($sql));
		}
		$word = array();
		foreach($result AS $value){
			
			$cleanUnicodeStr = trim(preg_replace('#[^\p{L}\p{N}]+#u', ' ', $value['single_value']));
			foreach(explode(' ', $cleanUnicodeStr) AS $list){
				if(!isset($word[$list])){
					$word[$list] = 1;
				}else{
					$word[$list] += 1;
				}
			}
		}
		arsort($word);
		array_splice($word, 10);
		
		foreach($word as $key=>$val){
			$lista[] = $val;
			$row2[] ="'".$key."'"; 
		}
		
		$list2['value'] = "[{data:[".implode(',' ,$lista)."]}]";
		$list2['response'] = implode(',' ,$row2);
		$list2['question_label'] = "Question 1";
		
		return $list2;
	}
	
	protected function reportMulti($sid, $qid){
		
		$sql = "SELECT
					question_multi.question_row AS question_row,
					response_multi.question_row AS '_value',
					row_label
				FROM
				question_multi
				LEFT Join response_multi ON (
						question_multi.survey_id = response_multi.survey_id AND 
						question_multi.question_id = response_multi.question_id AND 
						question_multi.question_row = response_multi.question_row )
				WHERE question_multi.survey_id = ".$sid." AND question_multi.question_id = ".$qid;
	
		try{
			$result = $this->db->select($sql); 
		}catch(CustomException $e){
			throw new CustomException($e->queryError($sql));
		}
		
		$array1 = array();
		
		foreach($result AS $value){

			if(!isset($array1[$value['question_row']])){
				$array1[$value['question_row']] = array('value'=>0, 'label'=>$value['row_label']);
			}
			
			if(!empty($value['_value'])){
				$array1[$value['question_row']]['value'] = $array1[$value['question_row']]['value']+1;
			}
			
		}
		
		foreach($array1 AS $value){
			$list['value'][] = $value['value'];
			$list['response'][] = "'".$value['label']."'";
		}
		
		$sql = "SELECT question_label FROM question WHERE question_id = ".$qid." and survey_id =".$sid.";";
		
		try{
			$result = $this->db->select($sql); 
		}catch(CustomException $e){
			throw new CustomException($e->queryError($sql));
		}
		
		$list['value'] = "[{data:[".implode(',' ,$list['value'])."]}]";
		$list['response'] = implode(',' ,$list['response']);
		$list['question_label'] = $result[0]['question_label'];
				
		return $list;
	} 
	
	protected function reportArray($sid, $qid){
		
		$sql = "SELECT
				response_array.response_id,
				question_array.question_row AS question_row,
				question_array.question_column AS question_column,
				response_array.question_row'_value',
				response_array.question_column'_value2',
				question_array.row_label,
				question_array.column_label
				FROM
				question_array
				Left Outer Join response_array ON (question_array.survey_id = response_array.survey_id AND question_array.question_id = response_array.question_id AND question_array.question_row = response_array.question_row AND question_array.question_column = response_array.question_column)
				WHERE question_array.survey_id = ".$sid." AND question_array.question_id = ".$qid.";";
		 	
		try{
			$result = $this->db->select($sql); 
		}catch(CustomException $e){
			throw new CustomException($e->queryError($sql));
		}
		
		
		$array1 = array();
		
		foreach($result AS $value){

			if(!isset($array1[$value['question_row']][$value['question_column']])){
				$array1[$value['question_row']][$value['question_column']] = array('value'=>0, 'row'=>$value['row_label'], 'column'=>$value['column_label']);
			}
			
			if(!empty($value['_value'])){
				$array1[$value['question_row']][$value['question_column']]['value'] += 1;
				$row2[$value['question_column']] = "'".$value['column_label']."'";
				//$Column[$value['question_column']] = $value['column_label'];
			}
		}

		foreach($array1 AS $krow => $row){
			unset($nameColumn);
			unset($valueColumn);
			foreach($row AS $kcolumn => $column){
				$nameColumn = $column['row'];
				$valueColumn[]  = $column['value'];
			}
			$lista[] = "{name: '".$nameColumn."', data:[".implode(',' ,$valueColumn)."]}";
		}

		@$list['value'] = "[".implode(',' ,$lista)."]";
		@$list['response'] = implode(',' ,$row2);
		@$list['question_label'] = "Question 1";
		
		return $list;
	}
	
	protected function getQuestionTypeById($qid){
		$id = str_replace('qid_', '', $qid);
		
		$sql = "SELECT question_type FROM question WHERE question_id = $id LIMIT 0,1;";
		
		try{
			$result = $this->db->select($sql); 
		}catch(CustomException $e){
			throw new CustomException($e->queryError($sql));
		}
		
		return $result[0]['question_type'];
	}
	
	
}