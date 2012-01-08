<?php

class Model_BroadcastTime extends Model_Abstract {

	/* Fields */
	public $id;
	public $idSerial;
	public $idEpisode;
	public $time;
	public $channel;
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
	 * @param Integer $idSeason
	 */
	public function setIdSerial($idSerial){
		$this->idSerial = $idSerial;
	}	
	
	/**
	*
	* @return Model_Season
	*/
	public function getSeason(){
		return Factory_Season::getById($this->getIdSeason());
	}	
	
	/**
	*
	* @return Integer
	*/
	public function getIdEpisode(){
		return $this->idEpisode;
	}
	
	/**
	 *
	 * @param Integer $idSeason
	 */
	public function setIdEpisode($idEpisode){
		$this->idEpisode = $idEpisode;
	}	
	
	/**
	*
	* @return Integer
	*/
	public function getTime(){
		return $this->time;
	}
	
	/**
	 *
	 * @param Integer $number
	 */
	public function setTime($time){
		$this->time = $time;
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
	* @return String
	*/
	public function getChannel(){
		return $this->channel;
	}
	
	/**
	*
	* @param String $channel
	*/
	public function setChannel($channel){
		$this->channel = $channel;
	}	
	
}

?>