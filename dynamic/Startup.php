<?php

class Startup {

	private $callName;

	private $configArray;

    public function __construct() {
	try{
		$this->configArray = parse_ini_file("config.ini", true);
		Database::createInstance($this->configArray['db']);
		Session::createInstance();

		$this->callName = forceGetRequestVar('callName');
		if($this->callName == null)
			//for use with php_cli
			$this->callName = $_SERVER['argv'][1];
		
		// Initialisiere Call mit $this->callName
		$callClass = new ReflectionClass("Call_" . $this->callName);
		$call = call_user_func_array(array(&$callClass, 'newInstance'), array());
		$call->handle();
	}catch(Exception $e){
		echo json_encode(array(
			"success" => false,
			"errorInfo" => $e->getMessage()
		));
	}
    }

}
?>
