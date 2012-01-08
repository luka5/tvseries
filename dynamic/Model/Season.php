<?php

class Model_Season extends Model_Abstract {

	/* Fields */
	public $id;
	public $idSerial;
	public $number;
	public $title;
	
	public function __construct(){
		// nothing to do
	}

	/**
	 *
	 * @return Integer
	 */
	public function getId(){
		return $this->id;
	}

	/**
	*
	* @param Integer $id
	*/
	public function setId($id){
		$this->id = $id;
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
	* @return Model_Serial
	*/
	public function getSerial(){
		return Factory_Serial::getById($this->getIdSerial());
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
	* @return Integer
	*/
	public function getNumber(){
		return $this->number;
	}
	
	/**
	 *
	 * @param Integer $number
	 */
	public function setNumber($number){
		$this->number = $number;
	}	
	
	/**
	 *
	 * @return String
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	*
	* @param String $title
	*/
	public function setTitle($title){
		$this->title = $title;
	}	
	
	/**
	*
	* @return Model_Episode[]
	*/
	public function getEpisodes(){
		return Factory_Episode::getByFields(array('idSeason' => $this->getId()));
	}	
	
}

?>