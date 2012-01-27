<?php

	class Factory_Replacements extends Factory_Abstract{

		/* Fields 
		private $idSerial;
		private $replace;
		private $replacewith;	*/

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
		 * @return Model_Replacements
		 */
		public static function getById($id){
			if(!isset(self::$models[$id])){
				// hole aus Datenbank
				$results = self::getByFields(array('idSerial' => $id));
			}
			return self::$models[$id];
		}

		/**
		 * 
		 * @param array $values Array with (Fieldname => Fieldvalue, ...) that will be queried.
		 * @return Model_Replacements[]
		 */
		public static function getByFields($values){
			$query = "";
			if($values !== NULL)
				$query = "WHERE " . parent::getFieldQuery($values);
			$query = "SELECT * FROM Replacements " . $query;
			$result = Database::getInstance()->executeQuery($query);
			if($result === false){
				throw new Exception("Fehler beim Abfragen der Replacements.".$query);	
			}
			
			$models = array();
			foreach($result as $resultItem){
				if(!isset(self::$models[$resultItem['idSerial']])){
					// Noch kein Model vorhanden, erzeuge neues
					$model = new Model_Replacements();
					$model->setIdSerial($resultItem['idSerial']);
					$model->setReplace($resultItem['replace']);
					$model->setReplacewith($resultItem['replacewith']);

					self::$models[$model->getIdSerial()] = $model;
				}
				$models[] = self::$models[$resultItem['idSerial']];
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
