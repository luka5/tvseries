<?php

class Call_UpdateEpisodes extends Call_Abstract {

	public function __construct() {
		parent::__construct();
	}

	public function handle() {
		$resultObj = array(
			"success" => true
		);
		try {
			$action = getRequestVar("action");
			if ($action == "updateavailability") {
				//Episoden anhand von idEpisode raussuchen, availability aendern und speichern
				$idEpisode = getRequestVar("idEpisode");
				$availability = getRequestVar("availability");
				$episodes = Factory_Episode::getById($idEpisode);

				$episodes->setAvailability($availability);
				Factory_Episode::store($episodes);
			}if ($action == "updateoradd") {
				//example url: view-source:http://localhost:8081/tvseries/dynamic/?callName=UpdateEpisodes&action=updateoradd&data=[{%22idSeason%22:%2250%22,%22number%22:%2221%22,%22originalTitle%22:%22Do%20You%20See%20What%20I%20See%22,%22premier%22:%2212.12.2012%22}]&allocate={%22originalTitle%22:%22originalTitle%22,%22premier%22:%22premier%22,%22idSeason%22:%22idSeason%22,%22number%22:%22number%22}
				$data = json_decode(getRequestVar("data"), true);
				$idSeason = getRequestVar("idSeason");

				$this->updateOrAdd($data, $idSeason);
			} else {
				throw new Exception("Fehlender Parameter 'action'.");
			}
		} catch (Exception $e) {
			$resultObj = array(
				"success" => false,
				"errorInfo" => $e->getMessage()
			);
		}
		parent::encodeAndPrint($resultObj);
	}

	public function updateOrAdd($data, $idSeason) {
		foreach ($data as $item) {
			$model = new Model_Episode();
			$model->setIdSeason($idSeason);

			//add fields to Model_Episode()
			foreach ($item as $key => $value) {
				if ($key == "unused")
					continue;
				
				$methodname = "set" . ucfirst($key);
				$method = new ReflectionMethod('Model_Episode', $methodname);
				$method->invoke($model, $value);
			}

			$existingEpisode = Factory_Episode::getByFields(array(
						"idSeason" => $model->getIdSeason(),
						"number" => $model->getNumber()
					));
			if (count($existingEpisode) > 0)
				$model->setId($existingEpisode[0]->getId());

			//insert $model into database OR update database
			Factory_Episode::store($model);
		}
	}

}

?>
