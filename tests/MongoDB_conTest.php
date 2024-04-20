<?php


require_once 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';


use MongoDB\Client;
use PHPUnit\Framework\TestCase;
use MongoDB\Database;
use MongoDB\Driver\Manager;
class MongoDB_conTest extends TestCase
{
    private MongoDB_con $mongoDBCon;

    protected function setUp(): void
    {
        $this->mongoDBCon = new MongoDB_con('localhost', 27017);
    }

    public function testConnect()
    {
        $client = new Client("mongodb://localhost:27017");
        $this->assertInstanceOf(Client::class, $client);

        $db = $this->mongoDBCon->getDB();
        $this->assertInstanceOf(\MongoDB\Database::class, $db);
    }
}

