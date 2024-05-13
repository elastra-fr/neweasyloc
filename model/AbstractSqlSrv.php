<?php

namespace App\sqlsrv;

require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';
use Exception;
use PDO;


//Classe abstraite pour des opérations classiques sur les tables SQL Server pour permettre la réutilisation du code pour d'autres tables SQL Server
//Cette classe contient des méthodes pour insérer, lire, mettre à jour et supprimer des enregistrements dans une table SQL Server
//Les méthodes de cette classe sont utilisées par les classes de modèle pour effectuer des opérations CRUD sur les tables SQL Server
//Les classes de modèle héritent de cette classe pour effectuer des opérations CRUD sur les tables SQL Server

abstract class AbstractSqlSrv
{

//Attributs de la classe 
    protected string $table;
    protected SqlSrv_con $dbcon;

//Constructeur de la classe
    public function __construct(string $table)
    {

        $sqlSrvCon = new SqlSrv_con();
        $this->dbcon = $sqlSrvCon;

        $sqlSrvCon->connect();

        $this->table = $table;
    }

    /**************************Méthode pour checker si une table existe dans la base de données. Si elle n'existe pas, la méthode crée la table.****************/
    //Cette méthode est appelée au démarrage de l'outils en ligne de commande.


    public function tableExists(string $table, string $create)
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


    /******************Méthode pour récupérer tous les enregistrements de la table au format json*****************/
//La méthode récupère tous les enregistrements de la table et les retourne au format JSON

    public function readAll(): string
    {

        $sql = "SELECT * FROM $this->table";
        $stmt = $this->dbcon->getConnection()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result, JSON_PRETTY_PRINT);
    }

    /*******************************Méthode pour récupérer un enregistrement par filtre au format json*****************/
//La méthode récupère des enregistrements de la table par filtre et les retourne au format JSON

    public function readByFilter( ?string $where, ?string $orderBy): string
    {
       
       try{
        $sql = "SELECT * FROM $this->table";
        if ($where) {
            $sql .= " WHERE $where";
        }
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }

        //Préparation de la requête avec utilisation des marqueurs de paramètres pour éviter les injections SQL
        $stmt = $this->dbcon->getConnection()->prepare($sql);
        $params = [];
            preg_match_all('/:(\w+)/', $sql, $matches);
            foreach ($matches[1] as $param) {
                $params[":$param"] = ${$param};
            }

    
        
        $stmt->execute($params);

         // Vérification si la requête a réussi
    if ($stmt->execute() === false) {
        throw new Exception("La requête a échoué");
    }



        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérification si le résultat est vide
    if ($result === false) {

        echo "Aucun résultat\n";
        return "Aucun résultat";
    }

        return json_encode($result, JSON_PRETTY_PRINT);
       }
       
       catch(Exception $e){

        echo "Erreur: " . $e->getMessage();
        return false;       
    }

    }

    /**************************Méthode pour insérer un enregistrement dans la table*****************/

    //La méthode insère un enregistrement dans la table et retourne true si l'insertion a réussi, sinon false

    public function create(array $data) : bool
    {

        try{
        $sql = "INSERT INTO $this->table (";
        $sql .= implode(", ", array_keys($data)) . ') VALUES (';
        $sql .= ":" . implode(", :", array_keys($data)) . ')';

//Préparation de la requête avec utilisation des marqueurs de paramètres pour éviter les injections SQL
        $stmt = $this->dbcon->getConnection()->prepare($sql);
        //var_dump($data);
        //var_dump($sql);
        $stmt->execute($data);
        return true;
        }

        catch(Exception $e){

            echo "Erreur: " . $e->getMessage();
            return false;

        }

  
    }

    /***************************Méthode pour effacer un enregistrement par son ID******************/



    public function delete(string $filter) : bool
    {
try{
        $sql = "DELETE FROM $this->table WHERE $filter";
        $stmt = $this->dbcon->getConnection()->prepare($sql);
        $stmt->execute();
        echo "Contrat effacé avec succès\n";
        return true;
}

catch(Exception $e){

echo "Erreur: " . $e->getMessage();
return false;

}
    }

    /********************************Méthode pour mettre à jour un enregistrement dans la table******************/

    public function update(array $data, int $id)
    {

        try{

 //var_dump($data);
        $sql = "UPDATE $this->table SET ";
        $sql .= implode(" = ?, ", array_keys($data)) . ' = ?';
        $sql .= " WHERE id = $id";
        //Préparation de la requête avec utilisation des marqueurs de paramètres pour éviter les injections SQL
        $stmt = $this->dbcon->getConnection()->prepare($sql);
        //var_dump($sql);
        $stmt->execute(array_values($data));
        echo "Contrat n°".$id." mis à jour avec succès\n";
        return true;
        }

        catch(Exception $e){

            echo "Erreur: " . $e->getMessage();
            return false;

        }   

    }   











}
