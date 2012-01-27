<?php

	abstract class Model_Abstract{

		protected function convertDate($datastring){
			
			$germanMonths = array("Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
			$englishMonths = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
			$datastring = str_replace($germanMonths, $englishMonths, $datastring);
			
			$date = new DateTime($datastring);
			return $date->format("Y-m-d");
			
		}
		
	}
?>
