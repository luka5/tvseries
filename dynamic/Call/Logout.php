<?php

class Call_Logout extends Call_Abstract {

    public function __construct() {
        
    }

    public function handle() {

        Session::getInstance()->destroy();
        
        $data = array(
            "success" => true
        );
        parent::encodeAndPrint($data);
    }

}

?>