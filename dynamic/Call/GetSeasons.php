<?php
class Call_GetSeasons extends Call_Abstract{

	public function __construct(){

	}
	
	public function handle(){
		try{
			$idSerial = getRequestVar("idSerial");
			$data = array("idSerial" => $idSerial);
		}catch(Exception_IndexNotFound $e){
			$data = null;
		}
		$seasons = Factory_Season::getByFields($data);
		
		echo parent::encodeAndPrint($seasons);	
	}
}
?>