<?php
namespace App\mongo;
use MongoDB;
use Exception;

require 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';
require_once 'config/globals.php';


class MongoDB_con {

    private $mongoClient;
    private $db;

    public function __construct($host = 'localhost', $port = 27017, $dbname = 'easyloc') {
        try {
            $this->mongoClient = new MongoDB\Client("mongodb://$host:$port");
            $this->db = $this->mongoClient->$dbname;
        } catch (Exception $e) {
            die('Erreur de connexion à MongoDB : ' . $e->getMessage());
        }
    }

    public function getDB() {
        return $this->db;
    }

public function connect() {
    if ($this->db instanceof MongoDB\Database) {
       // echo "Connexion réussie !";
        return true;
    }
    //echo "Echec de la connexion";
    return false;   }


}

?>
