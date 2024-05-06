<?php

namespace App\sqlsrv;

use MongoDB\BSON\ObjectId;
use MongoDB\Database;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Manager;
use MongoDB\Model\BSONDocument;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class ContractAggregatorTest extends TestCase
{
    private $pdoMock;
    private $mongoDbMock;
    private $contractAggregator;

    protected function setUp(): void
    {
        $this->pdoMock = m::mock(PDO::class);
        $this->mongoDbMock = m::mock(Database::class);
        $this->contractAggregator = new ContractAggregator($this->pdoMock, $this->mongoDbMock);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testGetContracts()
    {
        // Données de test
        $contracts = [
            ['id' => 1, 'vehicle_uid' => '123', 'customer_uid' => '456'],
            ['id' => 2, 'vehicle_uid' => '789', 'customer_uid' => '012']
        ];

        $customerData = [
            new BSONDocument(['_id' => new ObjectId('456'), 'first_name' => 'John', 'second_name' => 'Doe']),
            new BSONDocument(['_id' => new ObjectId('012'), 'first_name' => 'Jane', 'second_name' => 'Doe'])
        ];

        $vehicleData = [
            new BSONDocument(['_id' => new ObjectId('123'), 'licence_plate' => 'ABC-123']),
            new BSONDocument(['_id' => new ObjectId('789'), 'licence_plate' => 'DEF-456'])
        ];

        // Mock de la requête SQL
        $pdoStatementMock = m::mock(PDOStatement::class);
        $pdoStatementMock->shouldReceive('execute')->once()->andReturn(true);
        $pdoStatementMock->shouldReceive('fetchAll')->once()->with(PDO::FETCH_ASSOC)->andReturn($contracts);
        $this->pdoMock->shouldReceive('prepare')->once()->with('SELECT * FROM Contract')->andReturn($pdoStatementMock);

        // Mock de la requête MongoDB pour les clients
        $customerCursorMock = m::mock(Cursor::class);
        $customerCursorMock->shouldReceive('toArray')->once()->andReturn($customerData);
        $this->mongoDbMock->shouldReceive('Customer')->once()->andReturn(m::mock()->shouldReceive('aggregate')->once()->with([
            ['$match' => ['_id' => ['$in' => [new ObjectId('456'), new ObjectId('012')]]]],
            ['$project' => ['first_name' => 1, 'second_name' => 1]]
        ])->andReturn($customerCursorMock));

        // Mock de la requête MongoDB pour les véhicules
        $vehicleCursorMock = m::mock(Cursor::class);
        $vehicleCursorMock->shouldReceive('toArray')->once()->andReturn($vehicleData);
        $this->mongoDbMock->shouldReceive('Vehicle')->once()->andReturn(m::mock()->shouldReceive('aggregate')->once()->with([
            ['$match' => ['_id' => ['$in' => [new ObjectId('123'), new ObjectId('789')]]]],
            ['$project' => ['licence_plate' => 1]]
        ])->andReturn($vehicleCursorMock));

        // Appeler la méthode à tester
        $result = $this->contractAggregator->getContracts();

        // Vérifier le résultat
        $expected = json_encode([
            [
                'id' => 1,
                'vehicle_uid' => '123',
                'customer_uid' => '456',
                'customer_name' => 'John Doe',
                'vehicle_licence_plate' => 'ABC-123'
            ],
            [
                'id' => 2,
                'vehicle_uid' => '789',
                'customer_uid' => '012',
                'customer_name' => 'Jane Doe',
                'vehicle_licence_plate' => 'DEF-456'
            ]
        ], JSON_PRETTY_PRINT);
        $this->assertEquals($expected, $result);
    }
}
