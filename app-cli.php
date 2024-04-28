<?php

/**Cette application lignes de commande permet de tester les fonctionnalités de la bibliothèque d'accès aux données */

require_once 'database/SqlSrv_con.php';
require_once 'database/MongoDb_con.php';
require_once 'model/Customer.php';
require_once 'model/Contract.php';
require_once 'model/Billing.php';

typewriter("\nVault-Tec Systems  v.0.7.7\n\n");
typewriter("Bienvenue dans l'application de gestion EasyLoc\n\n");

//Test de connexion à la base de données MongoDB

$connectionMdb = new App\mongo\MongoDB_con();

if ($connectionMdb->connect()) {
    typewriter("Connexion réussie à la base de données MongoDB.\n");
} else {
    typewriter("Echec de la connexion à la base de données MongoDB. Verifiez que l'extension mongodb est bien installée et activée sur votre version de PHP (CLI compris)\n");
}

//Test de connexion à la base de données SQL Server

$connection = new App\sqlsrv\SqlSrv_con();

if ($connection->connect()) {
    typewriter("Connexion réussie à la base de données SQL Server.\n");
} else {
    typewriter("Echec de la connexion à la base de données SQL Server. Verifiez que l'extension sqlsrv est bien installée et activée sur votre version de PHP (CLI compris)\n");
}


//Vérifier si la table customer existe dans la base de données SQL Server et la créer si elle n'existe pas


$contractExists = new App\sqlsrv\ContractModel();
$contractExists->createContractTable();

//Vérifier si la table Billing existe dans la base de données SQL Server et la créer si elle n'existe pas

$billingExists = new App\sqlsrv\BillingModel();
$billingExists->createBillingTable();



$menu = [
    '1' => "\033[1m\033[32m[Opérations globale sur les clients]\033[0m",
    '2' => "\033[1m\033[32m[Opérations sur un client]\033[0m",
    '3' => "\033[1m\033[32m[Opérations sur les véhicules]\033[0m",
    '4' => "\033[1m\033[32m[Opérations sur les locations]\033[0m",
    '5' => "\033[1m\033[32m[Quitter]\033[0m",
    '6' => "\033[1m\033[32m[Retour au menu principal]\033[0m"



];

while (true) {

    //echo "Sélectionnez une option :\n";
    typewriter("\n\n>>Menu principal - Sélectionnez une option>>:\n");

    foreach ($menu as $key => $value) {
        echo "{$key}. {$value}\n";
    }
    $choice = readline();

    switch ($choice) {

            //Option 1 : Opérations globale sur les clients
        case '1':


            $customers = new CustomerModel();
            $customers = $customers->getAllCustomers();








            break;

            //Option 2 : Opérations sur un client
        case '2':
            // Sous-menu pour afficher les clients
            $submenu = [
                'a' => 'Afficher tous les clients',
                'b' => 'Afficher un client par son ID',
                'c' => 'Retour au menu principal'
            ];

            while (true) {
                echo "Sous-menu - Opérations sur un client :\n";
                foreach ($submenu as $key => $value) {
                    echo "{$key}. {$value}\n";
                }
                $subChoice = readline();

                switch ($subChoice) {
                    case 'a':


                        $customers = new CustomerModel();
                        $customers = $customers->getAllCustomers();



                        break;
                    case 'b':
                        // Code pour afficher un client par son ID

                        $singleCustomer = new CustomerModel();
                        $singleCustomer = $singleCustomer->getCustomerById('661ff60215ef346468117b7b');


                        break;
                    case 'c':
                        // Retour au menu principal
                        break 2; // Sortir de la boucle imbriquée et revenir au menu principal
                    default:
                        echo "Erreur : option invalide.\n";
                }
            }
            break;



        case '3':
            // Quitter l'application
            echo "Au revoir !\n";
            sleep(1);
            exit(0);
            // break;
        case '4':
            // Retour au menu principal
            break;

        case '5':
            // Quitter l'application
            //echo "Au revoir !\n";
            typewriter("Au revoir !\n");

            sleep(1);
            exit(0);

        case '6':

            // Retour au menu principal



            break;

        default:
            echo "Erreur : option invalide.\n";
    }
}

function addCustomer()
{
    // Code pour ajouter un client
    // ...

    // Revenir au menu principal
    echo "Client ajouté avec succès.\n";
    return; // Sortir de la fonction et revenir au menu principal
}

function showAllCustomers()
{
    // Code pour afficher tous les clients
    // ...

    // Revenir au menu principal
    echo "Tous les clients ont été affichés.\n";
    return; // Sortir de la fonction et revenir au menu principal
}

function showCustomerById()
{
    // Code pour afficher un client par son ID
    // ...

    // Revenir au menu principal
    echo "Le client a été affiché.\n";
    return; // Sortir de la fonction et revenir au menu principal
}
function typewriter($text)
{
    $text = mb_convert_encoding($text, 'UTF-8');

    for ($i = 0; $i < mb_strlen($text); $i++) {
        echo "\033[1;32m" . mb_substr($text, $i, 1) . "\033[0m";
        usleep(10000); // Suspendre l'exécution du script pendant 100 microsecondes
    }
}
