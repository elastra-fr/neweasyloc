<?php

namespace App\mongo;

require 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';


//Définition de la classe Vehicle avec ses getters et setters
class Vehicle{



}



//Définition de la classe VehicleModel qui hérite de la classe AbstractMongoDb
//Cette classe permet d'effectuer des opérations CRUD sur la collection vehicle de la base de données MongoDB
//Elle hérite de la classe AbstractMongoDb pour réutiliser les méthodes de cette classe pour effectuer des opérations CRUD sur la collection vehicle
//Elle contient également des méthodes pour effectuer des opérations moins standard sur la collection vehicle
class VehicleModel extends AbstractMongoDb
{



}

