<?php
class Call_GetSeasons extends Call_Abstract{

	public function __construct(){
		parent::__construct();
	}
	
	public function handle(){
		try{
			$idSerial = getRequestVar("idSerial");
			$data = array("idSerial" => $idSerial);
		}catch(Exception_IndexNotFound $e){
			$data = null;
		}
                $sort = array("number" => "ASC");
		$seasons = Factory_Season::getByFields($data, $sort);
		
		echo parent::encodeAndPrint($seasons);	
	}
}
?>