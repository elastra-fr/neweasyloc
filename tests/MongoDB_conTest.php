<?php


require_once 'vendor/autoload.php';
require_once 'database/MongoDb_con.php';


use MongoDB\Client;
use PHPUnit\Framework\TestCase;
use MongoDB\Database;
use MongoDB\Driver\Manager;
class MongoDB_conTest extends TestCase
{
    private App\mongo\MongoDB_con $mongoDBCon;

    

    protected function setUp(): void
    {
        $this->mongoDBCon = new App\mongo\MongoDB_con('localhost', 27017);
    }

    public function testConnect()
    {
        $client = new Client("mongodb://localhost:27017");
        $this->assertInstanceOf(Client::class, $client, 'Client doit retourner une instance de MongoDB\Client');

        $db = $this->mongoDBCon->getDB();
        $this->assertInstanceOf(\MongoDB\Database::class, $db, 'getDB doit retourner une instance de MongoDB\Database');
    }
}

