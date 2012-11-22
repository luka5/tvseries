<?php

class Model_Channel extends Model_Abstract {

	/* Fields */
	public $id;
	public $name;
	public $isBlocked;

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
	 * @return String
	 */
	public function getName(){
		return $this->name;
	}

	/**
	*
	* @param String $name
	*/
	public function setName($name){
		$this->name = $name;
	}
	
	/**
	*
	* @return Boolean
	*/
	public function isBlocked(){
		return $this->isBlocked;
	}
	
	/**
	*
	* @param Boolean $isBlockeds
	*/
	public function setBlocked($isBlocked){
		$this->isBlocked = $isBlocked;
	}
	
}

?>