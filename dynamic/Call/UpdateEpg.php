<?php
class Call_UpdateEpg extends Call_Abstract{

	private $epgIndices = array(
		"id" => 0,
		"beginn" => 1,
		"ende" => 2,
		"dauer" => 3,
		"sender" => 4,
		"titel" => 5,
		"typ" => 6,
		"text" => 7,
		"genre_id" => 8,
		"fsk" => 9,
		"language" => 10,
		"weekday" => 11,
		"zusatz" => 12,
		"wdh" => 13,
		"downloadlink" => 14,
		"infolink" => 15,
		"programlink" => 16
	);
	private $epgdir = "../media/epg/";
	//private $epgdir = "../../tvseriesmedia/epg/";
 	private $allserials = null;
				 
	public function __construct(){

	}

	public function handle(){
		//get request vars
		$action = getRequestVar("1");
		if(isset($_REQUEST["2"])){
			$dateFrom = getRequestVar("2");
		}else{
			//kein Datum übergeben: wähle heutigen Tag
			$dateFrom = date("d.m.Y");
		}
		$dateFrom = getRequestVar("2");
		if(isset($_REQUEST["3"])){
			$dateTo = getRequestVar("3");	
		}else{
			$dateTo = $dateFrom;
		}

		echo "Starting with action " . $action . " (" . date("d.m.Y H:i") . ")\n\n";

		$time1 = microtime(true);

		try{
			switch($action){
				case "all":
					$this->downloadEpgfiles($dateFrom, $dateTo);
					$this->importEpgfiles();
					break;

				case "importEpgfile":
					$this->importEpgfiles();
					break;

				default:
					echo "all | importEpgfile";
			}
		}catch(Exception $e){
			echo "\n\nForced Finish. ".$e->getMessage();
		}

		echo "\n\n Finished. (" . (microtime(true)-$time1) . "s, " . date("d.m.Y H:i") . ").";
	}

	/*
	 * 
	 * Lädt alle csv Dateien von $dateFrom bis $dateTo von onlinetvrecorder runter
	 */
	private function downloadEpgfiles($dateFrom, $dateTo){
		
		$from = new DateTime($dateFrom);
		$to = new DateTime($dateTo);
		
		while($from <= $to){
			$sourcefile = "http://www.onlinetvrecorder.com/epg/csv/epg_" . $from->format('Y_m_d') . ".csv";
			$targetfile = $this->epgdir . "epg_" . $from->format('Y_m_d') . ".csv";
			$this->downloadfile($sourcefile, $targetfile);
			
			date_add($from, date_interval_create_from_date_string('1 days'));
		}
		
	}
	
	private function importEpgfiles(){
		//Gehe alle Dateien des $epgdir ordners durch und rufe importEpgfile auf.
		$time1 = microtime(true);
		echo "Start importing epgfile " . date("d.m.Y H:i") . "\n";

		//wechsle in KB-Daten-Pfad
		if($handle = opendir($this->epgdir)){
			while (false !== ($sourcefile = readdir($handle))) {
				if(strpos($sourcefile, ".csv") !== false && strpos($sourcefile, ".csv") == strlen($sourcefile)-4){
					//check file
					$this->importEpgfile($sourcefile);
					
					//delete file
					//unlink($sourcefile);
				}
			}
			closedir($handle);
		}
		
		echo "\nfinished. duration: " . (microtime(true)-$time1) . "s\n";
	}

	private function importEpgfile($sourcefile){
		/*
		 * Struktur
		 * Id;beginn;ende;dauer;sender;titel;typ;text;genre_id;fsk;language;weekday;zusatz;wdh;downloadlink;infolink;programlink;
		 */

		echo "Reading input file ".$sourcefile." \n";
        
		// open $sourcefile
        $sourcehandle = fopen($this->epgdir . $sourcefile, "rb");
        if(!$sourcehandle)
            throw new Exception("File " . $sourcefile . " could not be opened");
		
		$lineCounter = 0;
		// Datei Zeile für Zeile durchgehen
		while( !feof($sourcehandle)) {
			$lineCounter++;

			echo ".";
			if($lineCounter%4000 == 0)
			echo "\n";

			$line = fgets($sourcehandle, 1024);
			$line = utf8_encode($line);
			$values = explode(";", $line);

			// Prüfe Gültigkeit der eingelesenen Zeile
			if(count($values) < 17 || $values[$this->epgIndices['id']] == "Id"){
				echo "!";
				continue;
			}
				
			//arbeite mit inhalt.
			$this->checkRow($values);
		}
	}

	private function checkRow($values){
		//ueberpruefe eintrag
		
		$title = $values[$this->epgIndices['titel']];
		
		$episodeText = $values[$this->epgIndices['text']];
		$episodeTitles = explode(",", $episodeText);
		
		$channel = $values[$this->epgIndices['sender']];
		
		$time = $values[$this->epgIndices['beginn']];
		$tmptime = new DateTime($time);
		$time = $tmptime->format('Y-m-d H:i:s');
		
		foreach($episodeTitles as $episodeTitle){
			//problem title steht mehreres mit Komma getrennt!
			$episodeTitle = trim($episodeTitle);
			$episodes = Factory_Episode::getByFields(array("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`title`, 'ä', 'ae'), 'ö', 'oe'), 'ü', 'ue'), 'Ä', 'Ae'), 'Ö', 'Oe'), 'Ü', 'Ue'), 'ß', 'ss'), '-', ' '), ',', ''),' ','')" => "%".str_replace(" ", "", $episodeTitle)."%"));

			if(count($episodes) > 0){
				//eintraege gefunden suche passende serial oder season
				foreach($episodes as $episode){
					//ueberpruefe ob serial oder season zum aktuellen passt
					$season = Factory_Season::getByFields(array("id" => $episode->getIdSeason()));
					$season = $season[0];
					$serial = Factory_Serial::getByFields(array("id" => $season->getIdSerial()));
					$serial = $serial[0];
					if($this->otrEquals($season->getTitle(), $title)){
						//season passt
						echo ":";
						//echo "\nPassendes gefunden!(1)" . $title . " - " . $episodeTitle;
						$this->newBroadcastTime($serial, $episode, $time, $channel, $episodeTitle);
						break;
					}else if($this->otrEquals($serial->getTitle(), $title)){
						//serial passt
						echo ":";
						//echo "\nPassendes gefunden!(2)" . $title . " - " . $episodeTitle;
						$this->newBroadcastTime($serial, $episode, $time, $channel, $episodeTitle);
						break;
					}else{
						//episode gefunden aber sonst nichts. irgnorieren.
					}
				}
			}else{
				//schaue ob serial bekannt
				//episode noch nicht angelegt oder "tippfehler"
				if($this->allserials == null)
					$this->allserials = Factory_Serial::getByFields(null);
				foreach($this->allserials as $serial){
				 	if($this->otrEquals($serial->getTitle(), $title)){
				 		//serial passt
				 		//TODO: hier kommen auch folgende Strings an: Zeichentrickserie USA 2004 20 min
				 		//SHIT!
				 		echo "\nPassendes gefunden!(3)" . $title . " - " . $episodeTitle;
				 		break;
				 	} 
				}
			}
		}

	}
	
	private function otrEquals($dbText, $csvText){
		//in csvText steht statt ü ue
		//in dbText aus der DB natürlich nicht!
		$replaceSearch = array('ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß', '-', ',', ' ', '.');
		$replaceReplace = array('ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss', ' ', '', '', '');
		$dbText = str_replace($replaceSearch, $replaceReplace, $dbText);
		$csvText = str_replace($replaceSearch, $replaceReplace, $csvText);
		
		if($dbText == $csvText)
			return true;
		return false;
	}
	
	private function newBroadcastTime($serial, $episode, $time, $channel, $title){
		//erzeuge neues BrowadcastTime Model
		echo "<br />Found: ".$serial->getTitle(). " ".$episode->getTitle() . "<br />";
		/*$bt = new Model_BroadcastTime();
		$bt->setIdSerial($serial->getId());
		$bt->setIdEpisode($episode->getId());
		$bt->setTime($time);
		$bt->setChannel($channel);
		$bt->setTitle($title);
		
		Factory_BroadcastTime::createNew($bt);*/
	}
	
	private function downloadfile($file_source, $file_target) {
		
        $rh = fopen($file_source, 'rb');
        $wh = fopen($file_target, 'wb');
        if ($rh===false || $wh===false) {
			// error reading or opening file
           return true;
        }
        while (!feof($rh)) {
            if (fwrite($wh, fread($rh, 1024)) === FALSE) {
                   // 'Download error: Cannot write to file ('.$file_target.')';
                   return true;
               }
        }
        fclose($rh);
        fclose($wh);
        // No error
        return false;
    }
}
?>