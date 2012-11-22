<?php

	class Factory_Channel extends Factory_Abstract{

		private static $models = array();

		/**
		 * 
		 * @param Integer $id
		 * @return Model_Channel
		 */
		public static function getById($id){
			if(!isset(self::$models[$id])){
				// hole aus Datenbank
				$results = self::getByFields(array('id' => $id));
			}
			return self::$models[$id];
		}

		/**
		 * 
		 * @param array $values Array with (Fieldname => Fieldvalue, ...) that will be queried.
		 * @return Model_Channel[]
		 */
		public static function getByFields($values, $sortBy = NULL){

			$query = "SELECT * FROM Channel " . parent::getWhereQuery($values) . " " . parent::getSortQuery($sortBy);
			$result = Database::getInstance()->executeQuery($query);
			if($result === false){
				throw new Exception("Fehler beim Abfragen der Channel.".$query);	
			}

			$models = array();
			foreach($result as $resultItem){
				if(!isset(self::$models[$resultItem['id']])){
					// Noch kein Model vorhanden, erzeuge neues
					$model = new Model_Channel();
					$model->setId($resultItem['id']);
					$model->setName($resultItem['name']);
					$model->setBlocked($resultItem['isBlocked']);
					

					self::$models[$model->getId()] = $model;
				}
				$models[] = self::$models[$resultItem['id']];
			}
			return $models;
		}

		/**
		 * 
		 * @param Model_Channel $model
		 */
		public static function store(Model_Channel $model){
			
			$fields = "";
			if($model->getId() != null)
				$fields = "id = " . $model->getId() . ", ";
			$fields .= "name = '" . $model->getName() . "', ";
			$fields .= "isBlocked = " . ($model->isBlocked()?1:0) . " ";

			if(isset(self::$models[$model->getId()])){
				// Model bekannt, schreibe in Datenbank
				$query = "UPDATE Channel SET ";
				$query .= $fields;
				$query .= "WHERE id = " . $model->getId();
				
				$result = Database::getInstance()->executeUpdate($query);
				if($result === false)
					throw new Exception("Error Updating Channel (".$model->getId().")" . $query);
			}else{
				//erzeiche neuen DB Eintrag
				$query = "INSERT INTO Channel SET ";
				$query .= $fields;

				$result = Database::getInstance()->executeUpdate($query);
				if($result === false){
					throw new Exception("Fehler beim Anlegen der Channel.".$query);
				}

			}		
		}
	}
?>
