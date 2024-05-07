<?php

namespace App\sqlsrv;
use App\mongo;
use App\mongo\CustomerModel;
use App\sqlsrv\Customer;

require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';
require_once 'model/AbstractMongoDb.php';
require_once 'model/AbstractSqlSrv.php';
require_once 'model/AbstractMongoDB.php';
require_once 'model/Customer.php';




use PDO;


class ContractPaid 
{
  
protected SqlSrv_con $dbcon;


    public function __construct()
    {

 $sqlSrvCon = new SqlSrv_con();
        $this->dbcon = $sqlSrvCon;

        //$pdo = $sqlSrvCon->connect();
       // parent::__construct('Contract');
        //$this->dbcon = $pdo;
        
     
    }


/***********************Methode pour vérifier qu'un contrat a été intégralement payé*******************************/


public function isContractPaid(int $contractId) : bool
    {

        $pdo=$this->dbcon->connect();
        // Récupération des données de paiement pour le contrat concerné. Calcul de la somme des montants payés pour le contrat et comparaison avec le montant total dû pour le contrat
        $sql = "
            SELECT c.id AS ContractId, c.price AS TotalDue, ISNULL(SUM(b.Amount), 0) AS TotalPaid
            FROM Contract c
            LEFT JOIN Billing b ON c.id = b.Contract_id
            WHERE c.id = :contractId
            GROUP BY c.id, c.price
        ";
  // Préparation de la requête SQL
         $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':contractId', $contractId, PDO::PARAM_INT);
        $stmt->execute();

        // Récupération des résultats de la requête
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
/*
        // Préparation de la requête SQL
        $stmt = $this->dbcon->getConnection()->prepare($sql);

        $stmt->bindParam(':contractId', $contractId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Récupération des résultats de la requête
        $result = $stmt->fetch(PDO::FETCH_ASSOC);*/
        echo "\n";
        echo "Total Payé : ".$result['TotalPaid'];
        echo "\n";
        echo "Total Prix : ".$result['TotalDue'];
        echo "\n";

        echo "Reste à payer : ".($result['TotalDue']-$result['TotalPaid'])."\n";

          echo "\n";




        // Vérification si le contrat a été entièrement payé
        if ($result['TotalPaid'] >= $result['TotalDue']) {
            echo "Le contrat a été intégralement payé";
            echo "\n";
            return true;
        } else {
            echo "Le contrat n'a pas été intégralement payé";
            echo "\n";
            return false;
        }

    }

    /************************Méthode pour lister toutes les locations impayées ***************************/

       

//Recoupement avec la base customer

public function getUnpaidContractsWithCustomerData(): string
    {
        // Récupérez les données de paiement pour tous les contrats de location impayés
        $unpaidContracts = $this->getUnpaidContracts();
        $unpaidContracts = json_decode($unpaidContracts, true);

        // Récupérez les données du client pour chaque contrat impayé depuis MongoDB
        $contractsWithCustomerData = [];
        foreach ($unpaidContracts as $contract) {
            // Récupérez les données du client depuis MongoDB en utilisant l'ID du client
                    //echo "ID du client : ".$contract['CustomerId'];
                    $id = trim($contract['CustomerId']);
                 
            $customer = new CustomerModel();
                     $customer = $customer->getCustomerById($id);
                     $customer = json_decode($customer, true);

                    
/*
            // Ajoutez les données du client au tableau de contrats impayés
            $contract['CustomerName'] = $customer['first_name'];
            $contract['CustomerLast'] = $customer['second_name'];
            $contractsWithCustomerData[] = $contract;*/
              $contract['CustomerName'] = $customer['first_name'];
        $contract['CustomerLast'] = $customer['second_name'];
        $contractsWithCustomerData[] = $contract;
        }

        // Retournez les résultats au format JSON
        return json_encode($contractsWithCustomerData, JSON_PRETTY_PRINT);
    }


public function getUnpaidContracts(): string
    {

        $pdo=$this->dbcon->connect();
        // Requête SQL pour récupérer les données de paiement pour tous les contrats de location impayés
        $sql = "
            SELECT c.id AS ContractId, c.customer_uid AS CustomerId, c.price AS TotalDue, ISNULL(SUM(b.Amount), 0) AS TotalPaid
            FROM Contract c
            LEFT JOIN Billing b ON c.id = b.Contract_id
            GROUP BY c.id, c.customer_uid, c.price
            HAVING ISNULL(SUM(b.Amount), 0) < c.price
        ";

        // Préparez la requête SQL
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Récupérez les résultats de la requête
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //var_dump($result);
        // Retournez les résultats au format JSON
        return json_encode($result, JSON_PRETTY_PRINT);

    
    }

 
    


}