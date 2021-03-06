<?php

class Model_Ftppush extends Model_Abstract {

	/* Fields */
	public $id;
	public $idBroadcastTime;
	public $ftppushId;
	public $filename;
	public $filesize;
	public $isCut;
	public $isDecoded;
	public $isHQ;
	public $isDeleted;

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
	public function getIdBroadcastTime(){
		return $this->idBroadcastTime;
	}
	
	/**
	 *
	 * @param Integer $idBroadcastTime
	 */
	public function setIdBroadcastTime($idBroadcastTime){
		$this->idBroadcastTime = $idBroadcastTime;
	}
	
	/**
	*
	* @return String
	*/
	public function getFtppushId(){
		return $this->ftppushId;
	}
	
	/**
	 *
	 * @param String $ftppushId
	 */
	public function setFtppushId($ftppushId){
		$this->ftppushId = $ftppushId;
	}
		
	/**
	*
	* @return String
	*/
	public function getFilename(){
		return $this->filename;
	}
	
	/**
	 *
	 * @param String $filename
	 */
	public function setFilename($filename){
		$this->filename= $filename;
	}	
	
	/**
	*
	* @return Integer
	*/
	public function getFilesize(){
		return $this->filesize;
	}
	
	/**
	 *
	 * @param Integer $filesize
	 */
	public function setFilesize($filesize){
		$this->filesize = $filesize;
	}
	
	/**
	*
	* @return Boolean
	*/
	public function isCut(){
		return $this->isCut;
	}
	
	/**
	 *
	 * @param Boolean $isCut
	 */
	public function setCut($isCut){
		$this->isCut = $isCut;
	}
	
	/**
	*
	* @return Boolean
	*/
	public function isDecoded(){
		return $this->isDecoded;
	}
	
	/**
	 *
	 * @param Boolean $isDecoded
	 */
	public function setDecoded($isDecoded){
		$this->isDecoded = $isDecoded;
	}
	
	/**
	*
	* @return Boolean
	*/
	public function isHQ(){
		return $this->isHQ;
	}
	
	/**
	 *
	 * @param Boolean $isHQ
	 */
	public function setHQ($isHQ){
		$this->isHQ = $isHQ;
	}
	
	/**
	*
	* @return Boolean
	*/
	public function isDeleted(){
		return $this->isDeleted;
	}
	
	/**
	 *
	 * @param Boolean $isDeleted
	 */
	public function setDeleted($isDeleted){
		$this->isDeleted = $isDeleted;
	}
	
}

?>