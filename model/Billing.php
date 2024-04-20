<?php

namespace App\sqlsrv;
require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';



//Définition de la classe Billing avec ses getters et setters

class Billing{



}


//Définition de la classe BillingModel qui hérite de la classe AbstractSqlSrv
//Cette classe permet d'effectuer des opérations CRUD sur la table billing de la base de données SQL Server
//Elle hérite de la classe AbstractSqlSrv pour réutiliser les méthodes de cette classe pour effectuer des opérations CRUD sur la table billing
//Elle contient également des méthodes pour effectuer des opérations moins standard sur la table billing

class BillingModel extends AbstractSqlSrv
{

public function __construct() {
    parent::__construct('billing');

}





}


