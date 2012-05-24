<?php

class Session {

	private static $instance = null;

	/**
	 * Factory-Methode zum Erzeugen einer neuen Instanz
	 */
	public static function createInstance() {
		if (self::$instance != null)
			throw new Exception("cannot create instance, because it exists one.");

		self::$instance = new Session();
		return self::$instance;
	}

	/**
	 * @return Session
	 */
	public static function getInstance() {
		return self::$instance;
	}

	// Konstruktor nicht von extern erreichbar	
	private function __construct() {
		session_start();
	}

	public function __destruct() {

	}

	public function getVar($key) {
		if (isset($_SESSION[$key]))
			return $_SESSION[$key];
		else
			throw new Exception_IndexNotFound();
	}

	public function setVar($key, $value) {
		$_SESSION[$key] = $value;
	}
        
	public function destroy(){
		$_SESSION = array();
		session_destroy();
	}
}

?>
