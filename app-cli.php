<?php

typewriter("\nVault-Tec Systems  v.0.7.7\n\n");
typewriter("Bienvenue dans l'application de gestion EasyLoc\n\n");




require_once 'database/SqlSrv_con.php';
require_once 'database/MongoDb_con.php';

//Test de connexion à la base de données MongoDB

$connectionMdb = new MongoDB_con();

if ($connectionMdb->connect()) {
    typewriter("Connexion réussie à la base de données MongoDB.\n");
} else {
    typewriter("Echec de la connexion à la base de données MongoDB. Verifiez que l'extension mongodb est bien installée et activée sur votre version de PHP (CLI compris)\n");
}

//Test de connexion à la base de données SQL Server

$connection = new SqlSrv_con();

if ($connection->connect()) {
    typewriter("Connexion réussie à la base de données SQL Server.\n");
} else {
    typewriter("Echec de la connexion à la base de données SQL Server. Verifiez que l'extension sqlsrv est bien installée et activée sur votre version de PHP (CLI compris)\n");
}





$menu = [
    '1' => "\033[1m\033[32m[Opérations globale sur les clients]\033[0m",
    '2' => "\033[1m\033[32m[Opérations sur un client]\033[0m",
    '3' => "\033[1m\033[32m[Opérations sur les véhicules]\033[0m",
    '4' => "\033[1m\033[32m[Opérations sur les locations]\033[0m",
    
];

while (true) {

    //echo "Sélectionnez une option :\n";
    typewriter("\n\nSélectionnez une option :\n");  

    foreach ($menu as $key => $value) {
        echo "{$key}. {$value}\n";
    }
    $choice = readline();

    switch ($choice) {

        //Option 1 : Opérations globale sur les clients
        case '1':

        require_once 'model/Customer.php';
        $customers = Customer::getAllCustomers();
        echo $customers;
            






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
                echo "Sélectionnez une option :\n";
                foreach ($submenu as $key => $value) {
                    echo "{$key}. {$value}\n";
                }
                $subChoice = readline();

                switch ($subChoice) {
                    case 'a':
                        // Code pour afficher tous les clients
                        showAllCustomers();
                        break;
                    case 'b':
                        // Code pour afficher un client par son ID
                        showCustomerById();
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
function typewriter($text) {
    $text = mb_convert_encoding($text, 'UTF-8');

    for ($i = 0; $i < mb_strlen($text); $i++) {
        echo "\033[1;32m" . mb_substr($text, $i, 1) . "\033[0m";
        usleep(50000); // Suspendre l'exécution du script pendant 100 microsecondes
    }
}

