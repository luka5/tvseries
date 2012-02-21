<?php
class Call_GetSerials extends Call_Abstract{

	public function __construct(){
		parent::__construct();
	}
	
	public function handle(){
                $sort = array("title" => "ASC");
		$serials = Factory_Serial::getByFields(null, $sort);
		parent::encodeAndPrint($serials);	
	}
}
?>