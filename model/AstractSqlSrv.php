<?php

require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';

abstract class AbstractSqlSrv{

protected $table;
protected $dbcon;

public function __construct($table) {

$sqlSrvCon = new SqlSrv_con();
$this->dbcon = $sqlSrvCon;

$sqlSrvCon->connect();

$this->table = $table;

    

}


//Méthode pour récupérer tous les enregistrements de la table au format json


public function readAll()
{

    $sql = "SELECT * FROM $this->table";
    $stmt = $this->dbcon->getConnection()->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($result, JSON_PRETTY_PRINT);
    
    }

//Méthode pour récupérer un enregistrement par son ID au format json

public function readSingleById($id)
{

    $sql = "SELECT * FROM $this->table WHERE id = $id";
    $stmt = $this->dbcon->getConnection()->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($result, JSON_PRETTY_PRINT);
    
    }










}