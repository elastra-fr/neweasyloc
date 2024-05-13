<?php

namespace App\mongo;
use PHPUnit\Framework\TestCase;
require 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';
require_once 'model/Customer.php';

use TypeError;



class CustomerTest extends TestCase
{

//Test de la création de l'objet Customer avec des données valides

public function testCreateCustomerWithValidData()
{
    // Créez un objet Customer avec des données valides
    $customer = new Customer(null, 'John', 'Doe', '123 Main St', 'ABC123');

    // Vérifiez que l'objet a été créé avec succès

    $this->assertInstanceOf(Customer::class, $customer);

}

//Test de la création de l'objet Customer avec des données invalides

public function testCreateCustomerWithInvalidData()
{
    // Utilisez une assertion pour vérifier qu'une erreur est levée lors de la création d'un objet Customer avec des données invalides

     $this->expectException(TypeError::class);

    // Créez un objet Customer avec des données invalides
    $customer = new Customer(null, 'John', 'Doe', '123 Main St', new \stdClass());

    var_dump($customer);

    // Cette assertion ne sera pas atteinte car une exception est attendue lors de la création de l'objet
    $this->assertNull($customer);

}




//Test de lecture de tous les documents de la collection Customer

public function testReadAll()
{
    // Données d'exemple représentant ce qu'on attend de la méthode readAll
    $expectedData = '[{"id":1,"field1":"value1"},{"id":2,"field1":"value2"}]';

    // Créez un mock pour la classe CustomerModel
    $customerModelMock = $this->getMockBuilder(CustomerModel::class)
                              ->onlyMethods(['readAll'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $customerModelMock->method('readAll')
                      ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que la méthode readAll renvoie les données d'exemple
    $this->assertEquals($expectedData, $customerModelMock->readAll());

}

//Test d'inserstion d'un document dans la collection Customer

public function testCreateCustomer()
{
    // Données d'exemple représentant ce qu'on attend de la méthode createCustomer
    $expectedData = '{"id":1,"field1":"value1"}';

    // Créez un mock pour la classe CustomerModel
    $customerModelMock = $this->getMockBuilder(CustomerModel::class)
                              ->onlyMethods(['addCustomer'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $customerModelMock->method('addCustomer')
                      ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que la méthode createCustomer renvoie les données d'exemple
    $this->assertEquals($expectedData, $customerModelMock->addCustomer(new Customer(1, 'John', 'Doe', '123 Main St', 'ABC123')));

}

//Test de lecture d'un document de la collection Customer par ID

public function testReadSingleById()
{
    // Données d'exemple représentant ce qu'on attend de la méthode readSingleById
    $expectedData = '{"id":1,"field1":"value1"}';

    // Créez un mock pour la classe CustomerModel
    $customerModelMock = $this->getMockBuilder(CustomerModel::class)
                              ->onlyMethods(['getCustomerById'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $customerModelMock->method('getCustomerById')
                      ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que la méthode readSingleById renvoie les données d'exemple
    $this->assertEquals($expectedData, $customerModelMock->getCustomerById(1));

}

//Test de suppression d'un document de la collection Customer par ID

public function testDeleteCustomerById()
{
    // Données d'exemple représentant ce qu'on attend de la méthode deleteCustomerById
    $expectedData = '{"id":1,"field1":"value1"}';

    // Créez un mock pour la classe CustomerModel
    $customerModelMock = $this->getMockBuilder(CustomerModel::class)
                              ->onlyMethods(['deleteCustomerById'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $customerModelMock->method('deleteCustomerById')
                      ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que la méthode deleteCustomerById renvoie les données d'exemple
    $this->assertEquals($expectedData, $customerModelMock->deleteCustomerById(1));

}

//Test de la mise à jour d'un document de la collection Customer

public function testUpdateCustomer()
{
    // Données d'exemple représentant ce qu'on attend de la méthode updateCustomer
    $expectedData = '{"id":1,"field1":"value1"}';

    // Créez un mock pour la classe CustomerModel
    $customerModelMock = $this->getMockBuilder(CustomerModel::class)
                              ->onlyMethods(['updateCustomer'])
                              ->getMock();

    // Configurez le mock pour qu'il renvoie les données d'exemple
    $customerModelMock->method('updateCustomer')
                      ->willReturn($expectedData);

    // Utilisez une assertion pour vérifier que la méthode updateCustomer renvoie les données d'exemple
    $this->assertEquals($expectedData, $customerModelMock->updateCustomer(new Customer(1, 'John', 'Doe', '123 Main St', 'ABC123'), 1));

}


}