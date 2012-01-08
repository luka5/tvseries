<?php

	abstract class Factory_Abstract{
		public static function createNew(){}
		public static function getById($id){}
		public static function store($model){}

		protected static function getFieldQuery($values){
			$query = "";
			foreach($values as $key=>$value){
				if(!empty($query))
					$query .= " AND ";
					
				if($value === null)
					$query .= " " . $key . " IS NULL ";
				else if(gettype($value) == "string")
					$query .= " " . $key . " LIKE '" . $value . "' ";
				else
					$query .= " " . $key . " = '" . $value . "' ";
			}			
			return $query;
		}
		
	}

?>
