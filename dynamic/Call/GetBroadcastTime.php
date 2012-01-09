<?php
class Call_GetBroadcastTime extends Call_Abstract{

	public function __construct(){
		parent::__construct();
	}
	
	public function handle(){
		$broadcasttimes = "";
		try{
			//BroadcastTime zu einer idEdpisode anzeigen
			$idEpisode = getRequestVar("idEpisode");
			$broadcasttimes = Factory_BroadcastTime::getByFields(array("idEpisode" => $idEpisode));
			
		}catch(Exception_IndexNotFound $e){

		}
		if($broadcasttimes == ""){
			$broadcasttimes = array("success" => false, "errorInfo" => "Keine Daten!");
		}
		parent::encodeAndPrint($broadcasttimes);		
	}
}
?>