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
use Exception;

//Définition de la classe Billing avec ses getters et setters

class Billing{


//Attributs de la classe
    private int $ID;
    private int $Contract_id;
    private float $Amount;

    //Constructeur de la classe
    public function __construct(int $ID, int $Contract_id, float $Amount) {
        $this->ID = $ID;
        $this->Contract_id = $Contract_id;
        $this->Amount = $Amount;
    }


    //Getters et setters
    public function getID(): int{
        return $this->ID;
    }

    public function setID(int $ID): void {
        $this->ID = $ID;
    }

    public function getContract_id(): int {
        return $this->Contract_id;
    }

    public function setContract_id(int $Contract_id) : void {
        $this->Contract_id = $Contract_id;
    }

    public function getAmount() :float{
        return $this->Amount;
    }

    public function setAmount(float $Amount) : void {
        $this->Amount = $Amount;

    }


}


//Définition de la classe BillingModel qui hérite de la classe AbstractSqlSrv
//Cette classe permet d'effectuer des opérations CRUD sur la table billing de la base de données SQL Server
//Elle hérite de la classe AbstractSqlSrv pour réutiliser les méthodes de cette classe pour effectuer des opérations CRUD sur la table billing
//Elle contient également des méthodes pour effectuer des opérations moins standard sur la table billing



class BillingModel extends AbstractSqlSrv
{


//Constructeur de la classe
public function __construct() {
    parent::__construct('billing');

}

/******************Méthode pour créer la table si elle n'existe pas dans la base de données******************/
//La méthode est appelée au démarrage de l'outil en ligne de commande

public function createBillingTable(): bool
{

    $sql="CREATE TABLE Billing (
    ID INT IDENTITY(1,1) PRIMARY KEY,
    Contract_id INT,
    Amount MONEY)";



//Appel de la méthode tableExists de la classe parent pour vérifier si la table existe déjà et la créer si elle n'existe pas
    $exists=parent::tableExists('Billing', $sql);
    return $exists;

}


//Méthode pour récupérer les données de  paiement sur SQL Server au format json en utilisant la méthode readAll de la classe parent

public function getAllBilling(): string
{

    //Appel de la méthode readAll de la classe parent pour récupérer les données de paiement
    return parent::readAll();

}


//Méthode pour récupérer les données de paiement par filtres sur SQL Server au format json

public function getBillingById(int $id):string{

    $filter = "ID = $id";

    //Appel de la méthode readByFilter de la classe parent pour récupérer les données de paiement par filtres
    $singleBilling= parent::readByFilter($filter, null);
    echo $singleBilling;
    return $singleBilling;

}   

/************************Méthode pour ajouter un paiement dans la table billing en utilisant la classe Billing**********************/

public function addBilling(Billing $billing) : string
{

try {

    $data = [
        //'ID' => $billing->getID(),
        'Contract_id' => $billing->getContract_id(),
        'Amount' => $billing->getAmount()
    ];

    //Appel de la méthode create de la classe parent pour insérer un paiement dans la table billing
    $billing = parent::create($data);
    return "Paiement ajouté avec succès";

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage(); 
    return "Echec de l'ajout du paiement";   

}
}


/**************************Methode pour modifier des données de paiment****************************** */


public function updateBilling(int $id, Billing $billing) : string
{

    //Appel de la méthode update de la classe parent pour modifier les données de paiement
    $data = [
        'Contract_id' => $billing->getContract_id(),
        'Amount' => $billing->getAmount()
    ];
    $update = parent::update($data, $id);
    
    return "Paiement modifié avec succès";

}

/**************************Méthode pour suppimer un paiement par sa clé unique****************************** */

public function deleteBilling(int $id): string
{

try {

    $filter = "ID = $id";
    //Appel de la méthode delete de la classe parent pour supprimer un paiement par sa clé unique
    $delete = parent::delete($filter);
   
    return "Paiement supprimé avec succès";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    return "Echec de la suppression du paiement";
}
}

/***************Méthode pour récupérer tous les paiments d'un contrat par son ID sur SQL Server au format json**************** */

public function getBillingByContractId(int $id): string
{

    $filter = "Contract_id = $id";

    //Appel de la méthode readByFilter de la classe parent pour récupérer les données de paiement par filtres
    $billing= parent::readByFilter($filter, null);
    //echo $billing;
    return $billing;


}


}

