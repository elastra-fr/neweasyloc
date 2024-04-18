<?php

require_once 'config/globals.php';
require_once 'database/SqlSrv_con.php';

class SqlSrv_conTest extends PHPUnit\Framework\TestCase
{
    public function testConnection()
    {
        // Création d'un stub de la classe PDO
        $pdoStub = $this->createStub(PDO::class);
        
        // Création d'une instance de SqlSrv_con
        $sqlSrvCon = new SqlSrv_con($pdoStub);
        
        // Appel de la méthode connect
        $connected = $sqlSrvCon->connect();
        
        // Assertions 
        $this->assertTrue($connected, 'Connection doit retourner true si la connexion est établie');
        $this->assertInstanceOf(PDO::class, $sqlSrvCon->getConnection(), 'getConnection doit retourner une instance de PDO');
    }
}