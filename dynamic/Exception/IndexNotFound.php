<?php

	class Exception_IndexNotFound extends Exception{
		public function __construct($message = "Index not found.") {
			parent::__construct($message);
		}
	}
?>