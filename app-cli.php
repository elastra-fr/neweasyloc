<?php

/**Cette application lignes de commande permet de tester les fonctionnalités de la bibliothèque d'accès aux données */


require_once 'database/SqlSrv_con.php';
require_once 'database/MongoDb_con.php';
require_once 'model/Customer.php';
require_once 'model/Contract.php';
require_once 'model/Billing.php';
require_once 'model/Vehicle.php';
require_once 'model/ContractPaid.php';
require_once 'model/ContractAggregator.php';

/*********************Méthode globales**********************/
function typewriter($text)
{
    $text = mb_convert_encoding($text, 'UTF-8');

    for ($i = 0; $i < mb_strlen($text); $i++) {
        echo "\033[1;32m" . mb_substr($text, $i, 1) . "\033[0m";
        usleep(0); // Suspendre l'exécution du script pendant 100 microsecondes
    }
}

function clearScreen()
{
    echo "\033[2J";
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


//Options du menu principal
$menu = [
    '1' => "\033[1m\033[32m[Requêtes générales]\033[0m",
    '2' => "\033[1m\033[32m[Requêtes sur les contrats]\033[0m",
    '3' => "\033[1m\033[32m[Requêtes sur les clients]\033[0m",
    '4' => "\033[1m\033[32m[Requêtes sur les véhicules]\033[0m",
    '5' => "\033[1m\033[32m[Requêtes sur les paiements]\033[0m",
    '6' => "\033[1m\033[32m[Quitter l'application]\033[0m"

];

//Boucle principale
while (true) {

    typewriter("\n\n>>Menu principal - Sélectionnez une option>>:\n");

    foreach ($menu as $key => $value) {
        echo "{$key}. {$value}\n";
    }
    $choice = readline(">");


    switch ($choice) {


        case '1':
            //Option 1 : Reqûetes générales
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

                $subChoice = readline(">");

                clearScreen();

                switch ($subChoice) {

                    case 'a':
                        //Liste des locations en retard
                        typewriter("Liste des locations en retard : \n");
                        $lateContract = new App\sqlsrv\ContractModel();
                        $lateContracts->$lateContract->getLateContracts();

                        break;

                    case 'b':

clearScreen();
                        //Liste des locations non payées
                        typewriter("Liste des locations non payées");

echo "\n";

              //          $unpaidLocations = new App\sqlsrv\ContractPaid();
                //        $unpaidContracts= $unpaidLocations->getUnpaidContracts();



                    $fullUnpaidLocations=new App\sqlsrv\ContractPaid();
                    $fullUnpaidContracts=$fullUnpaidLocations->getUnpaidContractsWithCustomerData();
                        //Afficher la sortie.
echo "\n";
                        //var_dump($unpaidContracts);

                        //Transformer en tableau

                        $fullUnpaidContracts=json_decode($fullUnpaidContracts);

foreach ($fullUnpaidContracts as $contract) {
    // Afficher l'ID et les informations du contrat
    echo "ID : {$contract->ContractId} - Client : {$contract->CustomerName} {$contract->CustomerLast} - Prix total : {$contract->TotalDue} - Payé : {$contract->TotalPaid}\n";
}

                        //var_dump($fullUnpaidContracts);

                        

echo "\n";

                        

                
                       

                       flush();   

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

                        clearScreen();

                        $order = "vehicle_uid ASC";
                        $contractsByVehicle = new App\sqlsrv\ContractAggregator($connection->getConnection(), $connectionMdb->getDB());
                        $contractsByVehicle = $contractsByVehicle->getContracts(null, $order);


                        //Transformer en tableau associatif

                        $contractsByVehicle = json_decode($contractsByVehicle, JSON_PRETTY_PRINT);



                        echo "Liste des contrats par clients:\n";


                        // Parcourir le tableau des contrats
                        foreach ($contractsByVehicle as $contract) {
                            // Afficher l'ID et les informations du contrat
                            echo "ID : {$contract['id']} - Client : {$contract['customer_name']} - Véhicule : {$contract['vehicle_licence_plate']} - Prix total : {$contract['price']} - Début : {$contract['loc_begin_datetime']} - Fin : {$contract['loc_end_datetime']}\n";
                        }


                        flush();




                        break;

                    case 'g':

                        //Obtenir tous les contrats regroupés par clients

                        clearScreen();

                        $order = "customer_uid ASC";

                        $contractsByCustomer = new App\sqlsrv\ContractAggregator($connection->getConnection(), $connectionMdb->getDB());
                        $contractsByCustomer = $contractsByCustomer->getContracts(null, $order);

                        // echo $contractsByCustomer;

                        //Transformer en tableau associatif

                        $contractsByCustomer = json_decode($contractsByCustomer, JSON_PRETTY_PRINT);

                        echo "Liste des contrats par clients:\n";

                        // Parcourir le tableau des contrats

                        foreach ($contractsByCustomer as $contract) {
                            // Afficher l'ID et les informations du contrat
                            echo "ID : {$contract['id']} - Client : {$contract['customer_name']} - Véhicule : {$contract['vehicle_licence_plate']} - Prix total : {$contract['price']} - Début : {$contract['loc_begin_datetime']} - Fin : {$contract['loc_end_datetime']}\n";
                        }

                        flush();

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


        case '2':
            //Option 2 : Requêtes sur les contrats
            // Sous-menu 
            $submenu = [
                'a' => 'Créer un contrat à la date actuelle',
                'b' => 'Créer un contrat à une autre date',
                'c' => 'Rechercher un contrat par son ID',
                'd' => 'Afficher tous les contrats et effectuer des opérations sur un contrat spécifique',
                'e' => 'Retour au menu principal',
                'f' => 'Quitter l\'application'
            ];


            echo "\n";
            echo "\033[31mAvant de créer un contrat, veuillez vous assurer que le client et le véhicule existent dans la base de données\033[0m\n";
            echo "\n";

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
                        //    $contract = new App\sqlsrv\ContractModel();

                        $filtre = "id = '$contractId'";
                        $contract = new App\sqlsrv\ContractAggregator($connection->getConnection(), $connectionMdb->getDB());
                        $contractData = $contract->getContracts($filtre, null);
                        // Récupérer les données du contrat
                        //  $contractData = $contract->readSingleById($contractId);

                        $contractData = json_decode($contractData, true); // Convertir l'objet JSON en tableau associatif PHP

                        echo "\n";
                        // Afficher les données du contrat
                        echo "Données du contrat :\n";

                        $contractCustomer = $contractData[0]['customer_name'];
                        $contractVehicle = $contractData[0]['vehicle_licence_plate'];
                        $contractPrice = $contractData[0]['price'];
                        $contractLocBegin = $contractData[0]['loc_begin_datetime'];
                        $contractLocEnd = $contractData[0]['loc_end_datetime'];
                        echo "Client : $contractCustomer\n";
                        echo "Véhicule : $contractVehicle\n";
                        echo "Prix : $contractPrice\n";
                        echo "Début : $contractLocBegin\n";
                        echo "Fin : $contractLocEnd\n";

                        echo "\n";

                        //var_dump($contractData);



                        break;

                    case 'd':
                        //Afficher tous les contrats et effectuer des opérations sur un contrat spécifique
                        typewriter("Afficher tous les contrats et effectuer des opérations sur un contrat spécifique\n");

                        // Récupérer tous les contrats
                        //$contract = new App\sqlsrv\ContractModel();
                        //$contracts = $contract->readAll();

                        $contract = new App\sqlsrv\ContractAggregator($connection->getConnection(), $connectionMdb->getDB());
                        $contracts = $contract->getContracts();

                        // Convertir les données des contrats en tableau associatif PHP
                        $contracts = json_decode($contracts, true);


                        //Afficher les contrats
                        echo "Liste des contrats :\n";


                        // Parcourir le tableau des contrats
                        foreach ($contracts as $contract) {
                            // Afficher l'ID et les informations du contrat
                            echo "ID : {$contract['id']} - Client : {$contract['customer_name']} - Véhicule : {$contract['vehicle_licence_plate']} - Prix total : {$contract['price']} - Début : {$contract['loc_begin_datetime']} - Fin : {$contract['loc_end_datetime']}\n";
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
                            $customerName = $selectedContract['customer_name'];
                            $vehicleLicensePlate = $selectedContract['vehicle_licence_plate'];
                            $price = $selectedContract['price'];
                            $locBeginDatetime = $selectedContract['loc_begin_datetime'];
                            $locEndDatetime = $selectedContract['loc_end_datetime'];


                            clearScreen();
                            // Afficher les informations du contrat sélectionné
                            echo "Contrat sélectionné :\n";
                            echo "ID : {$contractId}\n";
                            echo "Client : {$customerName}\n";
                            echo "Véhicule : {$vehicleLicensePlate}\n";
                            echo "Prix Total: {$price}\n";
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
                                        echo "\n";
                                        typewriter("Liste des paiements effectués pour ce contrat\n");


                                        // Récupérer les paiements effectués pour le contrat
                                        $billing = new App\sqlsrv\BillingModel();
                                        $billings_json = $billing->getBillingByContractId($contractId);
                                        // Décoder la chaîne JSON en tableau PHP
                                        $billings_array = json_decode($billings_json, true);

                                        // Afficher les paiements effectués pour le contrat
                                        echo "Paiements effectués pour le contrat :\n";
                                        foreach ($billings_array as $billing) {
                                            echo "ID : {$billing['ID']} - Montant : {$billing['Amount']}\n";
                                        }






                                        echo "\n\n";



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
                        $customerId = $customerModel->addCustomer($customer);

                        // Afficher un message de confirmation avec l'ID du client inséré
                        echo "Le client a été créé avec l'ID $customerId.\n";

                        break;

                    case 'b':
                        //Rechercher un client par nom et prénom
                        typewriter("Recherche d'un client par nom et prénom\n");

                        // Demander à l'utilisateur de saisir le prénom du client
                        $firstName = trim(readline('Entrez le prénom du client : '));


                        // Demander à l'utilisateur de saisir le nom du client
                        $secondName = trim(readline('Entrez le nom du client : '));


                        // Créer un nouvel objet CustomerModel
                        $customer = new App\mongo\CustomerModel();

                        // Récupérer les données du client
                        $customerData = $customer->searchCustomer($firstName,  $secondName);
                        $customerData = json_decode($customerData, true); // Convertir l'objet JSON en tableau associatif PHP

                        // Afficher les données du client
                        echo "Données du client :\n";

                        print_r($customerData);

                        echo "\n";



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
                                'c' => 'Lister tous les contrats du client',
                                'd' => 'Lister tous les contrats en cours du client',
                                'e' => 'Retour au menu précédent',
                                'f' => 'Quitter l\'application'
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
                                        $newCustomer = new App\mongo\Customer(null, $newFirstName, $newSecondName, $newAddress, $newPermitNumber);

                                        var_dump($newCustomer);
                                        // Appeler la méthode updateCustomer() pour modifier le client dans la base de données
                                        $customerModel = new App\mongo\CustomerModel();
                                        $customerModel->updateCustomer($newCustomer, $selectedCustomerId);

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
                                            $customerModel->deleteCustomerById($selectedCustomerId);

                                            // Afficher un message de confirmation
                                            echo "Le client a été supprimé.\n";
                                        } else {
                                            echo "La suppression a été annulée.\n";
                                        }

                                        break;

                                    case 'c':

                                        //Lister tous les contrats du client

                                        $contract = new App\sqlsrv\ContractAggregator($connection->getConnection(), $connectionMdb->getDB());
                                        $contracts = $contract->getContracts("customer_uid = '$selectedCustomerId'", null);

                                        //Transformer en tableau associatif

                                        $contracts = json_decode($contracts, JSON_PRETTY_PRINT);

                                        echo "\n";
                                        echo "Liste des contrats du client $selectedCustomerName:\n";

                                        // Parcourir le tableau des contrats

                                        foreach ($contracts as $contract) {
                                            // Afficher l'ID et les informations du contrat
                                            echo "ID : {$contract['id']} - Client : {$contract['customer_name']} - Véhicule : {$contract['vehicle_licence_plate']} - Prix total : {$contract['price']} - Début : {$contract['loc_begin_datetime']} - Fin : {$contract['loc_end_datetime']}\n";
                                        }

                                        echo "\n";



                                        break;

                                    case 'd':

                                        //Lister tous les contrats en cours du client

                                        $contract = new App\sqlsrv\ContractAggregator($connection->getConnection(), $connectionMdb->getDB());
                                        $contracts = $contract->getContracts("customer_uid = '$selectedCustomerId' AND loc_end_datetime > GETDATE()", null);

                                        //Transformer en tableau associatif

                                        $contracts = json_decode($contracts, JSON_PRETTY_PRINT);

                                        echo "\n";

                                        echo "Liste des contrats en cours du client $selectedCustomerName:\n";

                                        // Parcourir le tableau des contrats

                                        foreach ($contracts as $contract) {
                                            // Afficher l'ID et les informations du contrat
                                            echo "ID : {$contract['id']} - Client : {$contract['customer_name']} - Véhicule : {$contract['vehicle_licence_plate']} - Prix total : {$contract['price']} - Début : {$contract['loc_begin_datetime']} - Fin : {$contract['loc_end_datetime']}\n";
                                        }

                                        echo "\n";



                                        break;


                                    case 'e':
                                        // Retour au menu précédent

                                        clearScreen();

                                        break 3;

                                    case 'f':

                                        getMeOutOfHere();
                                    default:

                                        echo "Saisie invalide\n";
                                }
                            }
                        } else {
                            echo "Saisie invalide\n";
                        }

                        break;

                    case 'd':

                        //Retour au menu principal
                        clearScreen();
                        break 2;

                    case 'e':

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
                        $licensePlate = readline('Entrez le numéro d\'immatriculation du véhicule : ');

                        // Demander à l'utilisateur de saisir la marque du véhicule
                        $informations = readline('Entrez les informations sur le véhicule : ');

                        // Demander à l'utilisateur de saisir le kilométrage du véhicule
                        $mileage = readline('Entrez le kilométrage du véhicule : ');


                        // Créer un nouvel objet Vehicle avec les données saisies par l'utilisateur
                        $vehicle = new App\mongo\Vehicle(null, $licensePlate, $informations, $mileage);

                        // Appeler la méthode createVehicle() pour insérer le véhicule dans la base de données
                        $vehicleModel = new App\mongo\VehicleModel();
                        $vehicleId = $vehicleModel->addVehicle($vehicle);

                        // Afficher un message de confirmation avec l'ID du véhicule inséré
                        echo "Le véhicule a été créé avec l'ID $vehicleId.\n";

                        break;

                    case 'b':
                        //Rechercher un véhicule par son immatriculation
                        typewriter("Recherche d'un véhicule par son immatriculation\n");

                        // Demander à l'utilisateur de saisir le numéro d'immatriculation du véhicule
                        $registrationNumber = readline('Entrez le numéro d\'immatriculation du véhicule : ');

                        // Créer un nouvel objet VehicleModel
                        $vehicle = new App\mongo\VehicleModel();

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
                        $vehicle = new App\mongo\VehicleModel();

                        // Récupérer les données des véhicules
                        $vehicles = $vehicle->searchVehicleByKmLessThan($mileage);

                        // Convertir les données des véhicules en tableau associatif PHP
                        $vehicles = json_decode($vehicles, true);

                        // Afficher les véhicules
                        echo "Véhicules avec un kilométrage inférieur à $mileage km :\n";

                        // Parcourir le tableau des véhicules
                        foreach ($vehicles as $vehicle) {
                            // Afficher l'ID et les informations du véhicule
                            echo "ID : {$vehicle['uid']} - Immatriculation : {$vehicle['licence_plate']} - Information : {$vehicle['informations']} - Kilométrage : {$vehicle['km']} km\n";
                        }

                        break;

                    case 'd':

                        //Rechercher un véhicule avec un kilométrage supérieur à une valeur donnée
                        typewriter("Recherche d'un véhicule avec un kilométrage supérieur à une valeur donnée\n");

                        // Demander à l'utilisateur de saisir la valeur du kilométrage
                        $mileage = readline('Entrez la valeur du kilométrage : ');

                        // Créer un nouvel objet VehicleModel
                        $vehicle = new App\mongo\VehicleModel();

                        // Récupérer les données des véhicules
                        $vehicles = $vehicle->searchVehicleByKmGreaterThan($mileage);

                        // Convertir les données des véhicules en tableau associatif PHP
                        $vehicles = json_decode($vehicles, true);

                        // Afficher les véhicules
                        echo "Véhicules avec un kilométrage supérieur à $mileage km :\n";

                        // Parcourir le tableau des véhicules
                        foreach ($vehicles as $vehicle) {
                            // Afficher l'ID et les informations du véhicule
                            echo "ID : {$vehicle['uid']} - Immatriculation : {$vehicle['licence_plate']} - Information : {$vehicle['informations']} - Kilométrage : {$vehicle['km']} km\n";
                        }

                        break;

                    case 'e':

                        //Afficher tous les véhicules
                        typewriter("Affichage de tous les véhicules\n");

                        // Récupérer tous les véhicules
                        $vehicle = new App\mongo\VehicleModel();
                        $vehicles = $vehicle->readAll();

                        // Convertir les données des véhicules en tableau associatif PHP
                        $vehicles = json_decode($vehicles, true);

                        // Afficher les véhicules
                        echo "Liste des véhicules :\n";


                        $i = 1;
                        foreach ($vehicles as $vehicle) {
                            // Récupérer la valeur de $oid à partir de l'objet _id
                            $oid = $vehicle['_id']['$oid'];

                            echo "{$i}. {$vehicle['licence_plate']} (ID: {$oid})\n";
                            $i++;
                        }

                        // Demander à l'utilisateur de saisir le numéro du véhicule à sélectionner et récupérer $oid correspondant



                        $vehicleNumber = readline("Entrez le numéro du véhicule à sélectionner : ");

                        // Vérifier si la sélection est valide et récupérer l'ID du véhicule correspondant  

                        $selectedVehicleId = null;
                        $selectedVehicleLicensePlate = null;

                        if (is_numeric($vehicleNumber) && $vehicleNumber >= 1 && $vehicleNumber < count($vehicles) + 1) {
                            // Récupérer la valeur de $oid à partir de l'objet _id
                            $selectedVehicleId = $vehicles[$vehicleNumber - 1]['_id']['$oid'];
                            $selectedVehicleLicensePlate = $vehicles[$vehicleNumber - 1]['licence_plate'];
                            $selectedVehicleInformations = $vehicles[$vehicleNumber - 1]['informations'];

                            clearScreen();

                            echo "Vous avez sélectionné le véhicule : {$selectedVehicleLicensePlate}\n";
                            echo "Ce véhicule a l'ID : {$selectedVehicleId}\n";
                            echo "Informations : {$selectedVehicleInformations}\n";
                            echo "\n";
                            // Afficher les opérations possibles sur le véhicule

                            $operations = [
                                'a' => 'Modifier le véhicule',
                                'b' => 'Supprimer le véhicule',
                                'c' => 'Obtenir les contrats associés à ce véhicule',
                                'd' => 'Retour au menu précédent',
                                'e' => 'Quitter l\'application'
                            ];

                            while (true) {
                                echo "Opérations possibles sur le véhicule :\n";

                                foreach ($operations as $key => $value) {
                                    echo "{$key}. {$value}\n";
                                }

                                echo "\n";

                                $operation = readline("Sélectionnez une opération : ");

                                switch ($operation) {
                                    case 'a':
                                        // Modifier le véhicule
                                        typewriter("Modification du véhicule\n");

                                        // Demander à l'utilisateur de saisir le nouveau numéro d'immatriculation du véhicule
                                        $newLicensePlate = readline("Entrez le nouveau numéro d'immatriculation du véhicule : ");

                                        // Demander à l'utilisateur de saisir les nouvelles informations sur le véhicule
                                        $newInformations = readline("Entrez les nouvelles informations sur le véhicule : ");

                                        // Demander à l'utilisateur de saisir le nouveau kilométrage du véhicule
                                        $newMileage = readline("Entrez le nouveau kilométrage du véhicule : ");

                                        // Créer un nouvel objet Vehicle avec les données saisies par l'utilisateur
                                        $newVehicle = new App\mongo\Vehicle(null, $newLicensePlate, $newInformations, $newMileage);

                                        // Appeler la méthode updateVehicle() pour modifier le véhicule dans la base de données
                                        $vehicleModel = new App\mongo\VehicleModel();
                                        $vehicleModel->updateVehicle($newVehicle, $selectedVehicleId);

                                        // Afficher un message de confirmation

                                        echo "Le véhicule a été modifié.\n";


                                        break;

                                    case 'b':

                                        // Supprimer le véhicule
                                        typewriter("Suppression du véhicule\n");

                                        // Demander à l'utilisateur de confirmer la suppression
                                        $confirm = readline("Voulez-vous vraiment supprimer ce véhicule ? (o/n) : ");

                                        if ($confirm === 'o') {
                                            // Supprimer le véhicule de la base de données
                                            $vehicleModel = new App\mongo\VehicleModel();
                                            $vehicleModel->deleteVehicle($selectedVehicleId);

                                            // Afficher un message de confirmation
                                            echo "Le véhicule a été supprimé.\n";
                                        } else {
                                            echo "La suppression a été annulée.\n";
                                        }

                                        break;

                                    case 'c':

                                        // Obtenir les contrats associés à ce véhicule
                                        typewriter("Obtention des contrats associés à ce véhicule\n");

                                        // Récupérer les contrats associés au véhicule
                                        //$contract = new App\sqlsrv\ContractModel();
                                        //$contracts = $contract->getContractsByVehicle($selectedVehicleId);
                                        $contract = new App\sqlsrv\ContractAggregator($connection->getConnection(), $connectionMdb->getDB());

                                        $filtre = "vehicle_uid = '$selectedVehicleId'";
                                        $contracts = $contract->getContracts($filtre, null);



                                        // Convertir les données des contrats en tableau associatif PHP
                                        $contracts = json_decode($contracts, true);



                                        // Afficher les contrats associés au véhicule


                                        foreach ($contracts as $contract) {
                                            // Afficher l'ID et les informations du contrat
                                            echo "ID : {$contract['id']} - Client : {$contract['customer_name']} - Véhicule : {$contract['vehicle_licence_plate']} - Prix total : {$contract['price']} - Début : {$contract['loc_begin_datetime']} - Fin : {$contract['loc_end_datetime']}\n";
                                        }


                                        // Parcourir le tableau des contrats

                                        // Parcourir le tableau des contrats
                                        /*  foreach ($contracts as $contract) {
                                            // Vérifier si la propriété 'id' est définie avant d'y accéder
                                            if (isset($contract['id'])) {
                                                // Afficher l'ID et les informations du contrat
                                                echo "ID : {$contract['id']}";

                                                // Vérifier si les propriétés 'loc_begin_datetime' et 'loc_end_datetime' sont définies avant d'y accéder
                                                if (isset($contract['loc_begin_datetime']) && isset($contract['loc_end_datetime'])) {
                                                    echo " - Début : {$contract['loc_begin_datetime']} - Fin : {$contract['loc_end_datetime']}\n";
                                                } else {
                                                    echo " - Les dates de début et/ou de fin ne sont pas définies\n";
                                                }
                                            }
                                        }*/

                                        echo "\n";

                                        break;

                                    case 'd':

                                        // Retour au menu précédent
                                        clearScreen();

                                        break 3;

                                    case 'e':

                                        getMeOutOfHere();

                                    default:

                                        echo "Saisie invalide\n";
                                }
                            }
                        } else {
                            echo "Saisie invalide\n";
                        }

                        break;

                    case 'f':

                        //Retour au menu principal
                        clearScreen();
                        break 2;

                    case 'g':

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

            echo "\n";
            echo "\033[31mAvant de créer un paiement veuillez vous assurer que le contrat existe bien dans la base de données\033[0m\n";
            echo "\n";

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

                        //Récupérer la liste des contrats et afficher les contrats sous forme de menu avec lettre de sélection

                        // Récupérer tous les contrats

                        $contract = new App\sqlsrv\ContractModel();
                        $contracts = $contract->readAll();

                        // Convertir les données des contrats en tableau associatif PHP

                        $contracts = json_decode($contracts, true);

                        // Afficher les contrats

                        echo "Liste des contrats :\n";

                        // Parcourir le tableau des contrats et les afficher sous forme de menu avec lettre de sélection

                        $i = 1;

                        foreach ($contracts as $contract) {
                            // Afficher l'ID et les informations du contrat
                            echo "{$i}. ID : {$contract['id']} - Début : {$contract['loc_begin_datetime']} - Fin : {$contract['loc_end_datetime']}\n";
                            $i++;
                        }

                        // Demander à l'utilisateur de saisir le numéro du contrat à sélectionner et récupérer l'ID correspondant

                        $contractNumber = readline("Entrez le numéro du contrat à sélectionner : ");

                        // Vérifier si la sélection est valide et récupérer l'ID du contrat correspondant

                        $selectedContractId = null;
                        $selectedContractBeginDate = null;
                        $selectedContractEndDate = null;

                        if (is_numeric($contractNumber) && $contractNumber >= 1 && $contractNumber < count($contracts) + 1) {
                            // Récupérer $oid du contrat correspondant
                            $selectedContractId = $contracts[$contractNumber - 1]['id'];
                            $selectedContractBeginDate = $contracts[$contractNumber - 1]['loc_begin_datetime'];
                            $selectedContractEndDate = $contracts[$contractNumber - 1]['loc_end_datetime'];

                            clearScreen();

                            echo "Vous avez sélectionné le contrat :\n";
                            echo "ID : {$selectedContractId}\n";
                            echo "Début : {$selectedContractBeginDate}\n";
                            echo "Fin : {$selectedContractEndDate}\n";
                            echo "\n";
                        } else {
                            echo "Saisie invalide\n";
                        }

                        typewriter("Création d'un paiement\n");

                        // Demander à l'utilisateur de saisir le montant du paiement
                        $amount = readline('Entrez le montant du paiement : ');

                        // Créer un nouvel objet Billing avec les données saisies par l'utilisateur
                        $billing = new App\sqlsrv\Billing(null,  $selectedContractId, $amount);

                        // Appeler la méthode createBilling() pour insérer le paiement dans la base de données
                        $billingModel = new App\sqlsrv\BillingModel();
                        $billingId = $billingModel->addBilling($billing);


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
                        $billingData = $billing->getBillingById($billingId);

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

                        // Parcourir le tableau des paiements et afficher sous forme de menu selectionnable par ID


                        foreach ($billings as $billing) {
                            // Afficher l'ID et les informations du paiement
                            echo "ID : {$billing['ID']} - Montant : {$billing['Amount']} - ID du contrat : {$billing['Contract_id']}\n";
                        }

                        $selectedBillingId = readline("Entrez l'ID du paiement à sélectionner : ");

                        // Récupérer l'ID du Contrat du paiement sélectionné

                        $selectedBillingContractId = null;

                        foreach ($billings as $billing) {
                            if ($billing['ID'] == $selectedBillingId) {
                                $selectedBillingContractId = $billing['Contract_id'];
                            }
                        }


                        //Sous-menu pour les opérations sur le paiement sélectionné

                        $operations = [

                            'a' => "Modifier le paiement",
                            'b' => "Supprimer le paiement",
                            'c' => "Retour au menu précédent",
                            'd' => "Quitter l'application"

                        ];

                        echo "\n";

                        while (true) {
                            echo "Opérations possibles sur le paiement :\n";
                            foreach ($operations as $key => $value) {
                                echo "{$key}. {$value}\n";
                            }

                            $operation = readline();

                            clearScreen();

                            switch ($operation) {

                                case 'a':

                                    //Modifier le paiement
                                    typewriter("Modification du paiement\n");

                                    // Demander à l'utilisateur de saisir le nouveau montant du paiement
                                    $newAmount = readline("Entrez le nouveau montant du paiement : ");

                                    // Créer un nouvel objet Billing avec les données saisies par l'utilisateur
                                    $newBilling = new App\sqlsrv\Billing(null, $selectedBillingContractId, $newAmount);

                                    // Appeler la méthode updateBilling() pour modifier le paiement dans la base de données
                                    $billingModel = new App\sqlsrv\BillingModel();
                                    $billingModel->updateBilling($selectedBillingId, $newBilling);

                                    // Afficher un message de confirmation
                                    echo "Le paiement a été modifié.\n";

                                    break;

                                case 'b':

                                    //Supprimer le paiement
                                    typewriter("Suppression du paiement\n");

                                    // Demander à l'utilisateur de confirmer la suppression
                                    $confirm = readline("Voulez-vous vraiment supprimer ce paiement ? (o/n) : ");

                                    if ($confirm === 'o') {
                                        // Supprimer le paiement de la base de données
                                        $billingModel = new App\sqlsrv\BillingModel();
                                        $billingModel->deleteBilling($selectedBillingId);

                                        // Afficher un message de confirmation
                                        echo "Le paiement a été supprimé.\n";
                                    } else {
                                        echo "La suppression a été annulée.\n";
                                    }

                                    break;

                                case 'c':

                                    //Retour au menu précédent
                                    clearScreen();
                                    break 2;

                                case 'd':

                                    getMeOutOfHere();

                                default:

                                    echo "Saisie invalide\n";
                            }
                        }




                        break;

                    case 'd':

                        //Retour au menu principal
                        clearScreen();
                        break 2;

                    case 'e':

                        getMeOutOfHere();

                    default:

                        echo "Saisie invalide\n";
                }
            }




        case '6':

            getMeOutOfHere();


        default:
            echo "Erreur : option invalide.\n";
    }
}
