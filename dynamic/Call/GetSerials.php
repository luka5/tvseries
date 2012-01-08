<?php
class Call_GetSerials extends Call_Abstract{

	public function __construct(){

	}
	
	public function handle(){
		$serials = Factory_Serial::getByFields(null);
		parent::encodeAndPrint($serials);	
	}
}
?>