<?php

	class Factory_Ftppush extends Factory_Abstract{

		/* Fields FALSCH!!
			public $id;
			public $idSerial;
			public $idEpisode;
			public $time;
			public $channel;
			public $title;	 */

		private static $models = array();

		/**
		 * 
		 * @param Model_Ftppush $model
		 */
		public static function store(Model_Ftppush $model){
			if($model->getId() == null){
				// Model hat keine ID, schreibe in Datenbank
				$query = "INSERT INTO Ftppush SET ";
				$query .= "idBroadcastTime = " . $model->getIdBroadcastTime() . ", ";
				$query .= "filename = '" . $model->getFilename() . "', ";
				$query .= "filesize = " . $model->getFilesize() . ", ";
				$query .= "isCut = " . ($model->isCut()?1:0) . ", ";
				$query .= "isDecoded = " . ($model->isDecoded()?1:0) . ", ";
				$query .= "isHQ = " . ($model->isHQ()?1:0) . " ";
		}else{
				// Model hat ID, aktualisiere in Datenbank
				$query = "UPDATE Ftppush SET ";
				$query .= "idBroadcastTime = " . $model->getIdBroadcastTime() . ", ";
				$query .= "filename = '" . $model->getFilename() . "', ";
				$query .= "filesize = " . $model->getFilesize() . ", ";
				$query .= "isCut = " . ($model->isCut()?1:0) . ", ";
				$query .= "isDecoded = " . ($model->isDecoded()?1:0) . ", ";
				$query .= "isHQ = " . ($model->isHQ()?1:0) . " ";				
				$query .= "WHERE id = " . $model->getId();
			}
			
			$result = Database::getInstance()->executeUpdate($query);
			if($result === false){
				throw new Exception("Fehler beim Anlegen des Ftppush.");
			}
		}

		/**
		 * 
		 * @param Integer $id
		 * @return Model_BroadcastTime
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
		 * @return Model_Ftppush[]
		 */
		public static function getByFields($values, $sortBy = NULL){

			$query = "SELECT * FROM Ftppush " . parent::getWhereQuery($values) . " " . parent::getSortQuery($sortBy);
			$result = Database::getInstance()->executeQuery($query);

			$models = array();
			foreach($result as $resultItem){
				if(!isset(self::$models[$resultItem['id']])){
					// Noch kein Model vorhanden, erzeuge neues
					$model = new Model_Ftppush();
					
					$model->setId($resultItem['id']);
					$model->setIdBroadcastTime($resultItem['idBroadcastTime']);
					$model->setFilename($resultItem['filename']);
					$model->setFilesize($resultItem['filesize']);
					$model->setCut($resultItem['isCut']);
					$model->setDecoded($resultItem['isDecoded']);
					$model->setHQ($resultItem['isHQ']);

					self::$models[$model->getId()] = $model;
				}
				$models[] = self::$models[$resultItem['id']];
			}
			return $models;
		}
			
	}
?>
