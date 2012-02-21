<?php
class Call_GetEpisodes extends Call_Abstract{

	public function __construct(){
		parent::__construct();
	}
	
	public function handle(){
		try{
			//Episoden einer Staffel anzeigen
			$idSeason = getRequestVar("idSeason");
                        $sort = array("number" => "ASC");
			$episodes = Factory_Episode::getByFields(array("idSeason" => $idSeason), $sort);
			
		}catch(Exception_IndexNotFound $e){
			//alle Episoden einer Serie anzeigen
                        $episodes = array();
		}
		parent::encodeAndPrint($episodes);		
	}
}
?>