<?php
class Call_UpdateEpisodes extends Call_Abstract{

	public function __construct(){

	}
	
	public function handle(){
		$resultObj = array(
		    "success" => true
		);
		try{
			//Episoden anhand von idEpisode raussuchen, availability aendern und speichern
			$idEpisode = getRequestVar("idEpisode");
			$availability = getRequestVar("availability");
			$episodes = Factory_Episode::getByFields(array("id" => $idEpisode));
				
			$episodes[0]->setAvailability($availability);
			Factory_Episode::store($episodes[0]);
				
		}catch(Exception $e){
			$resultObj = array(
				"success" => false,
				"errorInfo" => $e->getMessage()
			);
		}
		parent::encodeAndPrint($resultObj);
	}
}
?>