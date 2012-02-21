<?php

	class Factory_Serial extends Factory_Abstract{

		/* Fields 
		private $id;
		private $title;	*/

		private static $models = array();

		/**
		 * 
		 * @param Model_Serial $model
		 */
		public static function createNew(Model_Serial $model){
			if($model->getId() === null){
				// Model hat keine ID, schreibe in Datenbank
				/*$query = "INSERT INTO " . Database::getInstance()->getHostDatabase() . ".File SET ";
				$query .= "Folder_idFolder = " . $model->getFolder_id() . ", ";
				$query .= "Usergroup_idUsergroup = " . $model->getUsergroup_id() . ", ";
				$query .= "Filetemplate_idFiletemplate = " . $model->getFiletemplate_id() . ", ";
				$query .= "name = '" . $model->getName() . "', ";
				$query .= "title = '" . $model->getTitle() . "', ";
				$query .= "isDynamic = " . $model->isDynamic() . " ";				
				$result = Database::getInstance()->executeUpdate($query);*/		
			}
		}

		/**
		 * 
		 * @param Integer $id
		 * @return Model_Serial
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
		 * @return Model_Serial[]
		 */
		public static function getByFields($values, $sortBy = NULL){

			$query = "SELECT * FROM Serial " . parent::getWhereQuery($values) . " " . parent::getSortQuery($sortBy);
			$result = Database::getInstance()->executeQuery($query);
			if($result === false){
				throw new Exception("Fehler beim Abfragen der Serien.".$query);	
			}
			
			$models = array();
			foreach($result as $resultItem){
				if(!isset(self::$models[$resultItem['id']])){
					// Noch kein Model vorhanden, erzeuge neues
					$model = new Model_Serial();
					$model->setId($resultItem['id']);
					$model->setTitle($resultItem['title']);

					self::$models[$model->getId()] = $model;
				}
				$models[] = self::$models[$resultItem['id']];
			}
			return $models;
		}

		/**
		 * 
		 * @param Model_Serial $model
		 */
		public static function store(Model_Serial $model){
			if(isset(self::$models[$model->getId()])){
				// Model bekannt, schreibe in Datenbank
				/*$query = "UPDATE " . Database::getInstance()->getHostDatabase() . ".File SET ";
				$query .= "Folder_idFolder = " . $model->getFolder_id() . ", ";
				$query .= "Usergroup_idUsergroup = " . $model->getUsergroup_id() . ", ";
				$query .= "Filetemplate_idFiletemplate = " . $model->getFiletemplate_id() . ", ";
				$query .= "name = '" . $model->getName() . "', ";
				$query .= "title = '" . $model->getTitle() . "', ";
				$query .= "isDynamic = " . $model->isDynamic() . " ";
				$query .= "WHERE idFile = " . $model->getIdFile();
				$result = Database::getInstance()->executeUpdate($query);*/
			}else
				throw new Exception("Unknown Model! Try using Factory::createNew(\$model)");
		}
			
	}
?>
