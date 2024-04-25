<?php

namespace App\sqlsrv;

require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';
use Exception;

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

    /**************************Méthode pour checker si une table existe dans la base de données. Si elle n'existe pas, la méthode crée la table.****************/
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

        try{
        $sql = "INSERT INTO $this->table (";
        $sql .= implode(", ", array_keys($data)) . ') VALUES (';
        $sql .= ":" . implode(", :", array_keys($data)) . ')';

        $stmt = $this->dbcon->getConnection()->prepare($sql);
        $stmt->execute($data);
        return true;
        }

        catch(Exception $e){

            echo "Erreur: " . $e->getMessage();
            return false;

        }

  
    }

    //Méthode pour effacer un enregistrement par son ID

    public function delete($id)
    {
try{
        $sql = "DELETE FROM $this->table WHERE id = $id";
        $stmt = $this->dbcon->getConnection()->prepare($sql);
        $stmt->execute();
        echo "Contrat n°".$id." effacé avec succès\n";
        return true;
}

catch(Exception $e){

echo "Erreur: " . $e->getMessage();
return false;

}
    }

    //Méthode pour mettre à jour un enregistrement dans la table

    public function update($data, $id)
    {


        $sql = "UPDATE $this->table SET ";
        $sql .= implode(" = ?, ", array_keys($data)) . ' = ?';
        $sql .= " WHERE id = $id";

        $stmt = $this->dbcon->getConnection()->prepare($sql);
        $stmt->execute(array_values($data));
        echo "Contrat n°".$id." mis à jour avec succès\n";
        return true;
    }   











}
