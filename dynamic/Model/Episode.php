<?php

class Model_Episode extends Model_Abstract {

	/* Fields */
	public $id;
	public $idSeason;
	public $number;
	public $title;
	public $originalTitle;
	public $about;
	public $ranking;
	public $availability;
	public $premier;
	public $originalPremier;	

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
	public function getIdSeason(){
		return $this->idSeason;
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
	 * @param Integer $idSeason
	 */
	public function setIdSeason($idSeason){
		$this->idSeason = $idSeason;
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
	* @return String
	*/
	public function getOriginalTitle(){
		return $this->originalTitle;
	}
	
	/**
	*
	* @param String $originalTitle
	*/
	public function setOriginalTitle($originalTitle){
		$this->originalTitle = $originalTitle;
	}	
	
	/**
	*
	* @return String
	*/
	public function getAbout(){
		return $this->about;
	}
	
	/**
	 *
	 * @param String $about
	 */
	public function setAbout($about){
		$this->about = $about;
	}
	
	/**
	*
	* @return Integer
	*/
	public function getRanking(){
		return $this->ranking;
	}
	
	/**
	 *
	 * @param Integer $ranking
	 */
	public function setRanking($ranking){
		$this->ranking = $ranking;
	}	
	
	/**
	*
	* @return String
	*/
	public function getAvailability(){
		return $this->availability;
	}
	
	/**
	 *
	 * @param String $availability
	 */
	public function setAvailability($availability){
		$this->availability = $availability;
	}	
	
	/**
	*
	* @return String
	*/
	public function getPremier(){
		return $this->premier;
	}
	
	/**
	 *
	 * @param String $premier
	 */
	public function setPremier($premier){
		$premier = parent::convertDate($premier);
		$this->premier = $premier;
	}	
	
	/**
	*
	* @return String
	*/
	public function getOriginalPremier(){
		return $this->originalPremier;
	}
	
	/**
	 *
	 * @param String $premier
	 */
	public function setOriginalPremier($originalPremier){
		$originalPremier = parent::convertDate($originalPremier);
		$this->originalPremier = $originalPremier;
	}
	
}

?>