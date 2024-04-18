<?php
//require_once 'database/MongoDb_con.php';
//require_once 'model/Customer.php';
//require_once 'vendor/autoload.php';
typewriter("\nVault-Tec Systems - Copyright 2077\n\n");
typewriter("Bienvenue dans l'application de gestion EasyLoc\n\n");


//echo "\nVault-Tec Systems - Copyright 2024\n\n";
//echo "Bienvenue dans l'application de gestion EasyLoc\n\n";


$menu = [
    '1' => "\033[1m\033[32m[Opérations globale sur les clients]\033[0m",
    '2' => "\033[1m\033[32m[Opérations sur un client]\033[0m",
    '3' => "\033[1m\033[32m[Opérations sur les véhicules]\033[0m",
    '4' => "\033[1m\033[32m[Opérations sur les locations]\033[0m",
    
];

while (true) {
echo "\033[1m\033[32m[Opérations sur les locations]\033[0m\n";
    foreach ($menu as $key => $value) {
        echo "{$key}. {$value}\n";
    }
    $choice = readline();

    switch ($choice) {

        //Option 1 : Opérations globale sur les clients
        case '1':

        require_once 'model/Customer.php';
        $customers = Customer::getAllCustomers();
        var_dump($customers);
            






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
    for ($i = 0; $i < strlen($text); $i++) {
        echo "\033[1;32m" . $text[$i] . "\033[0m";
        usleep(10000); // Suspendre l'exécution du script pendant 100 microsecondes
    }
}
