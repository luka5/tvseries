<?php

class Call_UpdateSeason extends Call_Abstract {

	public function __construct() {
		parent::__construct();
	}

	public function handle() {
		$resultObj = array(
			"success" => true,
			"msg" => ""
		);
		try {
			$action = getRequestVar("action");
			if ($action == "add") {
				$idSerial = getRequestVar("idSerial");
				$title = getRequestVar("title");
				$number = getRequestVar("number");

				$this->add($idSerial, $title, $number);
			} else {
				throw new Exception("Fehlender Parameter 'action'.");
			}
		} catch (Exception $e) {
			$resultObj = array(
				"success" => false,
				"msg" => $e->getMessage()
			);
		}
		parent::encodeAndPrint($resultObj);
	}

	public function add($idSerial, $title, $number) {
		$model = new Model_Season();
		$model->setIdSerial($idSerial);
		$model->setTitle($title);
		$model->setNumber($number);

		//insert $model into database
		Factory_Season::store($model);
	}

}

?>
