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
		public static function getByFields($values){
			$query = "";
			if($values !== NULL)
				$query = "WHERE " . parent::getFieldQuery($values);
			$query = "SELECT * FROM Episode " . $query;
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
			if(isset(self::$models[$model->getId()])){
				// Model bekannt, schreibe in Datenbank
				$query = "UPDATE Episode SET ";
				$query .= "idSeason = " . $model->getIdSeason() . ", ";
				$query .= "number = " . $model->getNumber() . ", ";
				$query .= "title = '" . $model->getTitle() . "', ";
				$query .= "originalTitle = '" . $model->getOriginalTitle() . "',  ";
				$query .= "about = '" . $model->getAbout() . "', ";
				$query .= "ranking = " . $model->getRanking() . ", ";
				$query .= "availability = " . $model->getAvailability() . ", ";
				$query .= "premier = '" . $model->getPremier() . "', ";
				$query .= "originalPremier = '" . $model->getOriginalPremier() . "' ";
				$query .= "WHERE id = " . $model->getId();
				
				$result = Database::getInstance()->executeUpdate($query);
				if($result === false)
					throw new Exception("Error Updating Episode (".$model->getId().")");
			}else
				throw new Exception("Unknown Model! Try using Factory::createNew(\$model)");
		}
			
	}
?>
