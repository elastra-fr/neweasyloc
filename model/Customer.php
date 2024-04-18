<?php

require_once 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';

/*
  collection Customer
 Table Customer : Contient les données clients
- uid (UUID - Identifiant unique du document)
- first_name (CHAR(255) - Nom)
- second_name (CHAR(255) - Prénom)
- address (CHAR(255) - Adresse complète)
- permit_number (CHAR(255) -numéro de permis)
 */


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


}