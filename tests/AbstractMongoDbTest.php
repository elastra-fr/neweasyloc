<?php

use PHPUnit\Framework\TestCase;
require_once 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';
require_once 'model/AbstractMongoDb.php';   
use MongoDB;
use MongoDB\Con;



class AbstractMongoDbTest extends TestCase
{
    // Mock pour la classe MongoDB\Collection
    private $collectionMock;

    // Mock pour la classe MongoDB\Con
    private $mongoDBConMock;

    // Méthode exécutée avant chaque test
    protected function setUp(): void
    {
        // Initialisation des mocks
        $this->mongoDBConMock = $this->createMock(Con::class);
        $this->mongoDBConMock = $this->createMock(Con::class);
        $this->mongoDBConMock = $this->createMock(MongoDB\Con::class);
    }

    // Test de la méthode create
    public function testCreate()
    {
        $this->collectionMock->expects($this->once())
            ->method('insertOne')
            ->willReturn(new MongoDB\InsertOneResult(['insertedId' => '123']));

        $this->mongoDBConMock->expects($this->once())
            ->method('getDB')
            ->willReturn($this->collectionMock);

        $abstractMongoDb = new class('test_collection') extends AbstractMongoDb {
            public function __construct($collection)
            {
                $this->db = new stdClass();
                $this->collection = $collection;
            }
        };

        $result = $abstractMongoDb->create(['name' => 'John']);

        $this->assertEquals('123', $result);
    }

    // Test de la méthode readAll
    public function testReadAll()
    {
        $this->collectionMock->expects($this->once())
            ->method('find')
            ->willReturn(new ArrayIterator([
                ['_id' => 1, 'name' => 'John'],
                ['_id' => 2, 'name' => 'Jane'],
            ]));

        $this->mongoDBConMock->expects($this->once())
            ->method('getDB')
            ->willReturn($this->collectionMock);

        $abstractMongoDb = new class('test_collection') extends AbstractMongoDb {
            public function __construct($collection)
            {
                $this->db = new stdClass();
                $this->collection = $collection;
            }
        };

        $result = $abstractMongoDb->readAll();

        $expectedResult = json_encode([
            ['_id' => 1, 'name' => 'John'],
            ['_id' => 2, 'name' => 'Jane'],
        ], JSON_PRETTY_PRINT);
        $this->assertEquals($expectedResult, $result);
    }

    // Test de la méthode readSingleById
    public function testReadSingleById()
    {
        $this->collectionMock->expects($this->once())
            ->method('findOne')
            ->with(['_id' => new MongoDB\BSON\ObjectID('123')])
            ->willReturn(['_id' => '123', 'name' => 'John']);

        $this->mongoDBConMock->expects($this->once())
            ->method('getDB')
            ->willReturn($this->collectionMock);

        $abstractMongoDb = new class('test_collection') extends AbstractMongoDb {
            public function __construct($collection)
            {
                $this->db = new stdClass();
                $this->collection = $collection;
            }
        };

        $result = $abstractMongoDb->readSingleById('123');

        $expectedResult = json_encode(['_id' => '123', 'name' => 'John'], JSON_PRETTY_PRINT);
        $this->assertEquals($expectedResult, $result);
    }

    // Test de la méthode readSingleByFilter
    public function testReadSingleByFilter()
    {
        $this->collectionMock->expects($this->once())
            ->method('findOne')
            ->with(['name' => 'John'])
            ->willReturn(['_id' => '123', 'name' => 'John']);

        $this->mongoDBConMock->expects($this->once())
            ->method('getDB')
            ->willReturn($this->collectionMock);

        $abstractMongoDb = new class('test_collection') extends AbstractMongoDb {
            public function __construct($collection)
            {
                $this->db = new stdClass();
                $this->collection = $collection;
            }
        };

        $result = $abstractMongoDb->readSingleByFilter(['name' => 'John']);

        $expectedResult = json_encode(['_id' => '123', 'name' => 'John'], JSON_PRETTY_PRINT);
        $this->assertEquals($expectedResult, $result);
    }

    // Test de la méthode delete
    public function testDelete()
    {
        $this->collectionMock->expects($this->once())
            ->method('deleteOne')
            ->with(['_id' => new MongoDB\BSON\ObjectID('123')])
            ->willReturn(true);

        $this->mongoDBConMock->expects($this->once())
            ->method('getDB')
            ->willReturn($this->collectionMock);

        $abstractMongoDb = new class('test_collection') extends AbstractMongoDb {
            public function __construct($collection)
            {
                $this->db = new stdClass();
                $this->collection = $collection;
            }
        };

        $result = $abstractMongoDb->delete('123');

        $this->assertTrue($result);
    }

    // Test de la méthode update
    public function testUpdate()
    {
        $this->collectionMock->expects($this->once())
            ->method('updateOne')
            ->with(['_id' => new MongoDB\BSON\ObjectID('123')], ['$set' => ['name' => 'Jane']])
            ->willReturn(true);

        $this->mongoDBConMock->expects($this->once())
            ->method('getDB')
            ->willReturn($this->collectionMock);

        $abstractMongoDb = new class('test_collection') extends AbstractMongoDb {
            public function __construct($collection)
            {
                $this->db = new stdClass();
                $this->collection = $collection;
            }
        };

        $result = $abstractMongoDb->update(['_id' => new MongoDB\BSON\ObjectID('123')], ['$set' => ['name' => 'Jane']]);

        $this->assertTrue($result);
    }
}
