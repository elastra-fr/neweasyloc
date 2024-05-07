<?php

namespace App\mongo;
require 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';

require_once 'model/AbstractMongoDb.php';
use MongoDB;
require_once 'model/Vehicle.php';

use PHPUnit\Framework\TestCase;
use TypeError;


class VehiculeTest extends TestCase
{

//Test de la création de l'objet Vehicle avec des données valides

public function testCreateVehicleWithValidData()
{
    // Créez un objet Vehicle avec des données valides
    $vehicle = new Vehicle(null, '123', '456', 100);

    // Vérifiez que l'objet a été créé avec succès
    $this->assertInstanceOf(Vehicle::class, $vehicle);

}

//Test de la création de l'objet Vehicle avec des données invalides

public function testCreateVehicleWithInvalidData()
{
    // Utilisez une assertion pour vérifier qu'une erreur est levée lors de la création d'un objet Vehicle avec des données invalides
    $this->expectException(TypeError::class);

    // Créez un objet Vehicle avec des données invalides
    $vehicle = new Vehicle(null, 'Test', '456', "abcd");

    // Cette assertion ne sera pas atteinte car une exception est attendue lors de la création de l'objet
    $this->assertNull($vehicle);


   }

}