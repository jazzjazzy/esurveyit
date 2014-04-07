<?php


class arrayTable {
	
	
	protected function saveArray($id, $val){
		try{
				$this->db_connect->beginTransaction();
				
				$sql = "INSERT INTO question (question_label, question_type, question_options) VALUES ('".$val->title."','".$val->type."' ,'".serialize($val->options)."');"; 
				
				try{
					$pid = $this->db->insert($sql); 
				}catch(CustomException $e){
					throw new CustomException($e->queryError($sql));
				}
				
				$sql = "INSERT INTO question_array (question_id, question_row, row_label, question_column, column_label) VALUES";
				
				foreach($val->rows AS $rowkey=>$row){
					foreach($val->columns AS $colkey=>$column){
						$sqlArray[] = "(".$id.",".$rowkey.",'".$row."',".$colkey.",'".$column."')";
					}
				}
				
				$sql = $sql.implode(',', $sqlArray);
				
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
	
	protected function saveSingle($id, $val){
		try{
				$this->db_connect->beginTransaction();
				
				$sql = "INSERT INTO question ( question_label, question_type, question_options) VALUES ('".$val->title."','".$val->type."','".serialize($val->options)."');"; 
				
				try{
					$pid = $this->db->insert($sql); 
				}catch(CustomException $e){
					throw new CustomException($e->queryError($sql));
				}
				
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
	
	protected function saveMulti($id, $val){
		try{
				$this->db_connect->beginTransaction();
				
				$sql = "INSERT INTO question ( question_label, question_type, question_options) VALUES ( '".$val->title."','".$val->type."' ,'".serialize($val->options)."');"; 
				
				try{
					$pid = $this->db->insert($sql); 
				}catch(CustomException $e){
					throw new CustomException($e->queryError($sql));
				}
				
				$sql = "INSERT INTO question_multi (question_id, question_row, row_label) VALUES";
				
				foreach($val->rows AS $rowkey=>$row){
					$sqlArray[] = "(".$id.",".$rowkey.",'".$row."')";
				}
				
				$sql = $sql.implode(',', $sqlArray);
				
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
}