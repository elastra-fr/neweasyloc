<?php
/*Table Contract : contient les données des contrats de locations.
Champs :
- id (INT - clé unique du contrat)
- vehicle_uid (CHAR(255) - uid du Vehicle associé au contrat)
- customer_uid (CHAR(255) - uid du Customer associé au contrat)
- sign_datetime (DATETIME - Date + heure de signature du contrat)
- loc_begin_datetime (DATETIME - Date + heure de début de la location)
- loc_end_ datetime (DATETIME - Date + heure de fin de la location)
- returning_datetime (DATETIME - Date + heure de rendu du véhicule)
- price (MONEY - Prix facturé pour le contrat) */
namespace App\sqlsrv;
require 'vendor/autoload.php';
require_once 'model/AbstractSqlSrv.php';

//Définition de la classe Contract avec ses getters et setters
class Contract {

    private $id;
    private $vehicle_uid;
    private $customer_uid;
    private $sign_datetime;
    private $loc_begin_datetime;
    private $loc_end_datetime;
    private $returning_datetime;
    private $price;


    //Constructeur de la classe
    public function __construct($id, $vehicle_uid, $customer_uid, $sign_datetime, $loc_begin_datetime, $loc_end_datetime, $returning_datetime, $price) {
        

        $this->id = $id;
        $this->vehicle_uid = $vehicle_uid;
        $this->customer_uid = $customer_uid;
        $this->sign_datetime = $sign_datetime;
        $this->loc_begin_datetime = $loc_begin_datetime;
        $this->loc_end_datetime = $loc_end_datetime;
        $this->returning_datetime = $returning_datetime;
        $this->price = $price;
    }


//Getters et setters de la classe Contract
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getVehicleUid() {
        return $this->vehicle_uid;
    }

    public function setVehicleUid($vehicle_uid) {
        $this->vehicle_uid = $vehicle_uid;
    }

    public function getCustomerUid() {
        return $this->customer_uid;
    }

    public function setCustomerUid($customer_uid) {
        $this->customer_uid = $customer_uid;
    }

    public function getSignDatetime() {
        return $this->sign_datetime;
    }

    public function setSignDatetime($sign_datetime) {
        $this->sign_datetime = $sign_datetime;
    }

    public function getLocBeginDatetime() {
        return $this->loc_begin_datetime;
    }

    public function setLocBeginDatetime($loc_begin_datetime) {
        $this->loc_begin_datetime = $loc_begin_datetime;
    }

    public function getLocEndDatetime() {
        return $this->loc_end_datetime;
    }

    public function setLocEndDatetime($loc_end_datetime) {
        $this->loc_end_datetime = $loc_end_datetime;
    }

    public function getReturningDatetime() {
        return $this->returning_datetime;
    }

    public function setReturningDatetime($returning_datetime) {
        $this->returning_datetime = $returning_datetime;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    
}

//Définition de la classe ContractModel qui hérite de la classe AbstractSqlSrv
//Cette classe permet d'effectuer des opérations CRUD sur la table contract de la base de données SQL Server
//Elle hérite de la classe AbstractSqlSrv pour réutiliser les méthodes de cette classe pour effectuer des opérations CRUD sur la table contract
//Elle contient également des méthodes pour effectuer des opérations moins standard sur la table contract
//ELLE EST DIFFERENCIEE DE LA CLASSE CONTRACT POUR INSTANCIER UN OBJET DE LA CLASSE CONTRACT UNIQUEMENT SI NECESSAIRE

class ContractModel extends AbstractSqlSrv{

public function __construct(){

parent::__construct('Contract');


}


//Méthode pour créer la table si elle n'existe pas dans la base de données

public function createContractTable()
{

    $sql = "CREATE TABLE Contract (
        id INT PRIMARY KEY,
        vehicle_uid CHAR(255),
        customer_uid CHAR(255),
        sign_datetime DATETIME,
        loc_begin_datetime DATETIME,
        loc_end_datetime DATETIME,
        returning_datetime DATETIME,
        price MONEY
    )";


$exists=parent::tableExists('Contract', $sql);
return $exists;


}



//Méthode pour récupérer les données de tous les contrats sur SQL Server au format json
public function readAll()
{
    $contrats = parent::readAll();
    echo $contrats;
    return $contrats;
    
    }


//Méthode pour récupérer les données d'un contrat par sa clé unique sur SQL Server au format json

public function readSingleById($id)
{
    $contrat = parent::readSingleById($id);
    echo $contrat;
    return $contrat;
    
    }



//Méthode pour insérer un contrat dans la table contract

public function createContract($contract)
{
    $data = [
        'vehicle_uid' => $contract->getVehicleUid(),
        'customer_uid' => $contract->getCustomerUid(),
        'sign_datetime' => $contract->getSignDatetime(),
        'loc_begin_datetime' => $contract->getLocBeginDatetime(),
        'loc_end_datetime' => $contract->getLocEndDatetime(),
        'returning_datetime' => $contract->getReturningDatetime(),
        'price' => $contract->getPrice()
    ];
    
//var_dump($data);
    $toInsert=parent::create($data);
    return $toInsert;


    //$insertOne=parent::create($data);
    //return $insertOne;
    
    }   





//Méthode pour modifier un contrat dans la table contract
public function updateContract($contract, $id)
{
    $data = [
        'vehicle_uid' => $contract->getVehicleUid(),
        'customer_uid' => $contract->getCustomerUid(),
        'sign_datetime' => $contract->getSignDatetime(),
        'loc_begin_datetime' => $contract->getLocBeginDatetime(),
        'loc_end_datetime' => $contract->getLocEndDatetime(),
        'returning_datetime' => $contract->getReturningDatetime(),
        'price' => $contract->getPrice()
    ];

    
    

    $toUpdate=parent::update($data, $id);
    return $toUpdate;
    
    }


//Méthode pour effacer un contrat par sa clé unique

public function deleteContract($id)
{
    $toDelete=parent::delete($id);
    return $toDelete;
    
    }








}