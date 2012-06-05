<?php

class Call_UpdateEpg extends Call_Abstract {

	private $epgIndices = array(
		"epgid" => 0,
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
	private $tmpdir = "../tmp/";
	private $allserials = null;
	private $checksum = null;
	private $configArray = null;

	public function __construct() {
		
	}

	public function handle() {
		$this->configArray = parse_ini_file("config.ini", true);

		//get request vars
		if (isset($_SERVER['argv'][2])) {
			$action = $_SERVER['argv'][2];
		} else {
			$action = "";
		}

		if (isset($_SERVER['argv'][3])) {
			$dateFrom = $_SERVER['argv'][3];
		} else {
			//kein Datum übergeben: wähle heutigen Tag
			$dateFrom = date("d.m.Y");
		}

		if (isset($_SERVER['argv'][4])) {
			$dateTo = $_SERVER['argv'][4];
		} else {
			$dateTo = $dateFrom;
		}

		if (!isset($_SERVER['argv'][3])) {
			//kein explizites Datum angegeben
			//wähle vortag für ftppush
			$dateFromFtp = date("d.m.Y", strtotime("-1 day"));
			$dateToFtp = $dateFromFtp;
		} else {
			$dateFromFtp = $dateFrom;
			$dateToFtp = $dateTo;
		}

		echo "Starting with action " . $action . " (" . date("d.m.Y H:i") . ")\n\n";

		$time1 = microtime(true);

		try {
			switch ($action) {
				case "all":
					$this->downloadEpgfiles($dateFrom, $dateTo);
					$this->importEpgfiles();
					$oldmapped = $this->recheck();
					$this->sendMail($dateFrom, $dateTo, $oldmapped);
					echo $this->addFtppushs($dateFromFtp, $dateToFtp, $oldmapped);
					$this->relocateFiles();
					break;

				case "import":
					$this->importEpgfiles();
					break;

				case "download":
					$this->downloadEpgfiles($dateFrom, $dateTo);
					break;

				case "mail":
					$this->sendMail($dateFrom, $dateTo);
					break;

				case "recheck":
					$this->recheck();
					break;

				case "ftppush":
					echo $this->addFtppushs($dateFromFtp, $dateToFtp);
					break;

				case "relocateFiles":
					$this->relocateFiles();
					break;

				default:
					throw new Exception("all | import | download | mail | recheck | ftppush | relocateFiles");
			}
		} catch (Exception $e) {
			echo "\n\nForced Finish. " . $e->getMessage();
		}

		echo "\n\n Finished. (" . (microtime(true) - $time1) . "s, " . date("d.m.Y H:i") . ").\n";
	}

	/*
	 * 
	 * Lädt alle csv Dateien von $dateFrom bis $dateTo von onlinetvrecorder runter
	 */

	private function downloadEpgfiles($dateFrom, $dateTo) {

		$from = new DateTime($dateFrom);
		$to = new DateTime($dateTo);

		while ($from <= $to) {
			$sourcefile = "http://www.onlinetvrecorder.com/epg/csv/epg_" . $from->format('Y_m_d') . ".csv";
			$targetfile = $this->tmpdir . "epg_" . $from->format('Y_m_d') . ".csv";
			$this->downloadfile($sourcefile, $targetfile);

			$year = $from->format('Y');
			$month = $from->format('m');
			$day = $from->format('d');
			$from->setDate($year, $month, $day + 1);
		}
	}

	private function importEpgfiles() {
		//Gehe alle Dateien des $tmpdir ordners durch und rufe importEpgfile auf.
		$time1 = microtime(true);
		echo "Start importing epgfile " . date("d.m.Y H:i") . "\n";

		//wechsle in KB-Daten-Pfad
		if ($handle = opendir($this->tmpdir)) {
			while (false !== ($sourcefile = readdir($handle))) {
				if (strpos($sourcefile, ".csv") !== false && strpos($sourcefile, ".csv") == strlen($sourcefile) - 4) {
					//check file
					$this->importEpgfile($sourcefile);

					//delete file
					unlink($this->tmpdir . $sourcefile);
				}
			}
			closedir($handle);
		}

		echo "\nfinished. duration: " . (microtime(true) - $time1) . "s\n";
	}

	private function importEpgfile($sourcefile) {
		/*
		 * Struktur
		 * Id;beginn;ende;dauer;sender;titel;typ;text;genre_id;fsk;language;weekday;zusatz;wdh;downloadlink;infolink;programlink;
		 */

		echo "\nReading input file " . $sourcefile . " \n";

		// open $sourcefile
		$sourcehandle = fopen($this->tmpdir . $sourcefile, "rb");
		if (!$sourcehandle)
			throw new Exception("File " . $sourcefile . " could not be opened");

		$lineCounter = 0;
		// Datei Zeile für Zeile durchgehen
		while (!feof($sourcehandle)) {
			$lineCounter++;

			echo ".";
			if ($lineCounter % 4000 == 0)
				echo "\n";

			$line = fgets($sourcehandle, 1024);
			$line = utf8_encode($line);
			$values = explode(";", $line);

			// Prüfe Gültigkeit der eingelesenen Zeile
			if (count($values) < 17 || $values[$this->epgIndices['epgid']] == "Id") {
				echo "!";
				continue;
			}

			//arbeite mit inhalt.
			$success = $this->checkRow($values);
			if ($success == 1)
				echo ":";
			else if ($success == 2)
				echo ";";
		}
	}

	private function checkRow($var) {
		//ueberpruefe eintrag
		$btmodel = null;
		if ($var instanceof Model_BroadcastTime) {
			$serial = Factory_Serial::getById($var->getIdSerial());

			$title = $serial->getTitle();
			$epgid = $var->getEpgid();
			$episodeText = $var->getTitle();
			$channel = $var->getChannel();
			$time = $var->getTime();
			$btmodel = $var;
		} else {
			$epgid = $var[$this->epgIndices['epgid']];
			$title = $var[$this->epgIndices['titel']];
			$episodeText = $var[$this->epgIndices['text']];
			$channel = $var[$this->epgIndices['sender']];
			$time = $var[$this->epgIndices['beginn']];
		}

		$found = false;
		if ($this->allserials == null)
			$this->allserials = Factory_Serial::getByFields(null);
		foreach ($this->allserials as $serial) {
			if ($this->otrEquals($serial->getTitle(), $title)) {
				$found = true;
				break;
			}
		}
		if (!$found)
			return;

		$episodeTitles = explode(",", $episodeText);

		$tmptime = new DateTime($time);
		$time = $tmptime->format('Y-m-d H:i:s');

		foreach ($episodeTitles as $episodeTitle) {
			//problem title steht mehreres mit Komma getrennt!
			$episodes = Factory_Episode::getByFields(array("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`title`, 'ä', 'ae'), 'ö', 'oe'), 'ü', 'ue'), 'Ä', 'Ae'), 'Ö', 'Oe'), 'Ü', 'Ue'), 'ß', 'ss'), '-', ' '), ',', ''),' ','')" => "%" . str_replace(array(" ", "'"), "", $episodeTitle) . "%"));

			if (count($episodes) > 0) {
				//eintraege gefunden suche passende serial oder season
				foreach ($episodes as $episode) {
					//ueberpruefe ob serial oder season zum aktuellen passt
					$season = Factory_Season::getByFields(array("id" => $episode->getIdSeason()));
					$season = $season[0];
					$serial = Factory_Serial::getByFields(array("id" => $season->getIdSerial()));
					$serial = $serial[0];
					if ($this->otrEquals($season->getTitle(), $title) || $this->otrEquals($serial->getTitle(), $title)) {
						//season passt || serial passt
						$this->newBroadcastTime($serial, $episode, $epgid, $time, $channel, $episodeTitle, $btmodel);
						return 1;
					}
				}
			}
		}
		//episode nicht gefunden, aber serial passt
		$episodeText = str_replace("'", "", $episodeText);
		$this->newBroadcastTime($serial, null, $epgid, $time, $channel, $episodeText, $btmodel);
		return 2;
	}

	private function otrEquals($dbText, $csvText) {
		//in csvText steht statt ü ue
		//in dbText aus der DB natürlich nicht!
		$replaceSearch = array('ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß', '-', ',', ' ', '.');
		$replaceReplace = array('ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss', ' ', '', '', '');
		$dbText = str_replace($replaceSearch, $replaceReplace, $dbText);
		$csvText = str_replace($replaceSearch, $replaceReplace, $csvText);

		if ($dbText == $csvText)
			return true;
		return false;
	}

	private function newBroadcastTime($serial, $episode, $epgid, $time, $channel, $title, $btmodel = null) {
		if ($btmodel == null) {
			//erzeuge neues BrowadcastTime Model
			$bt = new Model_BroadcastTime();
			$bt->setIdSerial($serial->getId());
			if ($episode != null)
				$bt->setIdEpisode($episode->getId());
			$bt->setEpgid($epgid);
			$bt->setTime($time);
			$bt->setChannel($channel);
			$bt->setTitle($title);
		}else {
			//aktualisiere Model
			$bt = $btmodel;
			$bt->setTitle($title);
			if ($episode != null)
				$bt->setIdEpisode($episode->getId());
		}

		Factory_BroadcastTime::createNew($bt);
	}

	private function downloadfile($file_source, $file_target) {

		$rh = fopen($file_source, 'rb');
		$wh = fopen($file_target, 'wb');
		if ($rh === false || $wh === false) {
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

	private function sendMail($dateFrom, $dateTo, $oldmapped = array()) {
		$from = new DateTime($dateFrom);
		$to = new DateTime($dateTo);
		$broadcastmodels = array();

		$date = new DateTime($from->format("d.m.Y H:i"));
		while ($date <= $to) {
			$values = array(
				"time" => $date->format("Y-m-d") . "%"
			);
			$sortCondition = array("time" => "DESC");
			$broadcastmodels[$date->format("d.m.Y")] = Factory_BroadcastTime::getByFields($values, $sortCondition);

			$year = $date->format('Y');
			$month = $date->format('m');
			$day = $date->format('d');
			$date->setDate($year, $month, $day + 1);
		}

		if (count($oldmapped) > 0) {
			$broadcastmodels["diversen Tage"] = $oldmapped;
		}

		$nachricht = "Hallole,\nim folgenden die neuen Sendungen.\n";
		foreach ($broadcastmodels as $date => $models) {
			$nachricht .= "\n\nSendungen vom " . $date . "\n";
			$unavailable = "";
			$available = "";
			$unassigned = "";

			foreach ($models as $model) {
				$time = new DateTime($model->getTime());
				$serial = Factory_Serial::getById($model->getIdSerial());
				if ($model->getIdEpisode() != null) {
					$episode = Factory_Episode::getById($model->getIdEpisode());
					$season = Factory_Season::getById($episode->getIdSeason());

					if ($episode->getAvailability() == "high" || $episode->getAvailability() == "medium")
						$available .= $time->format("H:i") . " " . $model->getChannel() . ": " . $serial->getTitle() . " - " . $episode->getTitle() . " (" . $season->getTitle() . ") \n";
					else
						$unavailable .= $time->format("H:i") . " " . $model->getChannel() . ": " . $serial->getTitle() . " - " . $episode->getTitle() . " (" . $season->getTitle() . ") \n";
				}else {
					$unassigned .= $time->format("H:i") . " " . $model->getChannel() . ": " . $serial->getTitle() . " - " . $model->getTitle() . " \n";
				}
			}

			$nachricht .= "Fehlende:\n" . $unavailable . "\nVorhandene:\n" . $available . "\nOhne Zuordnung:\n" . $unassigned;
		}

		$mail_header = "From: Marge<margesimpson@luka5.de>\n";
		$mail_header .= "MIME-Version: 1.0";
		$mail_header .= "\nContent-Type: text/plain; charset=UTF-8";
		$mail_header .= "\nContent-Transfer-Encoding: 8bit";

		$emailaddress = $this->configArray['common']['epgEmailAddress'];
		mail($emailaddress, "Sendungen vom " . $from->format("d.m.Y") . ($from < $to ? " bis " . $to->format("d.m.Y") : ""), $nachricht, $mail_header);
	}

	private function recheck() {
		$sortCondition = array("time" => "DESC");
		$broadcastmodels = Factory_BroadcastTime::getByFields(array(
					"idEpisode" => null
						), $sortCondition);
		$mapped = array();
		foreach ($broadcastmodels as $model) {
			$success = $this->checkRow($model);
			if ($success == 1)
				$mapped[] = $model;
		}
		return $mapped;
	}

	private function addFtppushs($dateFrom, $dateTo, $oldmapped = array()) {
		$result = null;

		$from = new DateTime($dateFrom);
		$to = new DateTime($dateTo);
		$broadcastmodels = $oldmapped;

		$date = new DateTime($from->format("d.m.Y H:i"));
		while ($date <= $to) {
			$values = array(
				"time" => $date->format("Y-m-d") . "%"
			);
			$sortCondition = array("time" => "DESC");
			$tmp = Factory_BroadcastTime::getByFields($values, $sortCondition);
			$broadcastmodels = array_merge($tmp, $broadcastmodels);

			$year = $date->format('Y');
			$month = $date->format('m');
			$day = $date->format('d');
			$date->setDate($year, $month, $day + 1);
		}

		foreach($broadcastmodels as $broadcastmodel){
			try{

				if ($broadcastmodel->getIdEpisode() == null)
				//ohne zuordnung => kein ftppush anlegen!
					continue;

				$idEpisode = $broadcastmodel->getIdEpisode();
				$episode = Factory_Episode::getById($idEpisode);

				if ($episode->getAvailability() == "high" || $episode->getAvailability() == "medium" || $episode->getAvailability() == "processing")
				//nur availability = "not" wird als ftppush angelegt
					continue;

				//ftppush anhand von epgid anlegen
				$fileprop = $this->addFtppush($broadcastmodel->getEpgid());

				//setzte Episode auf availability = processing
				$episode->setAvailability(2);
				Factory_Episode::store($episode);

				$ftppush = new Model_Ftppush();
				$ftppush->setIdBroadcastTime($broadcastmodel->getId());
				$ftppush->setFilename($fileprop['filename']);
				$ftppush->setFilesize($fileprop['filesize']);
				$ftppush->setCut($fileprop['isCut']);
				$ftppush->setDecoded($fileprop['isDecoded']);
				$ftppush->setHQ($fileprop['isHQ']);
				Factory_Ftppush::store($ftppush);

				$result .= "\nErfolgreich für idBroadcastmodel " . $broadcastmodel->getId() . " angelegt";
			} catch (Exception $e) {
				$result .= "\nFehler bei idBroadcastmodel " . $broadcastmodel->getId() . ": " . $e->getMessage();
			}
		}
		return $result;
	}

	private function addFtppush($epgid) {
		$cookiefilename = $this->tmpdir . "cookie_" . uniqid() . ".txt";

		$fp = fopen($cookiefilename, "w");
		fclose($fp);

		// create a new cURL resource
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefilename);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefilename);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if($this->checksum == null){
			$codeUrl = "https://www.onlinetvrecorder.com/downloader/api/getcode.php";
			curl_setopt($ch, CURLOPT_URL, $codeUrl);

			// grab URL and pass it to the browser
			$code = curl_exec($ch);

			$this->checksum = md5($this->configArray['otr']['checksumPart1'] . $code . $this->configArray['otr']['checksumPart2']);
		}

		$loginUrl = "https://www.onlinetvrecorder.com/downloader/api/login.php?did=" . $this->configArray['otr']['did'] . "&checksum=" . $this->checksum . "&email=" . $this->configArray['otr']['email'] . "&pass=" . $this->configArray['otr']['pass'];
		curl_setopt($ch, CURLOPT_URL, $loginUrl);
		// grab URL and pass it to the browser
		curl_exec($ch);

		$base64epgid = base64_encode($epgid);
		$filenameUrl = "https://www.onlinetvrecorder.com/downloader/api/request_file2.php?did=" . $this->configArray['otr']['did'] . "&checksum=" . $this->checksum . "&epgid=" . $base64epgid;
		curl_setopt($ch, CURLOPT_URL, $filenameUrl);
		$xmlData = curl_exec($ch);
		$fileprop = $this->parseFilenameXml($xmlData);

		$ftppushUrl = "https://www.onlinetvrecorder.com/downloader/api/ftppush.php?did=" . $this->configArray['otr']['did'] . "&checksum=" . $this->checksum . "&host=" . $this->configArray['otr']['ftphost'] . "&port=" . $this->configArray['otr']['ftpport'] . "&username=" . $this->configArray['otr']['ftpuser'] . "&password=" . $this->configArray['otr']['ftppassword'] . "&directory=" . $this->configArray['otr']['ftpdir'] . "&filename=" . $fileprop['filename'];
		curl_setopt($ch, CURLOPT_URL, $ftppushUrl);
		$xmlData = curl_exec($ch);
		$this->parseFtppushXml($xmlData);

		// close cURL resource, and free up system resources
		curl_close($ch);
		unlink($cookiefilename);

		return $fileprop;
	}

	private function parseFilenameXml($data) {

		$fileprops = array();

		$tmp = new SimpleXMLElement($data);

		$fileprops[] = array(
			"filename" => $tmp->HQMP4_geschnitten->FILENAME,
			"filesize" => $tmp->HQMP4_geschnitten->SIZE,
			"isCut" => true,
			"isDecoded" => false,
			"isHQ" => true
		);
		$fileprops[] = array(
			"filename" => $tmp->HQAVI_unkodiert->FILENAME,
			"filesize" => $tmp->HQAVI_unkodiert->SIZE,
			"isCut" => false,
			"isDecoded" => false,
			"isHQ" => true
		);
		$fileprops[] = array(
			"filename" => $tmp->HQ->FILENAME,
			"filesize" => $tmp->HQ->SIZE,
			"isCut" => false,
			"isDecoded" => true,
			"isHQ" => true
		);
		$fileprops[] = array(
			"filename" => $tmp->AVI_unkodiert->FILENAME,
			"filesize" => $tmp->AVI_unkodiert->SIZE,
			"isCut" => false,
			"isDecoded" => false,
			"isHQ" => false
		);
		$fileprops[] = array(
			"filename" => $tmp->AVI->FILENAME,
			"filesize" => $tmp->AVI->SIZE,
			"isCut" => false,
			"isDecoded" => true,
			"isHQ" => false
		);

		$fileprop = null;
		foreach ($fileprops as $tmp) {
			if ($tmp['filename'] != "") {
				$fileprop = $tmp;
				break;
			}
		}
		if ($fileprop == null)
			throw new Exception("Kein Dateiformate gefunden.");

		return $fileprop;
	}

	private function parseFtppushXml($data) {
		$tmp = new SimpleXMLElement($data);
		$result = $tmp->ITEM->RESULT;

		if ($result == "ADDED" || $result == "DOUBLE")
			return true;

		throw new Exception("Anlegen des Ftppushs fehlgeschlagen. (" . $result . ")");
	}

	private function relocateFiles() {
		/*
		 * schaue alle dateien in /share/ftppush an
		 * verschiebe fertige dateien an richtige stelle
		 * aktualisiere availability der episode
		 */
                                    $files = null;
		$dir = "/share/ftppush/";
		if ($dh = opendir($dir)) {
			while (($filename = readdir($dh)) !== false) {
				$filesize = filesize($dir . $filename);

				$tmp = Factory_Ftppush::getByFields(array("filename" => $filename));
				if(count($tmp) == 0)
					continue;
				$ftppush = $tmp[0];
				
				if(!$ftppush->isCut() || $ftppush->isDecoded())
					continue;
				
				if($ftppush->getFilesize() == round($filesize/1024)){
					//datei fertig kopiert.
					//verschiebe datei!
					$destinationFilename = "";
					$broadcastTime = Factory_BroadcastTime::getById($ftppush->getIdBroadcastTime());
					$episode = Factory_Episode::getById($broadcastTime->getIdEpisode());
					$season = Factory_Season::getById($episode->getIdSeason());
					$serial = Factory_Serial::getById($broadcastTime->getIdSerial());					
					
					$destinationFilename = "";
					if(strlen($season->getNumber()) == 1)
						$destinationFilename = "0" . $season->getNumber();
					else
						$destinationFilename = $season->getNumber();
					$destinationFilename .=  "x";
					if(strlen($episode->getNumber()) == 1)
						$destinationFilename .= "0" . $episode->getNumber();
					else
						$destinationFilename .= $episode->getNumber();
						
					$destinationDir = $this->configArray['common']['videofilesdir'] . $serial->getTitle() . "/" . $season->getTitle() . "/";
					$destinationDir = str_replace(" ", "_", $destinationDir);
					
					rename($dir . $filename, $destinationDir . $destinationFilename);
					
					if($ftppush->isHQ())
						$episode->setAvailability(5);
					else
						$episode->setAvailability(4);
					Factory_Episode::store($episode);

					//save filedetails to mail
					$files[] = array(
						"episode" => $episode,
						"season" => $season,
						"serial" => $serial
					);
				}
			}
			
			closedir($dh);
		}else{
			throw new Exception("Fehler beim Öffnen des Ordners.");
		}

		/*
		* versende Direktlink von automatisch zugeordneten Episoden
		*/
		$links = "";
		foreach($files as $file){
			$links .= "http://nerdserv.somehost.eu/tvseries/static/#" . $file['serial'].getTitle() . "/" . $file['season'].getTitle() . "/"  . $file['episode'].getTitle();
		}
		$message = "Hallo,\n\nunter den folgenden Links findest du die neu hinzugefügten Episoden:\n\n"  . $links . "\n\nGruß,\nMarge";

		$mail_header = "From: Marge<margesimpson@luka5.de>\n";
		$mail_header .= "MIME-Version: 1.0";
		$mail_header .= "\nContent-Type: text/plain; charset=UTF-8";
		$mail_header .= "\nContent-Transfer-Encoding: 8bit";

		$emailaddress = $this->configArray['common']['epgEmailAddress'];
		mail($emailaddress, "Neue Folgen  vom " . date("d.m.Y"), $message, $mail_header);
	}

}

?>