<?php

	class Factory_User extends Factory_Abstract{

		/* Fields 
		private $id;
		private $username;	
		private $secret;	*/

		private static $models = array();

		/**
		 * 
		 * @param Model_User $model
		 */
		public static function createNew(Model_Serial $model){
		}

		/**
		 * 
		 * @param Integer $id
		 * @return Model_User
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
		 * @return Model_User[]
		 */
		public static function getByFields($values){
			$query = "";
			if($values !== NULL)
				$query = "WHERE " . parent::getFieldQuery($values);
			$query = "SELECT * FROM User " . $query;
			$result = Database::getInstance()->executeQuery($query);
			if($result === false){
				throw new Exception("Fehler beim Abfragen der Benutzerdaten.");	
			}
			
			$models = array();
			foreach($result as $resultItem){
				if(!isset(self::$models[$resultItem['id']])){
					// Noch kein Model vorhanden, erzeuge neues
					$model = new Model_User();
					$model->setId($resultItem['id']);
					$model->setUsername($resultItem['username']);
					$model->setSecret($resultItem['secret']);

					self::$models[$model->getId()] = $model;
				}
				$models[] = self::$models[$resultItem['id']];
			}
			return $models;
		}

		/**
		 * 
		 * @param Model_User $model
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
