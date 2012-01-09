<?php
class Call_GetEpisodes extends Call_Abstract{

	public function __construct(){
		parent::__construct();
	}
	
	public function handle(){
		try{
			//Episoden einer Staffel anzeigen
			$idSeason = getRequestVar("idSeason");
			$episodes = Factory_Episode::getByFields(array("idSeason" => $idSeason));
			
		}catch(Exception_IndexNotFound $e){
			//alle Episoden einer Serie anzeigen
			$idSerial = getRequestVar("idSerial");
			$seasons = Factory_Season::getByFields(array("idSerial" => $idSerial));
			
			$episodes = array();
			foreach($seasons as $season){
				$episodes = array_merge($episodes, Factory_Episode::getByFields(array("idSeason" => $season->getId())));
			}
		}
		parent::encodeAndPrint($episodes);		
	}
}
?>