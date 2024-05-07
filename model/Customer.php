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
    private ?string $uid;
    private string $first_name;
    private string $second_name;
    private string $address;
    private string $permit_number;

    //Constructeur de la classe
    public function __construct(?string $uid, string $first_name, string $second_name, string $address, string $permit_number)
    {
        $this->uid = $uid;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->address = $address;
        $this->permit_number = $permit_number;
    }

    //Mise en place des getters et setters
    public function getUid():?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid):void
    {
        $this->uid = $uid;
    }

    public function getFirstName():string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name):void
    {
        $this->first_name = $first_name;
    }

    public function getSecondName():string
    {
        return $this->second_name;
    }

    public function setSecondName(string $second_name):void
    {
        $this->second_name = $second_name;
    }

    public function getAddress():string
    {
        return $this->address;
    }

    public function setAddress(string $address):void
    {
        $this->address = $address;
    }

    public function getPermitNumber():string
    {
        return $this->permit_number;
    }

    public function setPermitNumber(string $permit_number):void
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

public function addCustomer(Customer $customer):string
{


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

    public function getAllCustomers():string
    {

        //Utilisation de la méthode readAll de la classe parent pour récupérer les données de tous les clients
        $customers = parent::readAll();
        echo $customers;
        return $customers;
        //return $this->readAll();

    }

    /*****************Méthode pour récupérer les données d'un client par son ID sur mongoDB au format json*****************/

    public function getCustomerById(string $uid):string
    {
        
        $customer = parent::readSingleById($uid);
        echo $customer;
        return $customer;
        

    }

    /*************************Methode pour effacer un client par son ID*************************************/

    public function deleteCustomerById(string $uid):string
    {

    
        $customer = parent::delete($uid);
        echo "Client effacé avec succès";
        return $customer;
        
        

    }

    /******************Méthode pour modifier un Customer dans la collection Customer*****************/

    public function updateCustomer(Customer $customer, string $id):string
    {

       
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
        return "Client modifié avec succès";
    }

    catch (Exception $e) {
        return "Erreur lors de la modification du client : ". $e->getMessage();

    }



    }


    /*************************Méthode pour rechercher un client par son nom et prénom*************************************/

    public function searchCustomer(string $first_name, string $second_name):string
    {

      
        //Utilisation de la méthode readSingleByFilter de la classe parent pour rechercher un client par son nom et prénom
        $filter = ['first_name' => $first_name, 'second_name' => $second_name];
        $customer = parent::readAllByFilter($filter);
        $customer= json_encode($customer, JSON_PRETTY_PRINT);
  
        return $customer;
    }




    





}









