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

    




}

//Définition de la classe ContractModel qui hérite de la classe AbstractSqlSrv
//Cette classe permet d'effectuer des opérations CRUD sur la table contract de la base de données SQL Server
//Elle hérite de la classe AbstractSqlSrv pour réutiliser les méthodes de cette classe pour effectuer des opérations CRUD sur la table contract
//Elle contient également des méthodes pour effectuer des opérations moins standard sur la table contract

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


//Création d'un contrat à la date actuelle


//Création d'un contrat à une date donnée


//Méthode pour modifier un contrat dans la table contract


//Méthode pour effacer un contrat par sa clé unique








}