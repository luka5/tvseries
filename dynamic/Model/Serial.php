<?php

class Model_Serial extends Model_Abstract {

	/* Fields */
	public $id;
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
	* @return Model_Season[]
	*/
	public function getSeasons(){
		return Factory_Season::getByFields(array('idSearial' => $this->getId()));
	}
	
}

?>