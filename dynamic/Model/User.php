<?php

class Model_User extends Model_Abstract {

	/* Fields */
	public $id;
	public $username;
	public $secret;

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
	public function getUsername(){
		return $this->username;
	}

	/**
	*
	* @param String $username
	*/
	public function setUsername($username){
		$this->username = $username;
	}
	
	/**
	 *
	 * @return String
	 */
	public function getSecret(){
		return $this->secret;
	}

	/**
	*
	* @param String $secret
	*/
	public function setSecret($secret){
		$this->secret = $secret;
	}

}

?>