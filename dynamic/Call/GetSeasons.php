<?php
class Call_GetSeasons extends Call_Abstract{

	public function __construct(){
		parent::__construct();
	}
	
	public function handle(){
		try{
			$idSerial = getRequestVar("idSerial");
			$title = forceGetRequestVar("title");
			$data = array(
				"idSerial" => $idSerial,
				"title" => "%".$title."%"
			);
		}catch(Exception_IndexNotFound $e){
			$data = null;
		}
                $sort = array("number" => "ASC");
		$seasons = Factory_Season::getByFields($data, $sort);
		
		echo parent::encodeAndPrint($seasons);	
	}
}
?>