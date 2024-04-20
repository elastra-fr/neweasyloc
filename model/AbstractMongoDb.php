<?php

require 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';


//Class abstraite pour des opérations classiques sur les collections MongoDB pour peermettre la réutilisation du code pour d'autres collections
abstract class AbstractMongoDb {

//
    protected $db;
    protected $collection;

//Constructeur de la classe
    public function __construct($collection) {
        $mongoDBCon = new MongoDB_con();
        $this->db = $mongoDBCon->getDB();
        $this->collection = $this->db->$collection;


    }

//Méthode pour insérer un document dans une collection
    public function create($data){

try {

$result = $this->collection->insertOne($data);
return $result->getInsertedId();


}

catch (Exception $e) {

return false;

}



    }

//Méthode pour récupérer tous ou partie des documents d'une collection au format json
    

    
    public function readAll($filter = []) {


try {

    $result = $this->collection->find($filter);

    //return to json format
    return json_encode(iterator_to_array($result), JSON_PRETTY_PRINT);
    


    //return $result;

}

catch (Exception $e) {

    return false;

}


    }

    //Méthode pour mettre à jour un document dans une collection

    public function update($filter, $data) {

try {

$result = $this->collection->updateOne($filter, $data);

}

catch (Exception $e) {

return false;
}


    }






}
