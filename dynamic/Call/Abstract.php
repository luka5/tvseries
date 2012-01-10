<?php

	abstract class Call_Abstract{
		public function __construct(){
			$this->checkUserdata();
		}
	
		protected  function encodeAndPrint($data){
			echo json_encode($data);		
		}
		
		public function checkUserdata(){
			if(!isset($_SESSION['idUser']) || !isset($_SESSION['username']) || !isset($_SESSION['secret']))
				throw new Exception_AccessDenied("Nicht angemeldet.");
			
			$user = Factory_User::getById(Session::getInstance()->getVar("idUser"));
			if($user->getUsername() != Session::getInstance()->getVar("username") || $user->getSecret() != Session::getInstance()->getVar("secret"))
				throw new Exception_AccessDenied("Benutzerdaten falsch.");
		}
		
	}

?>
