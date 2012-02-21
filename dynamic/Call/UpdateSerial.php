<?php

class Call_UpdateSerial extends Call_Abstract {

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
				$title = getRequestVar("title");

				$this->add($title);
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

	public function add($title) {
		$model = new Model_Serial();
		$model->setTitle($title);

		//insert $model into database
		Factory_Serial::store($model);
	}

}

?>
