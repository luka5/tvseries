<?php

	class Factory_Season extends Factory_Abstract{

		/* Fields 
		private $id;
		private $idSerial;
		private $number;		
		private $title;	*/

		private static $models = array();

		/**
		 * 
		 * @param Model_Season $model
		 */
		public static function createNew(Model_Season $model){
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
		 * @return Model_Season
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
		 * @return Model_Season[]
		 */
		public static function getByFields($values, $sortBy = NULL){
                    
			$query = "SELECT * FROM Season " . parent::getWhereQuery($values) . " " . parent::getSortQuery($sortBy);
			$result = Database::getInstance()->executeQuery($query);
			if($result === false){
				throw new Exception("Fehler beim Abfragen der Staffeln.".$query);
			}
			
			$models = array();
			foreach($result as $resultItem){
				if(!isset(self::$models[$resultItem['id']])){
					// Noch kein Model vorhanden, erzeuge neues
					$model = new Model_Season();
					$model->setId($resultItem['id']);
					$model->setIdSerial($resultItem['idSerial']);
					$model->setNumber($resultItem['number']);
					$model->setTitle($resultItem['title']);

					self::$models[$model->getId()] = $model;
				}
				$models[] = self::$models[$resultItem['id']];
			}
			return $models;
		}

		/**
		 * 
		 * @param Model_Season $model
		 */
		public static function store(Model_Season $model){
			if($model->getId() == null){
				$query = "INSERT INTO Season SET ";
				$query .= "idSerial = " . $model->getIdSerial() . ", ";
				$query .= "number = " . $model->getNumber() . ", ";
				$query .= "title = '" . $model->getTitle() . "' ";
				
				$result = Database::getInstance()->executeUpdate($query);
				if($result === false)
					throw new Exception("Fehler beim Anlegen der Staffel.");
			}else
				throw new Exception("Aktualisieren einer Staffel noch nicht implementiert.");
		}
			
	}
?>
