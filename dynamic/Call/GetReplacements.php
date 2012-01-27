<?php
class Call_GetReplacements extends Call_Abstract{

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
		$replacements = Factory_Replacements::getByFields($data);
		
		echo parent::encodeAndPrint($replacements);
	}
}
?>