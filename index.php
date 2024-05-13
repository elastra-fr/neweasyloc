<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyLoc | Bibliothèque</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<header>
<?php


echo "<h1>Bibliothèque</h1>";

?>


</header>


<main>

<section>

<?php

require_once 'database/SqlSrv_con.php';
require_once 'database/MongoDb_con.php';
require_once 'model/ContractAggregator.php';



$conSqlSrv = new App\sqlsrv\SqlSrv_con();
$conSqlSrv->connect();

$conMongo = new App\mongo\MongoDB_con();
$conMongo->connect();

$aggregator = new App\sqlsrv\ContractAggregator($conSqlSrv->getConnection(), $conMongo->getDB());
$contracts = $aggregator->getContracts();

echo "<h2>Contrats</h2>";

echo $contracts;



/*

echo "<h2>Statut connexions à la base de données</h2>";

echo "<h3>Connexion à la base de données SqlServer</h3>";



$connection = new app\sqlsrv\SqlSrv_con();
$connection->connect();
//DAte de connexion
$date = date("Y-m-d H:i:s");
echo "<p>Date de connexion : ".$date."</p><br>";



echo "<h3>Connexion à la base de données Mongo</h3>";
$connectionMdb = new App\mongo\MongoDB_con();
$connectionMdb->connect();

//DAte de connexion
echo "<p>Date de connexion : ".$date."</p><br>";

if (!extension_loaded('mongodb')) {
    echo "Le module PHP MongoDB n'est pas installé.";


}

else {
    echo "Le module PHP MongoDB est installé.";
    }


if (!class_exists('MongoDB\Driver\Manager')) {
    echo "La classe MongoDB\Driver\Manager n'a pas été trouvée.";
}

else {
    echo "La classe MongoDB\Driver\Manager a été trouvée.";
    }
*/
?>

</section>

<section>
<?php

require_once 'model/Customer.php';
require_once 'model/Contract.php';
require_once 'model/Billing.php';
require_once 'model/Vehicle.php';
require_once 'model/ContractPaid.php';
/*
echo "<h2>Test Contrats en retard</h2>";

$lateContract=new App\sqlsrv\ContractModel();
$lateContract->getLateContracts();



echo "<h2>Test liste des clients</h2>";

$customers = new app\mongo\CustomerModel;
$customers->getAllCustomers();


echo "<h2>Test client par Id</h2>";


             $singleCustomer = new app\mongo\CustomerModel();
                    $id='661ff60215ef346468117b7b';
                    $singleCustomer = $singleCustomer->getCustomerById($id);

echo "<h2>Test client par nom</h2>";

$customerByName = new app\mongo\CustomerModel();
$firstName='Jean';
$lastName='Dupont';
$customerByName->searchCustomer($firstName, $lastName);
var_dump($customerByName);


echo "<h2>Test Ajout client</h2>";  
/*
$customerToAdd = new app\mongo\Customer(null, 'Jean', 'Dupont', '12 rue de la Paix', '123456789');
$addCustomer = new app\mongo\CustomerModel();
$addCustomer->addCustomer($customerToAdd);
*/


 /*


echo "<h2>Test Effacement client par Id</h2>";

$deleteCustomer = new app\mongo\CustomerModel();
$idToDelete='661ff60215ef346468117b80';
$deleteCustomer->delete($idToDelete);


echo "<h2>Test table Contract</h2>";

$contracts = new App\sqlsrv\ContractModel();
$contracts->readAll();

echo "<h2>Test contrat par Id</h2>";
$contractById = new App\sqlsrv\ContractModel(); 
$id='1';
$contractById->readSingleById($id);


echo "<h2>Test création contrat</h2>";

$contractToAdd = new App\sqlsrv\Contract(null, '123', '456', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', 100);
$addContract = new App\sqlsrv\ContractModel();
$addContract->createContract($contractToAdd);

echo "<h2>Test modification contrat par Id</h2>";

$randomPrice = rand(1, 1000);
$contractToUpdate = new App\sqlsrv\Contract(null, '123', '456', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', '2021-06-01 12:00:00', 200);
$updateContract = new App\sqlsrv\ContractModel();
$idToUpdate='1';
$updateContract->updateContract($contractToUpdate, $idToUpdate);

echo "<h2>Test effacement contrat par Id</h2>";

$deleteContract = new App\sqlsrv\ContractModel();
$idToDelete='50';
$deleteContract->delete($idToDelete);

*/

/*
require_once 'model/Customer.php';

$customers = Customer::getAllCustomers();

echo "<h2>Liste des clients</h2>";

//Affichage des clients sous forme de liste
echo $customers;

*/


?>




</section>



</main>


</body>
</html>






