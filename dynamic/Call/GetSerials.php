<?php

class Call_GetSerials extends Call_Abstract {

	public function __construct() {
		parent::__construct();
	}

	public function handle() {
		$sort = array("title" => "ASC");
		
		$title = forceGetRequestVar("title");
		$serials = Factory_Serial::getByFields(array("title" => "%" . $title . "%"), $sort);
		parent::encodeAndPrint($serials);
	}

}

?>