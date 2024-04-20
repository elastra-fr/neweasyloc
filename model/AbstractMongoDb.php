<?php
namespace App\mongo;
use MongoDB;
use Exception;


require 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';




//Class abstraite pour des opérations classiques sur les collections MongoDB pour permettre la réutilisation du code pour d'autres collections MongoDB
//Cette classe contient des méthodes pour insérer, lire, mettre à jour et supprimer des documents dans une collection MongoDB
//Les méthodes de cette classe sont utilisées par les classes de modèle pour effectuer des opérations CRUD sur les collections MongoDB
//Les classes de modèle héritent de cette classe pour effectuer des opérations CRUD sur les collections MongoDB

abstract class AbstractMongoDb
{

    //
    protected $db;
    protected $collection;

    //Constructeur de la classe
    public function __construct($collection)
    {
        $mongoDBCon = new MongoDB_con();
        $this->db = $mongoDBCon->getDB();
        $this->collection = $this->db->$collection;


    }

    //********************Méthode pour insérer un document dans une collection******************
    public function create($data)
    {

        try {

            $result = $this->collection->insertOne($data);
            return $result->getInsertedId();


        } catch (Exception $e) {

            return false;

        }



    }

    //*********************Méthode pour récupérer tous les documents d'une collection avec find********************



    public function readAll()
    {


        try {

            $result = $this->collection->find();

            //return to json format
            return json_encode(iterator_to_array($result), JSON_PRETTY_PRINT);



            //return $result;

        } catch (Exception $e) {

            return false;

        }


    }


    //************************Méthode pour récupérer un document dans une collection par son ID avec findOne*******************

    public function readSingleById($id)
    {

        try {

            $filter = ['_id' => new MongoDB\BSON\ObjectID($id)];
            $customer = $this->collection->findOne($filter);
            return json_encode($customer, JSON_PRETTY_PRINT);


        } catch (Exception $e) {

            return false;

        }



    }

    //***************************Méthode pour supprimer un document dans une collection par son ID avec deleteOne******************

    public function delete($id)
    {

        try {

            $filter = ['_id' => new MongoDB\BSON\ObjectID($id)];
            $result = $this->collection->deleteOne($filter);
            return true;


        } catch (Exception $e) {
            return false;
        }




    }





    //*********************************Méthode pour mettre à jour un document dans une collection par son ID avec updateOne******************

    public function update($filter, $data)
    {

        try {

            $result = $this->collection->updateOne($filter, $data);

        } catch (Exception $e) {

            return false;
        }


    }






}
