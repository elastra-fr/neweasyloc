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
use DateTime;
use Exception;

require_once 'database/SqlSrv_con.php';

//Définition de la classe Contract avec ses getters et setters
class Contract
{

    private ?int $id;
    private string $vehicle_uid;
    private string $customer_uid;
    private string $sign_datetime;
    private string $loc_begin_datetime;
    private string $loc_end_datetime;
    private ?string $returning_datetime;
    private float $price;


    //Constructeur de la classe
    public function __construct(
        ?int $id,
        string $vehicle_uid,
        string $customer_uid,
        string $sign_datetime,
        string $loc_begin_datetime,
        string $loc_end_datetime,
        ?string $returning_datetime,
        float $price
    ) {


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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
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

    public function getReturningDatetime(): ?string
    {
        return $this->returning_datetime;
    }

    public function setReturningDatetime(?string $returning_datetime): void
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




public function createContract(Contract $contract): bool
{
    // Convertir les chaînes de caractères en objets DateTime
    $sign_datetime = new DateTime($contract->getSignDatetime());
    $loc_begin_datetime = new DateTime($contract->getLocBeginDatetime());
    $loc_end_datetime = new DateTime($contract->getLocEndDatetime());

    // Vérifier si la date de retour est nulle
    $returning_datetime = null;
    if ($contract->getReturningDatetime() !== null && $contract->getReturningDatetime() !== "" && $contract->getReturningDatetime() !== "null"){
        $returning_datetime = new DateTime($contract->getReturningDatetime());
         $returning_datetime= $returning_datetime->format('Ymd H:i:s.v');
    }


    // Créer un tableau de données pour l'insertion
    $data = [
        'vehicle_uid' => $contract->getVehicleUid(),
        'customer_uid' => $contract->getCustomerUid(),
        'sign_datetime' => $sign_datetime->format('Ymd H:i:s.v'),
        'loc_begin_datetime' => $loc_begin_datetime->format('Ymd H:i:s.v'),
        'loc_end_datetime' => $loc_end_datetime->format('Ymd H:i:s.v'),
        'returning_datetime' => $returning_datetime, 
        'price' => $contract->getPrice()
    ];

    // Utilisation de la méthode create de la classe parent pour insérer les données du contrat dans la table contract
    $toInsert = parent::create($data);
    return $toInsert;
}









    /************************Méthode pour modifier un contrat dans la table contract*****************/
public function updateContract(Contract $contract, int $id): bool
{
    // Convertir les chaînes de caractères en objets DateTime
    $sign_datetime = new DateTime($contract->getSignDatetime());
    $loc_begin_datetime = new DateTime($contract->getLocBeginDatetime());
    $loc_end_datetime = new DateTime($contract->getLocEndDatetime());

    // Vérifier si la date de retour est nulle
    $returning_datetime = null;
    if ($contract->getReturningDatetime() !== null && $contract->getReturningDatetime() !== "" && $contract->getReturningDatetime() !== "null"){
        $returning_datetime = new DateTime($contract->getReturningDatetime());
         $returning_datetime= $returning_datetime->format('Ymd H:i:s.v');
    }

    // Créer un tableau de données pour la mise à jour
    $data = [
        'vehicle_uid' => $contract->getVehicleUid(),
        'customer_uid' => $contract->getCustomerUid(),
        'sign_datetime' => $sign_datetime->format('Ymd H:i:s.v'),
        'loc_begin_datetime' => $loc_begin_datetime->format('Ymd H:i:s.v'),
        'loc_end_datetime' => $loc_end_datetime->format('Ymd H:i:s.v'),
       'returning_datetime' => $returning_datetime, 
        'price' => $contract->getPrice()
    ];

    // Utilisation de la méthode update de la classe parent pour modifier les données du contrat dans la table contract
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
}
