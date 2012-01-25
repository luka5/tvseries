<?php

	abstract class Model_Abstract{

		protected function convertDate($datastring){
			
			$date = new DateTime($datastring);
			return $date->format("d-m-Y");
			
		}
		
	}
?>
