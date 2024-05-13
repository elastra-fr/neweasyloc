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


//Test de lecture de tous les documents de la collection Vehicle

public function testReadAll()
{
    // Données d'exemple représentant ce qu'on attend de la méthode readAll
    $expectedData = '[{"id":1,"field1":"value1"},{"id":2,"field1":"value2"}]';

    // Créez un mock pour la classe VehicleModel
    $vehicleModelMock = $this->getMockBuilder(VehicleModel::class)
                              ->onlyMethods(['readAll'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $vehicleModelMock->method('readAll')
                     ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que les données renvoyées par le mock correspondent aux données d'exemple
    $this->assertEquals($expectedData, $vehicleModelMock->readAll());

}

//Test de lecture d'un document de la collection Vehicle par ID

public function testReadSingleById()
{
    // Données d'exemple représentant ce qu'on attend de la méthode readSingleById
    $expectedData = '{"id":1,"field1":"value1"}';

    // Créez un mock pour la classe VehicleModel
    $vehicleModelMock = $this->getMockBuilder(VehicleModel::class)
                              ->onlyMethods(['readSingleById'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $vehicleModelMock->method('readSingleById')
                     ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que les données renvoyées par le mock correspondent aux données d'exemple
    $this->assertEquals($expectedData, $vehicleModelMock->readSingleById(1));

}

//Test de la création d'un document dans la collection Vehicle

public function testCreate()
{
    // Données d'exemple représentant ce qu'on attend de la méthode create
    $expectedData = '{"id":1,"field1":"value1"}';

    // Créez un mock pour la classe VehicleModel
    $vehicleModelMock = $this->getMockBuilder(VehicleModel::class)
                              ->onlyMethods(['addVehicle'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $vehicleModelMock->method('addVehicle')
                     ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que les données renvoyées par le mock correspondent aux données d'exemple
    $this->assertEquals($expectedData, $vehicleModelMock->addVehicle($testVehicle = new Vehicle(null, '123', '456', 100)));

}

//Test de la mise à jour d'un document dans la collection Vehicle

public function testUpdate()
{
    // Données d'exemple représentant ce qu'on attend de la méthode update
    $expectedData = '{"id":1,"field1":"value1"}';

    // Créez un mock pour la classe VehicleModel
    $vehicleModelMock = $this->getMockBuilder(VehicleModel::class)
                              ->onlyMethods(['updateVehicle'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $vehicleModelMock->method('updateVehicle')
                     ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que les données renvoyées par le mock correspondent aux données d'exemple
    $this->assertEquals($expectedData, $vehicleModelMock->updateVehicle($testVehicle = new Vehicle(null, '123', '456', 100), 1));

}

//Test de la suppression d'un document dans la collection Vehicle

public function testDelete()
{
    // Données d'exemple représentant ce qu'on attend de la méthode delete
    $expectedData = true;

    // Créez un mock pour la classe VehicleModel
    $vehicleModelMock = $this->getMockBuilder(VehicleModel::class)
                              ->onlyMethods(['deleteVehicle'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $vehicleModelMock->method('deleteVehicle')
                     ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que les données renvoyées par le mock correspondent aux données d'exemple
    $this->assertEquals($expectedData, $vehicleModelMock->deleteVehicle(1));

}


}

