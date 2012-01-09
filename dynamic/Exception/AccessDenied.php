<?php

	class Exception_AccessDenied extends Exception{		
		public function __construct($message = "Access denied.") {
			parent::__construct($message);
		}
	}
?>