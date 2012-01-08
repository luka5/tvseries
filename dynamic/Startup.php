<?php

class Startup {

	private $callName;

	private $configArray;

    public function __construct() {

		$this->configArray = parse_ini_file("config.ini", true);
//		Database::createInstance($this->configArray['db']);
		Session::createInstance();

		$this->callName = getRequestVar('callName');		
		
		// Initialisiere Call mit $this->callName
		$callClass = new ReflectionClass("Call_" . $this->callName);
		$call = call_user_func_array(array(&$callClass, 'newInstance'), array());
		$call->handle();

    }

}
?>
