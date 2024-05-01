<?php

/**Cette application lignes de commande permet de tester les fonctionnalités de la bibliothèque d'accès aux données */


require_once 'database/SqlSrv_con.php';
require_once 'database/MongoDb_con.php';
require_once 'model/Customer.php';
require_once 'model/Contract.php';
require_once 'model/Billing.php';
require_once 'model/Vehicle.php';
require_once 'model/ContractPaid.php';

/*********************Méthode globales**********************/
function typewriter($text)
{
    $text = mb_convert_encoding($text, 'UTF-8');

    for ($i = 0; $i < mb_strlen($text); $i++) {
        echo "\033[1;32m" . mb_substr($text, $i, 1) . "\033[0m";
        usleep(8000); // Suspendre l'exécution du script pendant 100 microsecondes
    }
}

function clearScreen()
{
    // Effacer l'écran
    echo "\033[2J";
    // Retourner le curseur en haut à gauche
    echo "\033[0;0H";
}

function getMeOutOfHere()
{


    typewriter("Au revoir !\n");
    sleep(1);
    exit(0);
}




/********************************************************/


clearScreen();
typewriter("\nVAULT-TEC SYSTEMS  V. 2.0.7.7\n\n");
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
typewriter("Vérification de l'existence de la table Contract dans la base de données SQL Server...\n");
$contractExists->createContractTable();

//Vérifier si la table Billing existe dans la base de données SQL Server et la créer si elle n'existe pas

$billingExists = new App\sqlsrv\BillingModel();
typewriter("Vérification de l'existence de la table Billing dans la base de données SQL Server...\n");
$billingExists->createBillingTable();


$menu = [
    '1' => "\033[1m\033[32m[Requêtes générales]\033[0m",
    '2' => "\033[1m\033[32m[Requêtes sur les contrats]\033[0m",
    '3' => "\033[1m\033[32m[Requêtes sur les clients]\033[0m",
    '4' => "\033[1m\033[32m[Requêts sur les véhicules]\033[0m",
    '5' => "\033[1m\033[32m[Requêtes sur les paiements]\033[0m",
    '6' => "\033[1m\033[32m[Quitter l'application]\033[0m"



];

while (true) {

    //echo "Sélectionnez une option :\n";
    typewriter("\n\n>>Menu principal - Sélectionnez une option>>:\n");

    foreach ($menu as $key => $value) {
        echo "{$key}. {$value}\n";
    }
    $choice = readline();


    switch ($choice) {

            //Option 1 : Reqûetes générales
        case '1':

            //Afficher le sous-menu pour les requêtes générales

            $submenu = [

                'a' => "Liste des locations en retard",
                'b' => "Liste des locations non payées",
                'c' => "Nombre de retards entre deux dates données",
                'd' => "Obtenir le nombre de retard moyen par client",
                'e' => "Obtenir la moyenne du temps de retard par véhicule",
                'f' => "Obtenir tous les contrats regroupés par véhicules",
                'g' => "Obtenir tous les contrats regroupés par clients",
                'h' => "Retour au menu principal"




            ];

            while (true) {
                echo "         Sous-menu - Requêtes générales :\n";
                foreach ($submenu as $key => $value) {
                    echo "          {$key}. {$value}\n";
                }

                $subChoice = readline();

                clearScreen();

                switch ($subChoice) {

                    case 'a':
                        //Liste des locations en retard

                        typewriter("Liste des locations en retard : \n");
                        $lateContract = new App\sqlsrv\ContractModel();
                        $lateContract->getLateContracts();






                        break;

                    case 'b':

                        //Liste des locations non payées

                        typewriter("Liste des locations non payées");
                        $unpaidLocations = new App\sqlsrv\ContractPaid(null, null, null);
                        $unpaidLocations->getUnpaidContractsWithCustomerData();



                        break;

                    case 'c':

                        //Nombre de retards entre deux dates données

                        break;

                    case 'd':

                        //Obtenir le nombre de retard moyen par client

                        break;

                    case 'e':

                        //Obtenir la moyenne du temps de retard par véhicule

                        break;


                    case 'f':

                        //Obtenir tous les contrats regroupés par véhicules

                        break;

                    case 'g':

                        //Obtenir tous les contrats regroupés par clients

                        break;

                    case 'h':

                        //Retour au menu principal

                        clearScreen();

                        break 2;

                    default:

                        echo "Saisie invalide";
                }
            }


            break;

            //Option 2 : Requêtes sur les contrats
        case '2':
            // Sous-menu pour afficher les clients
            $submenu = [
                'a' => 'Créer un contrat à la date actuelle',
                'b' => 'Créer un contrat à une autre date',
                'c' => 'Rechercher un contrat par son ID',
                'd' => 'Afficher tous les contrats et effectuer des opérations sur un contrat spécifique',
                'e' => 'Retour au menu principal',
                'f' => 'Quitter l\'application'
            ];

            while (true) {
                echo "Sous-menu - Requêtes sur les contrats :\n";
                foreach ($submenu as $key => $value) {
                    echo "{$key}. {$value}\n";
                }

                $subChoice = readline();

                clearScreen();

                switch ($subChoice) {

                    case 'a':
                        //Créer un contrat à la date actuelle
                        typewriter("Création d'un contrat à la date actuelle\n");

                        //Création d'un contrat

                        // Demander à l'utilisateur de saisir l'UID du véhicule
                        $vehicleUid = readline('Entrez l\'UID du véhicule : ');

                        // Demander à l'utilisateur de saisir l'UID du client
                        $customerUid = readline('Entrez l\'UID du client : ');

                        //DAte de signature du contrat correspond à la date actuelle

                        $signDatetime = date('Y-m-d H:i:s');

                        // Demander à l'utilisateur de saisir la date et l'heure de début de la location
                        $locBeginDatetime = readline('Entrez la date et l\'heure de début de la location (format : Y-m-d H:i:s) : ');

                        // Demander à l'utilisateur de saisir la date et l'heure de fin de la location
                        $locEndDatetime = readline('Entrez la date et l\'heure de fin de la location (format : Y-m-d H:i:s) : ');

                        // Demander à l'utilisateur de saisir la date et l'heure de retour du véhicule (peut être null)
                        $returningDatetime = readline('Entrez la date et l\'heure de retour du véhicule (format : Y-m-d H:i:s) ou laissez vide si non applicable : ');
                        if ($returningDatetime === '') {
                            $returningDatetime = null;
                        }



                        // Demander à l'utilisateur de saisir le prix de la location
                        $price = readline('Entrez le prix de la location : ');

                        // Créer un nouvel objet Contract avec les données saisies par l'utilisateur
                        $contract = new App\sqlsrv\Contract(null, $vehicleUid, $customerUid, $signDatetime, $locBeginDatetime, $locEndDatetime, $returningDatetime, $price);


                        // Appeler la méthode createContract() pour insérer le contrat dans la base de données
                        $contractModel = new App\sqlsrv\ContractModel();
                        $contractId = $contractModel->createContract($contract);

                        // Afficher un message de confirmation avec l'ID du contrat inséré
                        echo "Le contrat a été créé avec l'ID $contractId.\n";



                        break;

                    case 'b':
                        //Créer un contrat à une autre date
                        typewriter("Création d'un contrat à une autre date\n");

                        break;

                    case 'c':
                        //Rechercher un contrat par son ID
                        typewriter("Recherche d'un contrat par son ID\n");

                        // Demander à l'utilisateur de saisir l'ID du contrat à rechercher
                        $contractId = readline('Entrez l\'ID du contrat à rechercher : ');

                        // Créer un nouvel objet ContractModel
                        $contract = new App\sqlsrv\ContractModel();

                        // Récupérer les données du contrat
                        $contractData = $contract->readSingleById($contractId);

                        $contractData = json_decode($contractData, true); // Convertir l'objet JSON en tableau associatif PHP

                        // Afficher les données du contrat
                        echo "Données du contrat :\n";


                        var_dump($contractData);
                        print_r($contractData);



                        $vehicleId = $contractData['vehicle_uid']; // Récupérer l'ID du véhicule
                        $customerId = $contractData['customer_uid']; // Récupérer l'ID du client
                        //Recherche des données du client 
                        $customer = new App\mongo\CustomerModel();
                        echo "Customer ID : $customerId\n";
                        $id = '661ff60215ef346468117b7b';

                        //test si $customerId est égal à $id
                        if ($customerId === $id) {
                            echo "Les deux valeurs sont égales\n";
                        } else {
                            echo "Les deux valeurs ne sont pas égales\n";
                        }
                        $customerData = $customer->getCustomerById($id);
                        $customerData = json_decode($customerData, true); // Convertir l'objet JSON en tableau associatif PHP
                        print_r($customerData);
                        $customerName = $customerData['first_name']; // Récupérer le prénom du client
                        $customerLastName = $customerData['second_name']; // Récupérer le nom du client


                        echo "Le client concerné est : $customerName $customerLastName\n";
                        echo "Le véhicule concerné est : $vehicleId\n";



                        break;

                    case 'd':
                        //Afficher tous les contrats et effectuer des opérations sur un contrat spécifique
                        typewriter("Afficher tous les contrats et effectuer des opérations sur un contrat spécifique\n");

                        // Récupérer tous les contrats
                        $contract = new App\sqlsrv\ContractModel();
                        $contracts = $contract->readAll();

                        // Convertir les données des contrats en tableau associatif PHP
                        $contracts = json_decode($contracts, true);


                        //Afficher les contrats
                        echo "Liste des contrats :\n";


                        // Parcourir le tableau des contrats
                        foreach ($contracts as $contract) {
                            // Afficher l'ID et les informations du contrat
                            echo "ID : {$contract['id']} - Début : {$contract['loc_begin_datetime']} - Fin : {$contract['loc_end_datetime']}\n";
                        }

                        echo "\n";

                        // Demander à l'utilisateur de saisir l'ID du contrat
                        $contractId = readline("Entrez l'ID du contrat à sélectionner : ");

                        // Vérifier que l'ID saisi est valide
                        $selectedContract = null;
                        foreach ($contracts as $contract) {
                            if ($contract['id'] == $contractId) {
                                $selectedContract = $contract;
                                break;
                            }
                        }

                        // Vérifier que l'utilisateur a sélectionné un contrat valide
                        if ($selectedContract === null) {
                            echo "Le contrat sélectionné n'existe pas.\n";
                        } else {

                            // Récupérer les informations du contrat sélectionné
                            $contractId = $selectedContract['id'];
                            //$customerName = $selectedContract['customer_name'];
                            $locBeginDatetime = $selectedContract['loc_begin_datetime'];
                            $locEndDatetime = $selectedContract['loc_end_datetime'];

                            clearScreen();
                            // Afficher les informations du contrat sélectionné
                            echo "Contrat sélectionné :\n";
                            echo "ID : {$contractId}\n";
                            //echo "Client : {$customerName}\n";
                            echo "Début : {$locBeginDatetime}\n";
                            echo "Fin : {$locEndDatetime}\n";
                            echo "\n";

                            // Afficher les opérations possibles sur le contrat
                            $operations = [
                                'a' => 'Modifier le contrat',
                                'b' => 'Supprimer le contrat',
                                'c' => 'Lister les paiements effectués pour ce contrat',
                                'd' => 'Vérifier si le contrat a été intégralement payé',
                                'e' => 'Retour au menu précédent',
                                'f' => 'Quitter l\'application'
                            ];

                            while (true) {
                                echo "Opérations possibles sur le contrat :\n";
                                foreach ($operations as $key => $value) {
                                    echo "{$key}. {$value}\n";
                                }

                                $operation = readline("Sélectionnez une opération : ");

                                switch ($operation) {
                                    case 'a':
                                        // Modifier le contrat
                                        typewriter("Modification du contrat\n");



                                        // Demander à l'utilisateur de saisir la nouvelle date et heure de début de location
                                        $newLocBeginDatetime = readline("Entrez la nouvelle date et heure de début de location (format : Y-m-d H:i:s) : ");

                                        // Demander à l'utilisateur de saisir la nouvelle date et heure de fin de location
                                        $newLocEndDatetime = readline("Entrez la nouvelle date et heure de fin de location (format : Y-m-d H:i:s) : ");

                                        // Modifier le contrat dans la base de données
                                        $contractModel = new App\sqlsrv\ContractModel();
                                        $contractModel->updateContract($contractId, $newLocBeginDatetime, $newLocEndDatetime);

                                        // Afficher un message de confirmation
                                        echo "Le contrat a été modifié.\n";

                                        break;

                                    case 'b':
                                        // Supprimer le contrat
                                        typewriter("Suppression du contrat\n");

                                        // Demander à l'utilisateur de confirmer la suppression
                                        $confirm = readline("Voulez-vous vraiment supprimer ce contrat ? (o/n) : ");

                                        if ($confirm === 'o') {
                                            // Supprimer le contrat de la base de données
                                            $contractModel = new App\sqlsrv\ContractModel();
                                            $contractModel->deleteContract($contractId);

                                            // Afficher un message de confirmation
                                            echo "Le contrat a été supprimé.\n";
                                        } else {
                                            echo "La suppression a été annulée.\n";
                                        }

                                        break;

                                    case 'c':
                                        // Lister les paiements effectués pour ce contrat
                                        typewriter("Liste des paiements effectués pour ce contrat\n");

                                        // Récupérer les paiements effectués pour le contrat
                                        $billing = new App\sqlsrv\BillingModel();
                                        $billings = $billing->readAllByContractId($contractId);

                                        // Convertir les données des paiements en tableau associatif PHP
                                        $billings = json_decode($billings, true);

                                        // Afficher les paiements effectués pour le contrat

                                        echo "Paiements effectués pour le contrat :\n";

                                        // Parcourir le tableau des paiements
                                        foreach ($billings as $billing) {
                                            // Afficher l'ID et le montant du paiement
                                            echo "ID : {$billing['id']} - Montant : {$billing['amount']} - Date : {$billing['billing_datetime']}\n";
                                        }

                                        break;

                                    case 'd':

                                        // Vérifier si le contrat a été intégralement payé
                                        typewriter("Vérification si le contrat a été intégralement payé\n");

                                        // Créer un nouvel objet ContractPaid
                                        $contractPaid = new App\sqlsrv\ContractPaid(null, null, null);

                                        // Appeler la méthode isContractPaid() pour vérifier si le contrat a été intégralement payé
                                        $contractPaid->isContractPaid($contractId);

                                        break;

                                    case 'e':

                                        // Retour au menu principal
                                        clearScreen();
                                        break 3;

                                    case 'f':

                                        getMeOutOfHere();
                                        // Quitter l'application
                                        /*typewriter("Au revoir !\n");
                                        sleep(1);
                                        exit(0);*/

                                    default:


                                        break;
                                }
                            }
                        }

                    case 'e':
                        //Retour au menu principal
                        clearScreen();
                        break 2;

                    case 'f':

                        //Quitter l'application
                        /*typewriter("Au revoir !\n");
                        sleep(1);
                        exit(0);*/

                        getMeOutOfHere();


                    default:
                        echo "Saisie invalide\n";
                }
            }


            break;


            //Option 3 : Requêtes sur les clients
        case '3':

            // Sous-menu pour les requêtes sur les clients

            $submenu = [

                'a' => "Créer un client",
                'b' => "Rechercher un client par nom et prénom",
                'c' => "Afficher tous les clients",
                'd' => "Retour au menu principal",
                'e' => "Quitter l'application"

            ];


            while (true) {
                echo "Sous-menu - Requêtes sur les clients :\n";
                foreach ($submenu as $key => $value) {
                    echo "{$key}. {$value}\n";
                }

                $subChoice = readline();

                clearScreen();

                switch ($subChoice) {

                    case 'a':
                        //Créer un client
                        typewriter("Création d'un client\n");

                        // Demander à l'utilisateur de saisir le prénom du client
                        $firstName = readline('Entrez le prénom du client : ');

                        // Demander à l'utilisateur de saisir le nom du client
                        $secondName = readline('Entrez le nom du client : ');

                        // Demander à l'utilisateur de saisir l'adresse du client
                        $address = readline('Entrez l\'adresse du client : ');

                        // Demander à l'utilisateur de saisir le numéro de permis du client
                        $permitNumber = readline('Entrez le numéro de permis du client : ');

                        // Créer un nouvel objet Customer avec les données saisies par l'utilisateur
                        $customer = new App\mongo\Customer(null, $firstName, $secondName, $address, $permitNumber);

                        // Appeler la méthode createCustomer() pour insérer le client dans la base de données
                        $customerModel = new App\mongo\CustomerModel();
                        $customerId = $customerModel->createCustomer($customer);

                        // Afficher un message de confirmation avec l'ID du client inséré
                        echo "Le client a été créé avec l'ID $customerId.\n";

                        break;

                    case 'b':
                        //Rechercher un client par nom et prénom
                        typewriter("Recherche d'un client par nom et prénom\n");

                        // Demander à l'utilisateur de saisir le prénom du client
                        $firstName = readline('Entrez le prénom du client : ');

                        // Demander à l'utilisateur de saisir le nom du client
                        $secondName = readline('Entrez le nom du client : ');

                        // Créer un nouvel objet CustomerModel
                        $customer = new App\mongo\CustomerModel();

                        // Récupérer les données du client
                        $customerData = $customer->readByFilter("first_name = '$firstName' AND second_name = '$secondName'");
                        $customerData = json_decode($customerData, true); // Convertir l'objet JSON en tableau associatif PHP

                        // Afficher les données du client
                        echo "Données du client :\n";
                        print_r($customerData);

                        break;

                    case 'c':

                        //Afficher tous les clients et effectuer des opérations sur un client spécifique
                        typewriter("Affichage de tous les clients\n");

                        // Récupérer tous les clients
                        $customer = new App\mongo\CustomerModel();
                        $customersJson = $customer->readAll();
                        $customersArray = json_decode($customersJson, true);



                        // Afficher les clients
                        echo "Liste des clients :\n";


                        // Parcourir le tableau des clients et l'afficher sous forme de menu avec lettre de sélection


                        $i = 1;
                        foreach ($customersArray as $customer) {
                            // Récupérer la valeur de $oid à partir de l'objet _id
                            $oid = $customer['_id']['$oid'];

                            echo "{$i}. {$customer['first_name']} {$customer['second_name']} (ID: {$oid})\n";
                            $i++;
                        }

                        // Demander à l'utilisateur de saisir le numéro du client à sélectionner
                        $customerNumber = readline("Entrez le numéro du client à sélectionner : ");

                        // Récupérer l'ID du client sélectionné

                        // Vérifier si la sélection est valide et récupérer l'ID du client correspondant
                        $selectedCustomerId = null;
                        $selectedCustomerName = null;
                        if (is_numeric($customerNumber) && $customerNumber >= 1 && $customerNumber < count($customersArray) + 1) {
                            // Récupérer la valeur de $oid à partir de l'objet _id
                            $selectedCustomerId = $customersArray[$customerNumber - 1]['_id']['$oid'];
                            $selectedCustomerName = $customersArray[$customerNumber - 1]['first_name'] . ' ' . $customersArray[$customerNumber - 1]['second_name'];

                            clearScreen();

                            echo "Vous avez sélectionné le client : {$selectedCustomerName}\n";
                            echo "Ce client a l'ID : {$selectedCustomerId}\n";
                            echo "\n";
                            // Afficher les opérations possibles sur le client

                            $operations = [
                                'a' => 'Modifier le client',
                                'b' => 'Supprimer le client',
                                'c' => 'Retour au menu précédent',
                                'd' => 'Quitter l\'application'
                            ];

                            while (true) {
                                echo "Opérations possibles sur le client :\n";

                                foreach ($operations as $key => $value) {
                                    echo "{$key}. {$value}\n";
                                }

                                echo "\n";

                                $operation = readline("Sélectionnez une opération : ");

                                switch ($operation) {
                                    case 'a':
                                        // Modifier le client
                                        typewriter("Modification du client\n");

                                        // Demander à l'utilisateur de saisir le nouveau prénom du client
                                        $newFirstName = readline("Entrez le nouveau prénom du client : ");

                                        // Demander à l'utilisateur de saisir le nouveau nom du client
                                        $newSecondName = readline("Entrez le nouveau nom du client : ");

                                        // Demander à l'utilisateur de saisir la nouvelle adresse du client
                                        $newAddress = readline("Entrez la nouvelle adresse du client : ");

                                        // Demander à l'utilisateur de saisir le nouveau numéro de permis du client
                                        $newPermitNumber = readline("Entrez le nouveau numéro de permis du client : ");

                                        // Créer un nouvel objet Customer avec les données saisies par l'utilisateur
                                        $newCustomer = new App\mongo\Customer($selectedCustomerId, $newFirstName, $newSecondName, $newAddress, $newPermitNumber);

                                        // Appeler la méthode updateCustomer() pour modifier le client dans la base de données
                                        $customerModel = new App\mongo\CustomerModel();
                                        $customerModel->updateCustomer($newCustomer);

                                        // Afficher un message de confirmation
                                        echo "Le client a été modifié.\n";

                                        break;

                                    case 'b':
                                        // Supprimer le client
                                        typewriter("Suppression du client\n");

                                        // Demander à l'utilisateur de confirmer la suppression
                                        $confirm = readline("Voulez-vous vraiment supprimer ce client ? (o/n) : ");

                                        if ($confirm === 'o') {
                                            // Supprimer le client de la base de données
                                            $customerModel = new App\mongo\CustomerModel();
                                            $customerModel->deleteCustomer($selectedCustomerId);

                                            // Afficher un message de confirmation
                                            echo "Le client a été supprimé.\n";
                                        } else {
                                            echo "La suppression a été annulée.\n";
                                        }

                                        break;

                                    case 'c':
                                        // Retour au menu précédent

                                        clearScreen();

                                        break 3;

                                    case 'd':

                                        // Quitter l'application
                                        /*typewriter("Au revoir !\n");
                sleep(1);
                exit(0);
*/
                                        getMeOutOfHere();
                                    default:

                                        echo "Saisie invalide\n";
                                }
                            }
                        } else {
                            echo "Saisie invalide\n";
                        }



                        /*
                        foreach ($customers as $customer) {
                            // Afficher l'ID et les informations du client
                            echo "ID : {$customer['_id']} - Prénom : {$customer['first_name']} - Nom : {$customer['second_name']} - Adresse : {$customer['address']} - Numéro de permis : {$customer['permit_number']}\n";
                        }
*/
                        break;

                    case 'd':

                        //Retour au menu principal
                        clearScreen();
                        break 2;

                    case 'e':

                        //Quitter l'application
                        /*    typewriter("Au revoir !\n");
                        sleep(1);
                        exit(0);*/
                        getMeOutOfHere();

                    default:

                        echo "Saisie invalide\n";
                }
            }




            //Option 4 : Requêtes sur les véhicules    
        case '4':

            // Sous-menu pour les requêtes sur les véhicules

            $submenu = [

                'a' => "Créer un véhicule",
                'b' => "Rechercher un véhicule par son immatriculation",
                'c' => "Rechercher un véhicule ave un kilométrage inférieur à une valeur donnée",
                'd' => "Rechercher un véhicule avec un kilométrage supérieur à une valeur donnée",
                'e' => "Afficher tous les véhicules",
                'f' => "Retour au menu principal",
                'g' => "Quitter l'application"

            ];


            while (true) {
                echo "Sous-menu - Requêtes sur les véhicules :\n";
                foreach ($submenu as $key => $value) {
                    echo "{$key}. {$value}\n";
                }

                $subChoice = readline();

                clearScreen();

                switch ($subChoice) {

                    case 'a':
                        //Créer un véhicule
                        typewriter("Création d'un véhicule\n");

                        // Demander à l'utilisateur de saisir le numéro d'immatriculation du véhicule
                        $registrationNumber = readline('Entrez le numéro d\'immatriculation du véhicule : ');

                        // Demander à l'utilisateur de saisir la marque du véhicule
                        $brand = readline('Entrez la marque du véhicule : ');

                        // Demander à l'utilisateur de saisir le modèle du véhicule
                        $model = readline('Entrez le modèle du véhicule : ');

                        // Demander à l'utilisateur de saisir le kilométrage du véhicule
                        $mileage = readline('Entrez le kilométrage du véhicule : ');

                        // Créer un nouvel objet Vehicle avec les données saisies par l'utilisateur
                        $vehicle = new App\sqlsrv\Vehicle(null, $registrationNumber, $brand, $model, $mileage);

                        // Appeler la méthode createVehicle() pour insérer le véhicule dans la base de données
                        $vehicleModel = new App\sqlsrv\VehicleModel();
                        $vehicleId = $vehicleModel->createVehicle($vehicle);

                        // Afficher un message de confirmation avec l'ID du véhicule inséré
                        echo "Le véhicule a été créé avec l'ID $vehicleId.\n";

                        break;

                    case 'b':
                        //Rechercher un véhicule par son immatriculation
                        typewriter("Recherche d'un véhicule par son immatriculation\n");

                        // Demander à l'utilisateur de saisir le numéro d'immatriculation du véhicule
                        $registrationNumber = readline('Entrez le numéro d\'immatriculation du véhicule : ');

                        // Créer un nouvel objet VehicleModel
                        $vehicle = new App\sqlsrv\VehicleModel();

                        // Récupérer les données du véhicule

                        $vehicleData = $vehicle->readSingleByFilter(['licence_plate' => $registrationNumber]);

                        $vehicleData = json_decode($vehicleData, true); // Convertir l'objet JSON en tableau associatif PHP

                        // Afficher les données du véhicule

                        echo "Données du véhicule :\n";

                        print_r($vehicleData);

                        break;

                    case 'c':
                        //Rechercher un véhicule avec un kilométrage inférieur à une valeur donnée
                        typewriter("Recherche d'un véhicule avec un kilométrage inférieur à une valeur donnée\n");

                        // Demander à l'utilisateur de saisir la valeur du kilométrage
                        $mileage = readline('Entrez la valeur du kilométrage : ');

                        // Créer un nouvel objet VehicleModel
                        $vehicle = new App\sqlsrv\VehicleModel();

                        // Récupérer les données des véhicules
                        $vehicles = $vehicle->searchVehicleByKmLessThan($mileage);

                        // Convertir les données des véhicules en tableau associatif PHP
                        $vehicles = json_decode($vehicles, true);

                        // Afficher les véhicules
                        echo "Véhicules avec un kilométrage inférieur à $mileage km :\n";

                        // Parcourir le tableau des véhicules
                        foreach ($vehicles as $vehicle) {
                            // Afficher l'ID et les informations du véhicule
                            echo "ID : {$vehicle['uid']} - Immatriculation : {$vehicle['licence_plate']} - Marque : {$vehicle['brand']} - Modèle : {$vehicle['model']} - Kilométrage : {$vehicle['km']} km\n";
                        }

                        break;

                    case 'd':

                        //Rechercher un véhicule avec un kilométrage supérieur à une valeur donnée
                        typewriter("Recherche d'un véhicule avec un kilométrage supérieur à une valeur donnée\n");

                        // Demander à l'utilisateur de saisir la valeur du kilométrage
                        $mileage = readline('Entrez la valeur du kilométrage : ');

                        // Créer un nouvel objet VehicleModel
                        $vehicle = new App\sqlsrv\VehicleModel();

                        // Récupérer les données des véhicules
                        $vehicles = $vehicle->searchVehicleByKmGreaterThan($mileage);

                        // Convertir les données des véhicules en tableau associatif PHP
                        $vehicles = json_decode($vehicles, true);

                        // Afficher les véhicules
                        echo "Véhicules avec un kilométrage supérieur à $mileage km :\n";

                        // Parcourir le tableau des véhicules
                        foreach ($vehicles as $vehicle) {
                            // Afficher l'ID et les informations du véhicule
                            echo "ID : {$vehicle['uid']} - Immatriculation : {$vehicle['licence_plate']} - Marque : {$vehicle['brand']} - Modèle : {$vehicle['model']} - Kilométrage : {$vehicle['km']} km\n";
                        }

                        break;

                    case 'e':

                        //Afficher tous les véhicules
                        typewriter("Affichage de tous les véhicules\n");

                        // Récupérer tous les véhicules
                        $vehicle = new App\sqlsrv\VehicleModel();
                        $vehicles = $vehicle->readAll();

                        // Convertir les données des véhicules en tableau associatif PHP
                        $vehicles = json_decode($vehicles, true);

                        // Afficher les véhicules
                        echo "Liste des véhicules :\n";

                        // Parcourir le tableau des véhicules
                        foreach ($vehicles as $vehicle) {
                            // Afficher l'ID et les informations du véhicule
                            echo "ID : {$vehicle['uid']} - Immatriculation : {$vehicle['licence_plate']} - Marque : {$vehicle['brand']} - Modèle : {$vehicle['model']} - Kilométrage : {$vehicle['km']} km\n";
                        }

                        break;

                    case 'f':

                        //Retour au menu principal
                        clearScreen();
                        break 2;

                    case 'g':

                        //Quitter l'application
                        /*typewriter("Au revoir !\n");
                        sleep(1);
                        exit(0);*/

                        getMeOutOfHere();

                    default:

                        echo "Saisie invalide\n";
                }
            }








            break;


            //Option 5 : Requêtes sur les paiements
        case '5':

            // Sous-menu pour les requêtes sur les paiements

            $submenu = [

                'a' => "Créer un paiement",
                'b' => "Rechercher un paiement par son ID",
                'c' => "Afficher tous les paiements",
                'd' => "Retour au menu principal",
                'e' => "Quitter l'application"

            ];

            while (true) {
                echo "Sous-menu - Requêtes sur les paiements :\n";
                foreach ($submenu as $key => $value) {
                    echo "{$key}. {$value}\n";
                }

                $subChoice = readline();

                clearScreen();

                switch ($subChoice) {

                    case 'a':
                        //Créer un paiement
                        typewriter("Création d'un paiement\n");

                        // Demander à l'utilisateur de saisir le montant du paiement
                        $amount = readline('Entrez le montant du paiement : ');

                        // Demander à l'utilisateur de saisir la date et l'heure du paiement
                        $billingDatetime = readline('Entrez la date et l\'heure du paiement (format : Y-m-d H:i:s) : ');

                        // Demander à l'utilisateur de saisir l'ID du contrat associé au paiement
                        $contractId = readline('Entrez l\'ID du contrat associé au paiement : ');

                        // Créer un nouvel objet Billing avec les données saisies par l'utilisateur
                        $billing = new App\sqlsrv\Billing(null, $amount, $billingDatetime, $contractId);

                        // Appeler la méthode createBilling() pour insérer le paiement dans la base de données
                        $billingModel = new App\sqlsrv\BillingModel();
                        $billingId = $billingModel->createBilling($billing);

                        // Afficher un message de confirmation avec l'ID du paiement inséré
                        echo "Le paiement a été créé avec l'ID $billingId.\n";

                        break;

                    case 'b':
                        //Rechercher un paiement par son ID
                        typewriter("Recherche d'un paiement par son ID\n");

                        // Demander à l'utilisateur de saisir l'ID du paiement à rechercher
                        $billingId = readline('Entrez l\'ID du paiement à rechercher : ');

                        // Créer un nouvel objet BillingModel
                        $billing = new App\sqlsrv\BillingModel();

                        // Récupérer les données du paiement
                        $billingData = $billing->readSingleById($billingId);

                        $billingData = json_decode(
                            $billingData,
                            true

                        ); // Convertir l'objet JSON en tableau associatif PHP

                        // Afficher les données du paiement

                        echo "Données du paiement :\n";

                        print_r($billingData);

                        break;

                    case 'c':

                        //Afficher tous les paiements
                        typewriter("Affichage de tous les paiements\n");

                        // Récupérer tous les paiements
                        $billing = new App\sqlsrv\BillingModel();
                        $billings = $billing->readAll();

                        // Convertir les données des paiements en tableau associatif PHP
                        $billings = json_decode($billings, true);

                        // Afficher les paiements
                        echo "Liste des paiements :\n";

                        // Parcourir le tableau des paiements
                        foreach ($billings as $billing) {
                            // Afficher l'ID et les informations du paiement
                            echo "ID : {$billing['id']} - Montant : {$billing['amount']} - Date : {$billing['billing_datetime']} - ID du contrat : {$billing['contract_id']}\n";
                        }

                        break;

                    case 'd':

                        //Retour au menu principal
                        clearScreen();
                        break 2;

                    case 'e':

                        //Quitter l'application
                        /*typewriter("Au revoir !\n");
                            sleep(1);
                            exit(0);*/

                        getMeOutOfHere();

                    default:

                        echo "Saisie invalide\n";
                }
            }




        case '6':


            // Quitter l'application
            /*   typewriter("Au revoir !\n");
            sleep(1);
            exit(0);*/
            getMeOutOfHere();


        default:
            echo "Erreur : option invalide.\n";
    }
}
