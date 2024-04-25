<?php

/* Table Billing : Contient les données de payement des contrats. Payement en plusieur fois possible.
Champs :
- ID ( INT - clé unique du payement)
- Contract_id (INT - clé unique du contrat concerné par le payement)
- Amount (MONEY - Montant payé)
*/

namespace App\sqlsrv;
require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';



//Définition de la classe Billing avec ses getters et setters

class Billing{

    private $ID;
    private $Contract_id;
    private $Amount;

    //Constructeur de la classe
    public function __construct($ID, $Contract_id, $Amount) {
        $this->ID = $ID;
        $this->Contract_id = $Contract_id;
        $this->Amount = $Amount;
    }

    public function getID() {
        return $this->ID;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }

    public function getContract_id() {
        return $this->Contract_id;
    }

    public function setContract_id($Contract_id) {
        $this->Contract_id = $Contract_id;
    }

    public function getAmount() {
        return $this->Amount;
    }

    public function setAmount($Amount) {
        $this->Amount = $Amount;

    }


    



}


//Définition de la classe BillingModel qui hérite de la classe AbstractSqlSrv
//Cette classe permet d'effectuer des opérations CRUD sur la table billing de la base de données SQL Server
//Elle hérite de la classe AbstractSqlSrv pour réutiliser les méthodes de cette classe pour effectuer des opérations CRUD sur la table billing
//Elle contient également des méthodes pour effectuer des opérations moins standard sur la table billing

class BillingModel extends AbstractSqlSrv
{

public function __construct() {
    parent::__construct('billing');

}

//Méthode pour créer la table si elle n'existe pas dans la base de données

public function createBillingTable()
{

    $sql = "CREATE TABLE Billing (
        ID INT PRIMARY KEY,
        Contract_id INT,
        Amount MONEY
    )"; 

    $exists=parent::tableExists('Billing', $sql);
    return $exists;

}


//Méthode pour récupérer les données de  paiement sur SQL Server au format json


//Méthode pour récupérer les données de paiement par sa clé unique sur SQL Server au format json


//Méthode pour insérer un paiement dans la table billing


//Méthode pour suppimer un paiement par sa clé unique










}


