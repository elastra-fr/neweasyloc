<?php

namespace App\sqlsrv;
require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';
require_once 'model/AbstractSqlSrv.php';
use PDO;

class ContractPaid extends AbstractSqlSrv
{
    private $ID;
    private $Contract_id;
    private $Amount;

    public function __construct($ID, $Contract_id, $Amount)
    {

        
        $this->ID = $ID;
        $this->Contract_id = $Contract_id;
        $this->Amount = $Amount;
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setID($ID)
    {
        $this->ID = $ID;
    }

    public function getContract_id()
    {
        return $this->Contract_id;
    }

    public function setContract_id($Contract_id)
    {
        $this->Contract_id = $Contract_id;
    }

    public function getAmount()
    {
        return $this->Amount;
    }

    public function setAmount($Amount)
    {
        $this->Amount = $Amount;
    }

/***********************Methode pour vérifier qu'un contrat a été intégralement payé*******************************/


public function isContractPaid($contractId)
    {
        // Récupération des données de paiement pour le contrat concerné. Calcul de la somme des montants payés pour le contrat et comparaison avec le montant total dû pour le contrat
        $sql = "
            SELECT c.id AS ContractId, c.price AS TotalDue, ISNULL(SUM(b.Amount), 0) AS TotalPaid
            FROM Contract c
            LEFT JOIN Billing b ON c.id = b.Contract_id
            WHERE c.id = :contractId
            GROUP BY c.id, c.price
        ";

        // Préparation de la requête SQL
        $stmt = $this->dbcon->getConnection()->prepare($sql);
        $stmt->bindParam(':contractId', $contractId, PDO::PARAM_INT);
        $stmt->execute();

        // Récupération des résultats de la requête
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification si le contrat a été entièrement payé
        if ($result['TotalPaid'] >= $result['TotalDue']) {
            echo "Le contrat a été intégralement payé";
            return true;
        } else {
            echo "Le contrat n'a pas été intégralement payé";
            return false;
        }
    }

    /************************Méthode pour lister toutes les locations impayées ***************************/

       public function getUnpaidContracts()
    {
        // Requête SQL pour récupérer les données de paiement pour tous les contrats de location impayés
        $sql = "
            SELECT c.id AS ContractId, c.customer_id AS CustomerId, v.license_plate AS LicensePlate, c.price AS TotalDue, ISNULL(SUM(b.Amount), 0) AS TotalPaid
            FROM Contract c
            JOIN Vehicle v ON c.vehicle_id = v.id
            LEFT JOIN Billing b ON c.id = b.Contract_id
            GROUP BY c.id, c.customer_id, v.license_plate, c.price
            HAVING ISNULL(SUM(b.Amount), 0) < c.price
        ";

        // Préparez la requête SQL
        $stmt = $this->dbcon->getConnection()->prepare($sql);
        $stmt->execute();

        // Récupérez les résultats de la requête
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retournez les résultats au format JSON
        return json_encode($result, JSON_PRETTY_PRINT);
    }
 


}