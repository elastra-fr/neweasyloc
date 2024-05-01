<?php
/*Table Customer : Contient les données clients
- uid (UUID - Identifiant unique du document)
- first_name (CHAR(255) - Nom)
- second_name (CHAR(255) - Prénom)
- address (CHAR(255) - Adresse complète)
- permit_number (CHAR(255) -numéro de permis)*/

namespace App\mongo;
require 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';
require_once 'model/AbstractMongoDb.php';
use MongoDB;
use Exception;


//Définition de la classe Customer avec ses getters et setters


class Customer
{
//Attributs de la classe
    private $uid;
    private $first_name;
    private $second_name;
    private $address;
    private $permit_number;

    //Constructeur de la classe
    public function __construct($uid, $first_name, $second_name, $address, $permit_number)
    {
        $this->uid = $uid;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->address = $address;
        $this->permit_number = $permit_number;
    }

    //Mise en place des getters et setters
    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getSecondName()
    {
        return $this->second_name;
    }

    public function setSecondName($second_name)
    {
        $this->second_name = $second_name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getPermitNumber()
    {
        return $this->permit_number;
    }

    public function setPermitNumber($permit_number)
    {
        $this->permit_number = $permit_number;
    }
}

//Définition de la classe CustomerModel qui hérite de la classe AbstractMongoDb
//Cette classe permet de faire des opérations CRUD sur la collection Customer de la base de données MongoDB 
//Elle permet également d'opérer d'autres opérations moins standard sur la collection Customer 



class CustomerModel extends AbstractMongoDb
{

//Constructeur de la classe
    public function __construct()
    {
        parent::__construct('Customer');
    }


/*****************Méthode pour ajouter un client dans la collection Customer en utilisant la classe Customer*****************/

public function addCustomer($customer){


try {
    $data = [
        'uid' => $customer->getUid(),
        'first_name' => $customer->getFirstName(),
        'second_name' => $customer->getSecondName(),
        'address' => $customer->getAddress(),
        'permit_number' => $customer->getPermitNumber()
    ];


//Appel de la méthode create de la classe AbstractMongoDb pour insérer un document dans la collection Customer
    $customer = parent::create($data);
    //echo "Client ajouté avec succès";
    return "Client ajouté avec succès";
}

catch (Exception $e) {
    return "Erreur lors de l'ajout du client : ". $e->getMessage();
}

}


/******************Méthode pour récupérer les données de tous les clients sur mongoDB au format json*****************/

    public function getAllCustomers()
    {


        //$customers = $this->readAll();
        //Utilisation de la méthode readAll de la classe parent pour récupérer les données de tous les clients
        $customers = parent::readAll();
        echo $customers;
        return $customers;
        //return $this->readAll();

    }

    /*****************Méthode pour récupérer les données d'un client par son ID sur mongoDB au format json*****************/

    public function getCustomerById($uid)
    {
        

        //$customer = $this->readSingleById($uid);
        //Utilisation de la méthode readSingleById de la classe parent pour récupérer les données d'un client par son ID
        $customer = parent::readSingleById($uid);
        echo $customer;
        return $customer;
        //return $this->readOne($uid);
        

    }

    /*************************Methode pour effacer un client par son ID*************************************/

    public function deleteCustomerById($uid)
    {

        //Utilisation de la méthode delete de la classe parent pour effacer un client par son ID
        //$customer = $this->delete($uid);
        $customer = parent::delete($uid);
        echo "Client effacé avec succès";
        return $customer;
        
        

    }

    /******************Méthode pour modifier un Customer dans la collection Customer*****************/

    public function updateCustomer($customer, $id)
    {

        echo "id : ".$id;   
        echo "Data : ".$customer->getUid()." ".$customer->getFirstName()." ".$customer->getSecondName()." ".$customer->getAddress()." ".$customer->getPermitNumber();

        

        try{
        $data = ['$set'=>[
            'uid' => $customer->getUid(),
            'first_name' => $customer->getFirstName(),
            'second_name' => $customer->getSecondName(),
            'address' => $customer->getAddress(),
            'permit_number' => $customer->getPermitNumber()
        ]]
        
        ;



            $filter = ['_id' => new MongoDB\BSON\ObjectID($id)];   

//Appel de la méthode update de la classe AbstractMongoDb pour modifier un document dans la collection Customer
        $customer = parent::update($filter, $data);
        echo "Client modifié avec succès";
        return $customer;
    }

    catch (Exception $e) {
        return "Erreur lors de la modification du client : ". $e->getMessage();

    }



    }


    /*************************Méthode pour rechercher un client par son nom et prénom*************************************/

    public function searchCustomer($first_name, $second_name)
    {

      
        //Utilisation de la méthode readSingleByFilter de la classe parent pour rechercher un client par son nom et prénom
        $filter = ['first_name' => $first_name, 'second_name' => $second_name];
        $customer = parent::readAllByFilter($filter);
        $customer= json_encode($customer, JSON_PRETTY_PRINT);
  
        return $customer;
    }




    





}










/*
require_once 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';
*/
/*
  collection Customer
 Table Customer : Contient les données clients
- uid (UUID - Identifiant unique du document)
- first_name (CHAR(255) - Nom)
- second_name (CHAR(255) - Prénom)
- address (CHAR(255) - Adresse complète)
- permit_number (CHAR(255) -numéro de permis)
 */
/*

class Customer {

private $uid;

private $first_name;

private $second_name;

private $address;

private $permit_number;


public function __construct($uid, $first_name, $second_name, $address, $permit_number) {

    $this->uid = $uid;
    $this->first_name = $first_name;
    $this->second_name = $second_name;
    $this->address = $address;
    $this->permit_number = $permit_number;


}

//Mise en place des getters et setters

public function getUid() {
    return $this->uid;
}

public function setUid($uid) {
    $this->uid = $uid;
}

public function getFirst_name() {
    return $this->first_name;
}

public function setFirst_name($first_name) {
    $this->first_name = $first_name;
}

public function getSecond_name() {
    return $this->second_name;
}   

public function setSecond_name($second_name) {
    $this->second_name = $second_name;
}

public function getAddress() {
    return $this->address;
}

public function setAddress($address) {
    $this->address = $address;
}

public function getPermit_number() {
    return $this->permit_number;
}

public function setPermit_number($permit_number) {
    $this->permit_number = $permit_number;
}



//Fonction pour récupérer les données de tous les clients sur mongoDB au format json


public static function getAllCustomers() {

    $connectionMdb = new MongoDB_con();
    $db = $connectionMdb->getDB();
    
    //var_dump($db);
    $collection = $db->Customer;
    $cursor = $collection->find();
    var_dump($collection);

    var_dump($cursor);
    $customers = [];
    
    foreach ($cursor as $document) {
        $customer = new Customer($document['_id'], $document['first_name'], $document['second_name'], $document['address'], $document['permit_number']);
        $customers[] = $customer;
    }

    var_dump($customers);

    // Convertir le tableau d'objets en tableau associatif
    $customerArray = [];
    foreach ($customers as $customer) {
        $customerArray[] = [
            'uid' => $customer->getUid(),
            'first_name' => $customer->getFirst_name(),
            'second_name' => $customer->getSecond_name(),
            'address' => $customer->getAddress(),
            'permit_number' => $customer->getPermit_number()
        ];
    }


  return json_encode($customerArray, JSON_PRETTY_PRINT);
    

    }

    //Fonction pour récupérer les données d'un client par son ID sur mongoDB au format json

    public static function getCustomerById($uid) {

        $connectionMdb = new MongoDB_con();
        $db = $connectionMdb->getDB();
        $collection = $db->Customer;
        $cursor = $collection->find(['_id' => $uid]);
        $customer = null;
        foreach ($cursor as $document) {
            $customer = new Customer($document['_id'], $document['first_name'], $document['second_name'], $document['address'], $document['permit_number']);
        }
        if ($customer === null) {
            return null;
        }
        return json_encode([
            'uid' => $customer->getUid(),
            'first_name' => $customer->getFirst_name(),
            'second_name' => $customer->getSecond_name(),
            'address' => $customer->getAddress(),
            'permit_number' => $customer->getPermit_number()
        ], JSON_PRETTY_PRINT);
    }


}*/