<?php

	class Factory_Episode extends Factory_Abstract{

		/* Fields 
		private $id;
		private $idSeason;
		private $number;
		private $title;
		private $originalTitle;
		private $about;
		private $ranking;
		private $availability;
		private $premier;
		private $originalPremier; */

		private static $models = array();

		/**
		 * 
		 * @param Model_Episode $model
		 */
		public static function createNew(Model_Episode $model){
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
		 * @return Model_Episode
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
		 * @return Model_Episode[]
		 */
		public static function getByFields($values, $sortBy = NULL){

			$query = "SELECT * FROM Episode " . parent::getWhereQuery($values) . " " . parent::getSortQuery($sortBy);
			$result = Database::getInstance()->executeQuery($query);
			if($result === false){
				throw new Exception("Fehler beim Abfragen der Episoden.".$query);	
			}

			$models = array();
			foreach($result as $resultItem){
				if(!isset(self::$models[$resultItem['id']])){
					// Noch kein Model vorhanden, erzeuge neues
					$model = new Model_Episode();
					$model->setId($resultItem['id']);
					$model->setIdSeason($resultItem['idSeason']);
					$model->setNumber($resultItem['number']);
					$model->setTitle($resultItem['title']);
					$model->setOriginalTitle($resultItem['originalTitle']);
					$model->setAbout($resultItem['about']);
					$model->setRanking($resultItem['ranking']);
					$model->setAvailability($resultItem['availability']);
					$model->setPremier($resultItem['premier']);
					$model->setOriginalPremier($resultItem['originalPremier']);

					self::$models[$model->getId()] = $model;
				}
				$models[] = self::$models[$resultItem['id']];
			}
			return $models;
		}

		/**
		 * 
		 * @param Model_Episode $model
		 */
		public static function store(Model_Episode $model){
			
			$fields = "";
			if($model->getIdSeason() !== null && $model->getIdSeason() != "")
				$fields .= "idSeason = " . $model->getIdSeason() . ", ";
			if($model->getNumber() !== null && $model->getNumber() != "")
				$fields .= "number = " . $model->getNumber() . ", ";
			$fields .= "title = \"" . $model->getTitle() . "\", ";
			$fields .= "originalTitle = \"" . $model->getOriginalTitle() . "\",  ";
			$fields .= "about = \"" . str_replace("\"","\\\"", $model->getAbout()) . "\", ";
			if($model->getRanking() !== null && $model->getRanking() != "")
				$fields .= "ranking = " . $model->getRanking() . ", ";
			if($model->getAvailability() !== null && $model->getAvailability() != "")
				$fields .= "availability = " . $model->getAvailability() . ", ";
			$fields .= "premier = '" . $model->getPremier() . "', ";
			$fields .= "originalPremier = '" . $model->getOriginalPremier() . "' ";

			if(isset(self::$models[$model->getId()])){
				// Model bekannt, schreibe in Datenbank
				$query = "UPDATE Episode SET ";
				$query .= $fields;
				$query .= "WHERE id = " . $model->getId();
				
				$result = Database::getInstance()->executeUpdate($query);
				if($result === false)
					throw new Exception("Error Updating Episode (".$model->getId().")" . $query);
			}else{
				//erzeiche neuen DB Eintrag
				$query = "INSERT INTO Episode SET ";
				$query .= $fields;

				$result = Database::getInstance()->executeUpdate($query);
				if($result === false){
					throw new Exception("Fehler beim Anlegen der Episode.".$query);
				}

			}		
		}
	}
?>
