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
use PDO;
require_once 'database/SqlSrv_con.php';

//Définition de la classe Contract avec ses getters et setters
class Contract
{

    private int $id;
    private string $vehicle_uid;
    private string $customer_uid;
    private string $sign_datetime;
    private string $loc_begin_datetime;
    private string $loc_end_datetime;
    private string $returning_datetime;
    private float $price;


    //Constructeur de la classe
    public function __construct(int $id,
        string $vehicle_uid,
        string $customer_uid,
        string $sign_datetime,
        string $loc_begin_datetime,
        string $loc_end_datetime,
        string $returning_datetime,
        float $price)
    {


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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getVehicleUid(): string
    {
        return $this->vehicle_uid;
    }

    public function setVehicleUid(string $vehicle_uid): void
    {
        $this->vehicle_uid = $vehicle_uid;
    }

    public function getCustomerUid(): string
    {
        return $this->customer_uid;
    }

    public function setCustomerUid(string $customer_uid): void
    {
        $this->customer_uid = $customer_uid;
    }

    public function getSignDatetime(): string
    {
        return $this->sign_datetime;
    }

    public function setSignDatetime(string $sign_datetime): void
    {
        $this->sign_datetime = $sign_datetime;
    }

    public function getLocBeginDatetime(): string
    {
        return $this->loc_begin_datetime;
    }

    public function setLocBeginDatetime(string $loc_begin_datetime): void
    {
        $this->loc_begin_datetime = $loc_begin_datetime;
    }

    public function getLocEndDatetime(): string
    {
        return $this->loc_end_datetime;
    }

    public function setLocEndDatetime(string $loc_end_datetime): void
    {
        $this->loc_end_datetime = $loc_end_datetime;
    }

    public function getReturningDatetime(): string
    {
        return $this->returning_datetime;
    }

    public function setReturningDatetime(string $returning_datetime): void
    {
        $this->returning_datetime = $returning_datetime;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}

//Définition de la classe ContractModel qui hérite de la classe AbstractSqlSrv
//Cette classe permet d'effectuer des opérations CRUD sur la table contract de la base de données SQL Server
//Elle hérite de la classe AbstractSqlSrv pour réutiliser les méthodes de cette classe pour effectuer des opérations CRUD sur la table contract
//Elle contient également des méthodes pour effectuer des opérations moins standard sur la table contract
//ELLE EST DIFFERENCIEE DE LA CLASSE CONTRACT POUR INSTANCIER UN OBJET DE LA CLASSE CONTRACT UNIQUEMENT SI NECESSAIRE

class ContractModel extends AbstractSqlSrv
{

    //Constructeur de la classe

    public function __construct()
    {

        parent::__construct('Contract');
    }


    /********************************Méthode pour créer la table si elle n'existe pas dans la base de données**************/

    public function createContractTable(): bool
    {

        $sql = "CREATE TABLE Contract (
    id INT IDENTITY(1,1) PRIMARY KEY,
    vehicle_uid CHAR(255),
    customer_uid CHAR(255),
    sign_datetime DATETIME,
    loc_begin_datetime DATETIME,
    loc_end_datetime DATETIME,
    returning_datetime DATETIME,
    price MONEY
    )";

        //Utilisation de la méthode tableExists de la classe parent pour vérifier si la table existe déjà dans la base de données et la créer si elle n'existe pas
        $exists = parent::tableExists('Contract', $sql);
        return $exists;
    }



    /*******************Méthode pour récupérer les données de tous les contrats sur SQL Server au format json*******************/
    public function readAll(): string
    {
        $contrats = parent::readAll();
        //echo $contrats;
        return $contrats;
    }

    /***************Méthode pour récupérér les contrats associés à un véhicule**********************/

    public function getContractsByVehicle(string $uid): string
    {
        $filter = "vehicle_uid = '$uid'";

        //Utilisation de la méthode readByFilter de la classe parent pour récupérer les données des contrats filtrées par uid véhicule
        $contracts = parent::readByFilter($filter, null);
        //echo $contracts;
        return $contracts;
    }


    /**********************Méthode pour récupérer les données d'un contrat par sa clé unique sur SQL Server au format json*/



    public function readSingleById(int $id): string
    {
        //Filtrer les données par id    
        $filter = "id =$id";

        //Utilisation de la méthode readByFilter de la classe parent pour récupérer les données du contrat filtrées par id
        $contrat = parent::readByFilter($filter, null);
        //echo $contrat;
        return $contrat;
    }




    /*********************Méthode pour insérer un contrat dans la table contract à la date actuelle ou à une date passée en paramètre*******************/



     private function formatDateForDB($date)
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }
    public function createContract(Contract $contract): bool

    {

        $sign_datetime = $this->formatDateForDB($contract->getSignDatetime());
        $loc_begin_datetime = $this->formatDateForDB($contract->getLocBeginDatetime());
        $loc_end_datetime = $this->formatDateForDB($contract->getLocEndDatetime());
        $returning_datetime = $this->formatDateForDB($contract->getReturningDatetime());



        $data = [
            'vehicle_uid' => $contract->getVehicleUid(),
            'customer_uid' => $contract->getCustomerUid(),
            'sign_datetime' => $sign_datetime,
            'loc_begin_datetime' => $loc_begin_datetime,
            'loc_end_datetime' => $loc_end_datetime,
            'returning_datetime' => $returning_datetime,
            'price' => $contract->getPrice()
        ];

var_dump($data);


        //Utilisation de la méthode create de la classe parent pour insérer les données du contrat dans la table contract
        $toInsert = parent::create($data);
        return $toInsert;
    }






    /************************Méthode pour modifier un contrat dans la table contract*****************/
    public function updateContract(Contract $contract, int $id): bool
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



        //Utilisation de la méthode update de la classe parent pour modifier les données du contrat dans la table contract
        $toUpdate = parent::update($data, $id);
        return $toUpdate;
    }


    /**************************Méthode pour effacer un contrat par sa clé unique*************************************/

    public function deleteContract(int $id): bool
    {


        $filter = "id =$id"; {

            //Utilisation de la méthode delete de la classe parent pour effacer les données du contrat dans la table contract
            $toDelete = parent::delete($filter);
            return $toDelete;
        }
    }


    /******************Methode pour récupérer la liste des contrats par uid utilisateur ********************/
/*
    public function getContractsByUser(string $uid): string
    {
        $filter = "customer_uid = '$uid'";

        //Utilisation de la méthode readByFilter de la classe parent pour récupérer les données des contrats filtrées par uid utilisateur
        $contracts = parent::readByFilter($filter, null);
        echo $contracts;
        return $contracts;
    }*/


    /****************Liste des locations en cours associées à un utilisateur******************//*
    public function getOngoingContractsByUser($uid)
    {


        $filter = "customer_uid = '$uid' AND loc_end_datetime > GETDATE()";

        $contracts = parent::readByFilter($filter);
        echo $contracts;
        return $contracts;
    }
*/

    /****************Méthode pour récupérer la Liste de toutes les locations en retard ******************/
    //Get late contract 
   /* public function getLateContracts()
    {


        $filters = "returning_datetime IS NULL AND loc_end_datetime < GETDATE() AND vehicle_uid IS NOT NULL AND customer_uid IS NOT NULL";

        //Utilisation de la mé

        $lateContracts = parent::readByFilter($filters, null);
        return $lateContracts;
    }
*/

    /********************************** Méthode pour compter le nombre de retard entre deux dates**********************************/

    

public function getNbDelayBetweenDates(string $start_date, string $end_date)
 {
    $conditions = "returning_datetime IS NOT NULL AND loc_end_datetime < returning_datetime AND loc_end_datetime BETWEEN '$start_date' AND '$end_date'";
    $delays = parent::readByFilter($conditions, null);

    $delays = json_decode($delays, JSON_PRETTY_PRINT);
  var_dump(count($delays));
    

    
    //return count($delays);
}


    


/*****************************************Méthode pour obtenir le nombre de retard moyen par client **********************************/

public function getAvgDelayByCustomer(){

 $pdo= new SqlSrv_con();
 $connection=$pdo->connect();




   $sql = "SELECT customer_uid, AVG(DATEDIFF(hour, loc_end_datetime, returning_datetime)) as avg_delay
            FROM Contract
            WHERE returning_datetime IS NOT NULL AND DATEDIFF(hour, loc_end_datetime, returning_datetime) > 1
            GROUP BY customer_uid";

// Préparer la requête

$stmt=$connection->prepare($sql);

$stmt->execute();

$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
$result=json_encode($result);

return $result;


}



}
