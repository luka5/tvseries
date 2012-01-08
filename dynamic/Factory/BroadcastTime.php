<?php

	class Factory_BroadcastTime extends Factory_Abstract{

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
		 * @param Model_BroadcastTime $model
		 */
		public static function createNew(Model_BroadcastTime $model){
			if($model->getId() === null){
				// Model hat keine ID, schreibe in Datenbank
				$query = "INSERT INTO BroadcastTime SET ";
				$query .= "idSerial = " . $model->getIdSerial() . ", ";
				$query .= "idEpisode = " . $model->getIdEpisode() . ", ";
				$query .= "time = '" . $model->getTime() . "', ";
				$query .= "channel = '" . $model->getChannel() . "', ";
				$query .= "title = '" . $model->getTitle() . "' ";				
				$result = Database::getInstance()->executeUpdate($query);
				if($result === false){
					throw new Exception("Fehler beim Anlegen der BroadcastTime");
				}
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
		 * @return Model_BroadcastTime[]
		 */
		public static function getByFields($values){
			$query = "";
			if($values !== NULL)
				$query = "WHERE " . parent::getFieldQuery($values);
			$query = "SELECT * FROM BroadcastTime " . $query . " ORDER BY time DESC";
			$result = Database::getInstance()->executeQuery($query);

			$models = array();
			foreach($result as $resultItem){
				if(!isset(self::$models[$resultItem['id']])){
					// Noch kein Model vorhanden, erzeuge neues
					$model = new Model_BroadcastTime();
					
					$model->setId($resultItem['id']);
					$model->setIdSerial($resultItem['idSerial']);
					$model->setIdEpisode($resultItem['idEpisode']);
					$model->setTime($resultItem['time']);
					$model->setChannel($resultItem['channel']);
					$model->setTitle($resultItem['title']);

					self::$models[$model->getId()] = $model;
				}
				$models[] = self::$models[$resultItem['id']];
			}
			return $models;
		}

		/**
		 * 
		 * @param Model_BroadcastTime $model
		 */
		public static function store(Model_BroadcastTime $model){
			if(isset(self::$models[$model->getId()])){
				// Model bekannt, schreibe in Datenbank

			}else
				throw new Exception("Unknown Model! Try using Factory::createNew(\$model)");
		}
			
	}
?>
