<?php

class Model_Replacements extends Model_Abstract {

	/* Fields */
	public $idSerial;
	public $replace;
	public $replacewith;

	public function __construct(){
		// nothing to do
	}

	/**
	 *
	 * @return Integer
	 */
	public function getIdSerial(){
		return $this->idSerial;
	}

	/**
	*
	* @param Integer $idSerial
	*/
	public function setIdSerial($idSerial){
		$this->idSerial = $idSerial;
	}	
	
	/**
	 *
	 * @return String
	 */
	public function getReplace(){
		return $this->replace;
	}

	/**
	*
	* @param String $replace
	*/
	public function setReplace($replace){
		$this->replace = $replace;
	}	

		/**
	 *
	 * @return String
	 */
	public function getReplacewith(){
		return $this->replacewith;
	}

	/**
	*
	* @param String $with
	*/
	public function setReplacewith($replacewith){
		$this->replacewith = $replacewith;
	}	

	/**
	*
	* @return Model_Serial[]
	*/
	public function getSerial(){
		return Factory_Season::getByFields(array('idSearial' => $this->getIdSerial()));
	}
	
}

?>