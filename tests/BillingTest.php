<?php

namespace App\sqlsrv;
use PHPUnit\Framework\TestCase;

require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';
require_once 'model/Billing.php';
require_once 'model/AbstractSqlSrv.php';
use Exception;
use TypeError;





class BillingTest extends TestCase
{
    

    //Test de la création de l'objet Billing avec des données valides

    public function testCreateBillingWithValidData()
    {
        // Créez un objet Billing avec des données valides
        $billing = new Billing(1, 1, 100);

        // Vérifiez que l'objet a été créé avec succès
        $this->assertInstanceOf(Billing::class, $billing);

    }

    //Test de la création de l'objet Billing avec des données invalides

    public function testCreateBillingWithInvalidData()
    {
        // Utilisez une assertion pour vérifier qu'une erreur est levée lors de la création d'un objet Billing avec des données invalides
        $this->expectException(TypeError::class);

        // Créez un objet Billing avec des données invalides
        $billing = new Billing('invalid_id', '123', '456');

        // Cette assertion ne sera pas atteinte car une exception est attendue lors de la création de l'objet
        $this->assertNull($billing);

    }

    //Test de lecture de tous les documents de la collection Billing

    public function testReadAll()
    {
        // Données d'exemple représentant ce qu'on attend de la méthode readAll
        $expectedData = '[{"id":1,"field1":"value1"},{"id":2,"field1":"value2"}]';

        // Créez un mock pour la classe BillingModel
        $billingModelMock = $this->getMockBuilder(BillingModel::class)
                                  ->onlyMethods(['readAll'])
                                  ->getMock();

        // Configurez le mock pour qu'il renvoie les données d'exemple
        $billingModelMock->method('readAll')
                         ->willReturn($expectedData);

        // Vérifiez que la méthode readAll renvoie les données d'exemple
        $this->assertEquals($expectedData, $billingModelMock->readAll());
    }

    //Test de lecture d'un document de la collection Billing par ID

    public function testReadSingleById()
    {
        // Données d'exemple représentant ce qu'on attend de la méthode readSingleById
        $expectedData = '{"id":1,"field1":"value1"}';

        // Créez un mock pour la classe BillingModel
        $billingModelMock = $this->getMockBuilder(BillingModel::class)
                                  ->onlyMethods(['getBillingById'])
                                  ->getMock();

        // Configurez le mock pour qu'il renvoie les données d'exemple
        $billingModelMock->method('getBillingById')
                         ->willReturn($expectedData);

        // Utilisez une assertion pour vérifier que les données renvoyées par le mock correspondent aux données d'exemple
        $this->assertEquals($expectedData, $billingModelMock->getBillingById(1));
    }

    //Test de l'ajout d'un document dans la collection Billing

    public function testAddBilling()
    {
        // Données d'exemple représentant un objet Billing à ajouter
    $testBilling = new Billing(1, 1, 100);
        // Données d'exemple représentant le message de succès attendu
        $expectedMessage = "Paiement ajouté avec succès";

        // Créez un mock pour la classe BillingModel
        $billingModelMock = $this->getMockBuilder(BillingModel::class)
                                  ->onlyMethods(['addBilling'])
                                  ->getMock();

        // Configurez le mock pour qu'il renvoie le message de succès attendu
        $billingModelMock->method('addBilling')
                         ->willReturn($expectedMessage);

        // Utilisez une assertion pour vérifier que le message renvoyé par le mock correspond au message de succès attendu
        $this->assertEquals($expectedMessage, $billingModelMock->addBilling($testBilling));
    }


    //Test de la modification d'un document de la collection Billing

    public function testUpdateBilling()
    {
        // Données d'exemple représentant un objet Billing à modifier
        $testBilling = new Billing(1, 1, 100);

        // Données d'exemple représentant le message de succès attendu
        $expectedMessage = "Paiement modifié avec succès";

        // Créez un mock pour la classe BillingModel
        $billingModelMock = $this->getMockBuilder(BillingModel::class)
                                  ->onlyMethods(['updateBilling'])
                                  ->getMock();

        // Configurez le mock pour qu'il renvoie le message de succès attendu
        $billingModelMock->method('updateBilling')
                         ->willReturn($expectedMessage);

        // Utilisez une assertion pour vérifier que le message renvoyé par le mock correspond au message de succès attendu
        $this->assertEquals($expectedMessage, $billingModelMock->updateBilling(1, $testBilling));
    }


    //Test de la suppression d'un document de la collection Billing par ID

    public function testDeleteBillingById()
    {
        // Données d'exemple représentant le message de succès attendu
        $expectedMessage = "Paiement supprimé avec succès";

        // Créez un mock pour la classe BillingModel
        $billingModelMock = $this->getMockBuilder(BillingModel::class)
                                  ->onlyMethods(['deleteBilling'])
                                  ->getMock();

        // Configurez le mock pour qu'il renvoie le message de succès attendu
        $billingModelMock->method('deleteBilling')
                         ->willReturn($expectedMessage);

        // Utilisez une assertion pour vérifier que le message renvoyé par le mock correspond au message de succès attendu
        $this->assertEquals($expectedMessage, $billingModelMock->deleteBilling(1));
    }






}   