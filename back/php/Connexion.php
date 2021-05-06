<?php
class Connexion {
    const USER = 'root';
    const HOST = 'localhost';
    const PASS = '';
    const DB = 'hotel';

    private $PDOInstance = null;
    private static $instance = null;
 
    private function __construct(){
        $this->PDOInstance = new PDO('mysql:dbname='.self::DB.';host='.self::HOST, self::USER, self::PASS);    
    }
 
    /**
    * Crée et retourne l'objet Connexion
    */
    public static function getInstance(){  
        if(is_null(self::$instance)){
            self::$instance = new Connexion();
        }
    return self::$instance;
    }
 
    /**
     * Exécute une requête SQL avec PDO
     *
     * @param string $query La requête SQL
     * @return PDOStatement Retourne l'objet PDOStatement
     */
    public function query($query){
        return $this->PDOInstance->query($query);
    }
}