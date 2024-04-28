<?php
//Classe de connexion à la base de données SQL Server


namespace App\sqlsrv;

use PDO;
use PDOException;

require_once 'config/globals.php';


class SqlSrv_con
{

    //Attributs de la classe

    private $server;
    private $host;
    private $user;
    private $password;
    private $database;
    private $connection;


    //Constructeur de la classe
    public function __construct(PDO $pdo = null)
    {

        $this->server = SQLSRV_SERVER;
        $this->host = SQLSRV_HOST;
        $this->user = SQLSRV_USER;
        $this->password = SQLSRV_PASSWORD;
        $this->database = SQLSRV_DATABASE;
        $this->connection = $pdo;
    }

    //Méthode pour se connecter à la base de données
    public function connect()
    {

        if ($this->connection instanceof PDO) {
            return true;
        }

        $dsn = "sqlsrv:Server=$this->server;Database=$this->database";

        try {

            $this->connection = new PDO($dsn, $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connexion réussie !";
            return true;
        } catch (PDOException $e) {
            echo "Echec de la connexion : " . $e->getMessage();
            return false;
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
