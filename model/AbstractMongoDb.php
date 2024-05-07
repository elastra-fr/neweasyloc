<?php

namespace App\mongo;

use MongoDB;
use Exception;
use MongoDB\Collection;

require 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';




//Classe abstraite pour des opérations classiques sur les collections MongoDB pour permettre la réutilisation du code pour d'autres collections MongoDB
//Cette classe contient des méthodes pour insérer, lire, mettre à jour et supprimer des documents dans une collection MongoDB
//Les méthodes de cette classe sont utilisées par les classes de modèle pour effectuer des opérations CRUD sur les collections MongoDB
//Les classes de modèle héritent de cette classe pour effectuer des opérations CRUD sur les collections MongoDB

abstract class AbstractMongoDb
{

    //
    protected MongoDB\Database $db;
    protected MongoDB\Collection $collection;

    //Constructeur de la classe
    public function __construct( string $collection)
    {
        $mongoDBCon = new MongoDB_con();
        $this->db = $mongoDBCon->getDB();

  $this->collectionName = $collection;

        $this->collection = $this->db->$collection;
         
             // Initialisation de la propriété $collectionName
      

        
        
    }

    //********************Méthode pour insérer un document dans une collection******************

    //La méthode récupère un tableau associatif $data  et insère un document dans une collection
    public function create(array $data) : string
    {

        try {
            $insertOne = $this->collection->insertOne($data);
            return $insertOne->getInsertedId();
        } catch (Exception $e) {

            echo "Erreur lors de l'insertion du document :" . $e->getMessage();
            return false;
        }
    }

    //*********************Méthode pour récupérer tous les documents d'une collection avec find********************



//La méthode récupère tous les documents d'une collection et les retourne au format JSON
    public function readAll() : string
    {


        try {

            $result = $this->collection->find();

            //return to json format
            return json_encode(iterator_to_array($result), JSON_PRETTY_PRINT);



            //return $result;

        } catch (Exception $e) {

            echo "Erreur lors de la récupération des documents :" . $e->getMessage();

            return false;
        }
    }

    /********************************Méthode pour récupérer tous les documents d'un collection avec filtre*********************************************/

//La méthode récupère tous les documents d'une collection avec un filtre et les retourne au format JSON
    public function readAllByFilter(array $filter) : string
    {

        try {

            $result = $this->collection->find($filter);

            //return to json format
            return json_encode(iterator_to_array($result), JSON_PRETTY_PRINT);

            //return $result;

        } catch (Exception $e) {

            echo "Erreur lors de la récupération des documents :" . $e->getMessage();

            return false;
        }
    }




    //************************Méthode pour récupérer un document dans une collection par son ID avec findOne*******************

//La méthode récupère un document dans une collection par son ID et le retourne au format JSON
    public function readSingleById(string $id) : string
    {

        try {

            $filter = ['_id' => new MongoDB\BSON\ObjectID($id)];
            $customer = $this->collection->findOne($filter);
            return json_encode($customer, JSON_PRETTY_PRINT);
        } catch (Exception $e) {

            echo "Erreur lors de la récupération du document :" . $e->getMessage();

            return false;
        }
    }

    //************************Méthode pour récupérer un document dans une collection par un filtre avec findOne*******************

//La méthode récupère un document dans une collection par un filtre et le retourne au format JSON
    public function readSingleByFilter(array $filter) : string
    {

        try {

            $customer = $this->collection->findOne($filter);
            return json_encode($customer, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo "Erreur lors de la récupération du document :" . $e->getMessage();

            return false;
        }
    }

    //***************************Méthode pour supprimer un document dans une collection par son ID avec deleteOne******************
//La méthode supprime un document dans une collection par son ID et retourne true si la suppression a réussi, sinon false
    public function delete(string $id) : bool
    {

        try {

            $filter = ['_id' => new MongoDB\BSON\ObjectID($id)];
            $result = $this->collection->deleteOne($filter);
            return true;
        } catch (Exception $e) {
            echo "Erreur lors de la suppression du document :" . $e->getMessage();
            return false;
        }
    }





    //*********************************Méthode pour mettre à jour un document dans une collection par son ID avec updateOne******************

//La méthode met à jour un document dans une collection par son ID et retourne true si la mise à jour a réussi, sinon false

    public function update(array $filter,  array $data)  : bool
    {

        try {

            $result = $this->collection->updateOne($filter, $data);
            return true;
        } catch (Exception $e) {

            echo "Erreur lors de la mise à jour du document :" . $e->getMessage();
            return false;
        }
    }
}
