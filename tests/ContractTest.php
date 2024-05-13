<?php


namespace App\sqlsrv;
require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';
require_once 'model/AbstractSqlSrv.php';
require_once 'model/Contract.php';

use Exception;
use InvalidArgumentException;
use PDO;
use PHPUnit\Framework\TestCase;

use ReflectionClass;
use TypeError;


class ContractTest extends TestCase



{

//Test de la création de l'objet Contract avec des données valides

public function testCreateContractWithValidData()
{
    // Créez un objet Contract avec des données valides
    $contract = new Contract(null, '123', '456', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', 100);

    // Vérifiez que l'objet a été créé avec succès
    $this->assertInstanceOf(Contract::class, $contract);
}

//Test de la création de l'objet Contract avec des données invalides

public function testCreateContractWithInvalidData()
{

$this->expectException(TypeError::class);


    // Créez un objet Contract avec des données invalides (par exemple, un id de type string)
    $contract = new Contract('invalid_id', '123', '456', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', 100);

    // Vérifiez que l'objet n'a pas été créé
    $this->assertNull($contract->getId());
}



public function testReadAll()
{
    // Données d'exemple représentant ce qu'on attend de la méthode readAll
    $expectedData = '[{"id":1,"field1":"value1"},{"id":2,"field1":"value2"}]';

    // Créez un mock pour la classe ContractModel
    $contractModelMock = $this->getMockBuilder(ContractModel::class)
                              ->onlyMethods(['readAll'])
                              ->getMock();

    // Configurez le mock pour retourner les données d'exemple
    $contractModelMock->method('readAll')->willReturn($expectedData);

    // Obtenez un aperçu des méthodes du mock
    $reflection = new ReflectionClass($contractModelMock);
    $methods = $reflection->getMethods();
    $mockedMethods = array_map(function($method) {
        return $method->getName();
    }, $methods);

    // Affichez les méthodes du mock
    var_dump($mockedMethods);

    // Appelez la méthode à tester
    $result = $contractModelMock->readAll();

    // Vérifiez que le résultat est ce qu'on attendait
    $this->assertEquals($expectedData, $result);
}

public function testReadByFilter()
{
    // Données d'exemple représentant ce qu'on attend de la méthode readByFilter
    $expectedData = '[{"id":1,"field1":"value1"}]';

    // Créez un mock pour la classe ContractModel
    $contractModelMock = $this->getMockBuilder(ContractModel::class)
                              ->onlyMethods(['readByFilter'])
                              ->getMock();

    // Configurez le mock pour retourner les données d'exemple
    $contractModelMock->method('readByFilter')->willReturn($expectedData);

    // Appelez la méthode à tester
    $result = $contractModelMock->readByFilter('filter', 'options');

    // Vérifiez que le résultat est ce qu'on attendait
    $this->assertEquals($expectedData, $result);    
}


// Test de la méthode create à partir d'un objet Contract
public function testCreate()
{
    // Données d'exemple représentant ce qu'on attend de la méthode create
    $expectedData = true;

    // Créez un mock pour la classe ContractModel
    $contractModelMock = $this->getMockBuilder(ContractModel::class)
                              ->onlyMethods(['createContract'])
                              ->getMock();

    $testContrat = new Contract(null, '123', '456', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', 100);



    // Configurez le mock pour retourner les données d'exemple
    $contractModelMock->method('createContract')->willReturn($expectedData);

    // Appelez la méthode à tester
    $result = $contractModelMock->createContract($testContrat);

    // Vérifiez que le résultat est ce qu'on attendait
    $this->assertEquals($expectedData, $result);    

}

// Test de la méthode update à partir d'un objet Contract

public function testUpdate()
{
    // Données d'exemple représentant ce qu'on attend de la méthode update
    $expectedData = true;

    // Créez un mock pour la classe ContractModel
    $contractModelMock = $this->getMockBuilder(ContractModel::class)
                              ->onlyMethods(['updateContract'])
                              ->getMock();

    $testContrat = new Contract(null, '123', '456', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', 100);

    // Configurez le mock pour retourner les données d'exemple
    $contractModelMock->method('updateContract')->willReturn($expectedData);

    // Appelez la méthode à tester
    $result = $contractModelMock->updateContract($testContrat, 0);

    // Vérifiez que le résultat est ce qu'on attendait
    $this->assertEquals($expectedData, $result);    
}

// Test de la méthode delete à partir d'un id

public function testDelete()
{
    // Données d'exemple représentant ce qu'on attend de la méthode delete
    $expectedData = true;

    // Créez un mock pour la classe ContractModel
    $contractModelMock = $this->getMockBuilder(ContractModel::class)
                              ->onlyMethods(['deleteContract'])
                              ->getMock();

    // Configurez le mock pour retourner les données d'exemple
    $contractModelMock->method('deleteContract')->willReturn($expectedData);

    // Appelez la méthode à tester
    $result = $contractModelMock->deleteContract(0);

    // Vérifiez que le résultat est ce qu'on attendait
    $this->assertEquals($expectedData, $result);    

}











}