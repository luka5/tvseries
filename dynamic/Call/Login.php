<?php
class Call_Login extends Call_Abstract{

	public function __construct() {

	}
	
	public function handle(){
		//log in, if correct
		$username = $_POST['username'];
		$secret = $_POST['secret'];
		
		$values = array(
			"username" => $username,
			"secret" => $secret
		);
		$user = Factory_User::getByFields($values);
		if(count($user) == 0)
			throw new Exception_AccessDenied("Zugangsdaten falsch.");
		
		Session::getInstance()->setVar("idUser" , $user[0]->getId());
		Session::setVar("username" , $username);
		Session::setVar("secret" , $secret);
	
		$data = array(
			"success" => true
		);
		parent::encodeAndPrint($data);
	}
	
}
?>