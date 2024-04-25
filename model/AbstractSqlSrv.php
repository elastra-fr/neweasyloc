<?php

namespace App\sqlsrv;

require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';

use PDO;

abstract class AbstractSqlSrv
{

    protected $table;
    protected $dbcon;

    public function __construct($table)
    {

        $sqlSrvCon = new SqlSrv_con();
        $this->dbcon = $sqlSrvCon;

        $sqlSrvCon->connect();

        $this->table = $table;
    }

    //Méthode pour checker si une table existe dans la base de données. Si elle n'existe pas, la méthode crée la table.
    //Cette méthode est appelée au démarrage de l'outils en ligne de commande.

    public function tableExists($table, $create)
    {

        $sql = "SELECT * FROM information_schema.tables WHERE table_name = '$table'";

        $stmt = $this->dbcon->getConnection()->query($sql);

        if ($stmt->fetch() !== false) {
            
        echo "La table ".$table. " existe déjà\n";    
        return true;
    } else {

$stmt = $this->dbcon->getConnection()->query($create);

    if ($stmt) {
        echo "Table ".$table." créée avec succès\n";
    } else {
        echo "Echec de la création de la table".$table."\n";
    }

        
    }
      

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

    //Méthode pour insérer un enregistrement dans la table

    public function create($data)
    {

        $sql = "INSERT INTO $this->table (name, email, phone) VALUES (:name, :email, :phone)";
        $stmt = $this->dbcon->getConnection()->prepare($sql);
        $stmt->execute($data);
        return $this->dbcon->getConnection()->lastInsertId();
    }

    //Méthode pour effacer un enregistrement par son ID

    public function delete($id)
    {

        $sql = "DELETE FROM $this->table WHERE id = $id";
        $stmt = $this->dbcon->getConnection()->prepare($sql);
        $stmt->execute();
        return true;
    }

    //Méthode pour mettre à jour un enregistrement dans la table











}
