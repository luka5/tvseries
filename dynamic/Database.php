<?php

class Database {

    private $PDOobj;
	private $hostDatabase = null;

	private static $instance = null;

	// Factory-Methode zum Erzeugen einer neuen Instanz
	public static function createInstance($config) {
		if(self::$instance != null)
			throw new Exception("cannot create instance, because it already exists one.");

		self::$instance = new Database($config);
		return self::$instance;
    }

	public static function getInstance(){
		return self::$instance;
	}


	// Konstruktor nicht von extern erreichbar
	private function __construct($config){
        $strDBLocation = $config['type'].":host=".$config['host'].";dbname=".$config['dbname'];
        try {
            $this->PDOobj = new PDO($strDBLocation, $config['user'],  $config['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        }catch (PDOException $e ) {
            throw new Exception("Fehler beim Verbinden zur Datenbank: ". $e->getMessage() );
        }
	}

    public function executeQuery($sqlquery) {
        try {
            $result = $this->PDOobj->query($sqlquery);
            if($result!==FALSE){
                $returnValue = $result->fetchAll(PDO::FETCH_ASSOC);
                $result->closeCursor();
				return $returnValue;
            }else return FALSE;
            
        } catch(PDOExeption $e ) {
            throw new Exception( "Error while executing query: (DBobj->executeQueryAsArray)".$e->getMessage());
        }
    }

    public function executeUpdate($sqlquery) {
        try {
            return $this->PDOobj->exec($sqlquery);
        } catch (PDOExeption $e ) {
            throw new Exeption ( "Error while executing query: (DBobj->executeUpdate): ".$e->getMessage());
        }
    }

    public function getErrorInfo(){
        return $this->PDOobj->errorInfo();
    }

    public function prepare($statment) {
        try {
		    $result = $this->PDOobj->prepare($statment);
		    if($result === FALSE) {
		        return NULL;
		    } else {
		        return $result;
		    }
        } catch (PDOExeption $e) {
			throw new Exception("Couldn't prepare Statement: ".$e->getMessage());
        }
    }

    public function beginnTransaction(){
        return $this->PDOobj->beginTransaction();
    }

    public function commit(){
        return $this->PDOobj->commit();
    }

    public function rollback(){
        return $this->PDOobj->rollback();
    }

}
?>
