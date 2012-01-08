<?php

// common functions
function __autoload($class_name) {
    require_once str_replace('_', '/', $class_name) . '.php';
}
function getRequestVar($key){
	if(isset($_REQUEST[$key]))
		return $_REQUEST[$key];
	else
		throw new Exception_IndexNotFound("RequestVar " . $key . " missing.");
}
// mache aus Errors Exception zum Abfangen, fange alle Exceptions ab und gib Fehlermeldung aus
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    exception_handler("" . $errstr . "<br />" . $errfile . " " . $errline);
}
function exception_handler($msg) {
	require("error.php");
    exit;
}
set_error_handler("exception_error_handler", E_ALL);
set_exception_handler("exception_handler");

// create startup
new Startup();
?>
