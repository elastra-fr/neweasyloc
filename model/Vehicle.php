<?php

/*
Table Vehicle : Contient les données associées à un véhicule
- uid (UUID - Identifiant unique du document)
- licence_plate (CHAR(255) - Immatriculation du véhicule)
- informations (TEXT - Notes sur le véhicule, par exemple dégradations)
- km (INT - Kilométrage du véhicule)
*/

namespace App\mongo;
require 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';
use MongoDB;


//Définition de la classe Vehicle avec ses getters et setters
class Vehicle{

    //Attributs de la classe
    private ?string $uid;
    private string $licence_plate;
    private string $informations;
    private int $km;

    //Constructeur de la classe
    public function __construct(?string $uid, string $licence_plate, string $informations, int $km) {
        $this->uid = $uid;
        $this->licence_plate = $licence_plate;
        $this->informations = $informations;
        $this->km = $km;
    }

    //Getters et setters

public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): void
    {
        $this->uid = $uid;
    }

    public function getLicencePlate(): string
    {
        return $this->licence_plate;
    }

    public function setLicencePlate(string $licence_plate): void
    {
        $this->licence_plate = $licence_plate;
    }

    public function getInformations(): string
    {
        return $this->informations;
    }

    public function setInformations(string $informations): void
    {
        $this->informations = $informations;
    }

    public function getKm(): int
    {
        return $this->km;
    }

    public function setKm(int $km): void
    {
        $this->km = $km;
    }




}



//Définition de la classe VehicleModel qui hérite de la classe AbstractMongoDb
//Cette classe permet d'effectuer des opérations CRUD sur la collection vehicle de la base de données MongoDB
//Elle hérite de la classe AbstractMongoDb pour réutiliser les méthodes de cette classe pour effectuer des opérations CRUD sur la collection vehicle
//Elle contient également des méthodes pour effectuer des opérations moins standard sur la collection vehicle
class VehicleModel extends AbstractMongoDb
{

    public function __construct()
    {
        parent::__construct('Vehicle');
    }   

    /***************Méthode pour ajouter un véhicule dans la collection vehicle*********************/

    public function addVehicle(Vehicle $vehicle):string
    {
        $data = [
            'uid' => $vehicle->getUid(),
            'licence_plate' => $vehicle->getLicenceplate(),
            'informations' => $vehicle->getInformations(),
            'km' => $vehicle->getKm()
        ];

        //Appel de la méthode create de la classe AbstractMongoDb pour insérer un document dans la collection vehicle
        $newVehicle= parent::create($data);
        return $newVehicle;
    }


    /*********************Méthode pour modifier un véhicule dans la collection vehicle*******************/

    public function updateVehicle(Vehicle $vehicle, string $id):string
    {
        $data = ['$set'=>[
            'uid' => $vehicle->getUid(),
            'licence_plate' => $vehicle->getLicenceplate(),
            'informations' => $vehicle->getInformations(),
            'km' => $vehicle->getKm()
        ]];

        $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];

        //Appel de la méthode update de la classe AbstractMongoDb pour mettre à jour un document dans la collection vehicle
        $updateVehicle= parent::update($filter, $data);
        return $updateVehicle;
    }

    /***********************Méthode pour supprimer un véhicule dans la collection vehicle*******************/

    public function deleteVehicle(string $uid):string
    {
        //Appel de la méthode delete de la classe AbstractMongoDb pour supprimer un document dans la collection vehicle
        $deleteVehicle= parent::delete($uid);
        return $deleteVehicle;
    }


    /**********************Méthode pour rechercher un véhicule par son numéro d'immatriculation dans la collection vehicle*******************/

    public function searchVehicleByLicencePlate(string $licence_plate):string
    {
        //Appel de la méthode read de la classe AbstractMongoDb pour récupérer tous les documents de la collection vehicle
        $vehicle= parent::readSingleByFilter(['licence_plate' => $licence_plate]);
        return $vehicle;
    }
    //Méthode pour rechercher un véhicule dont le kilométrage est supérieur à un certain seuil dans la collection vehicle

    public function searchVehicleByKmGreaterThan(int $km):string{
        //Appel de la méthode read de la classe AbstractMongoDb pour récupérer tous les documents de la collection vehicle
         $filter = ['km' => ['$gt' => (int)$km]];
          $vehicle= parent::readAllByFilter($filter);
        return $vehicle;
    }

    //Méthode pour rechercher un véhicule dont le kilométrage est inférieur à un certain seuil dans la collection vehicle

    public function searchVehicleByKmLessThan(int $km):string{

        echo "$km";
        //Appel de la méthode read de la classe AbstractMongoDb pour récupérer tous les documents de la collection vehicle
        $filter = ['km' => ['$lt' => (int)$km]];    
        $vehicle= parent::readAllByFilter($filter);
        return $vehicle;
    }
    



}

