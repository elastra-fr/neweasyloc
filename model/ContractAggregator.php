<?php



namespace App\sqlsrv;
require 'vendor/autoload.php';
require_once 'database/SqlSrv_con.php';
use Exception;
use PDO;
use MongoDB;
use MongoDB\BSON\ObjectId;

class ContractAggregator
{
    private $pdo;
    private $mongoDb;

    public function __construct(PDO $pdo, MongoDB\Database $mongoDb)
    {
        $this->pdo = $pdo;
        $this->mongoDb = $mongoDb;
    }

public function getContracts($where=null, $orderBy=null)
{
    // Récupérer les données de la table Contrat sur SQL Server
    $sql = "SELECT * FROM Contract";

    if ($where) {
        $sql .= " WHERE $where";
    }

    if ($orderBy) {
        $sql .= " ORDER BY $orderBy";
    }




    //$stmt = $this->pdo->query($sql);
    $stmt = $this->pdo->prepare($sql);
    $params = [];

preg_match_all('/:(\w+)/', $sql, $matches);
            foreach ($matches[1] as $param) {
                $value = ${$param};
                $stmt->bindParam(":$param", $value);
    
            }

    $stmt->execute($params);

    $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($contracts as &$contract) {
        // Supprimer les espaces indésirables de 'vehicle_uid'
        $contract['vehicle_uid'] = trim($contract['vehicle_uid']);
        $contract['customer_uid'] = trim($contract['customer_uid']);
    }

    // Filtrer les valeurs invalides de customer_uid
    $customerIds = array_filter(array_map(function($contract) {
        $customerId = trim($contract['customer_uid']);
        try {
            $oid = new MongoDB\BSON\ObjectId($customerId);
            if ($oid instanceof MongoDB\BSON\ObjectId) {
                return $oid;
            }
        } catch (Exception $e) {
            // Ignorer les valeurs invalides
        }
    }, $contracts));

    // Pipeline d'agrégation MongoDB pour récupérer les informations du client
    $customerPipeline = [
        ['$match' => ['_id' => ['$in' => $customerIds]]],
        ['$project' => [
            'first_name' => 1,
            'second_name' => 1,
        ]]
    ];

    // Récupérer les données des collections Customer sur MongoDB
    $customerData = $this->mongoDb->Customer->aggregate($customerPipeline);

    // Convertir le curseur en tableau
    $customerDataArray = iterator_to_array($customerData);

    // Parcourir les contrats et ajouter les données du client correspondant
    foreach ($contracts as &$contract) {
        // Supprimer les espaces indésirables de 'customer_uid'
        $customerId = trim($contract['customer_uid']);

        // Récupérer les données du client correspondant
        $customerData = current(array_filter($customerDataArray, function ($data) use ($customerId) {
            return $data['_id'] == $customerId;
        }));

        // Vérifier si les données du client sont nulles
        if (!$customerData) {
            $contract['customer_name'] = 'Client introuvable';
        } else {
            // Ajouter les données du client correspondant
            $contract['customer_name'] = $customerData['first_name'] . ' ' . $customerData['second_name'];
        }
    }

    // Filtrer les valeurs invalides de vehicle_uid
    $vehicleIds = array_filter(array_map(function($contract) {
        $vehicleId = trim($contract['vehicle_uid']);
        try {
            $oid = new MongoDB\BSON\ObjectId($vehicleId);
            if ($oid instanceof MongoDB\BSON\ObjectId) {
                return $oid;
            }
        } catch (Exception $e) {
            // Ignorer les valeurs invalides
        }
    }, $contracts));

    // Pipeline d'agrégation MongoDB pour récupérer les informations du véhicule
    $vehiclePipeline = [
        ['$match' => ['_id' => ['$in' => $vehicleIds]]],
        ['$project' => [
            'licence_plate' => 1
        ]]
    ];

    // Récupérer les données des collections Vehicle sur MongoDB
    $vehicleData = $this->mongoDb->Vehicle->aggregate($vehiclePipeline);

    // Convertir le curseur en tableau
    $vehicleDataArray = iterator_to_array($vehicleData);

    // Parcourir les contrats et ajouter les données du véhicule correspondant
    foreach ($contracts as &$contract) {
        // Supprimer les espaces indésirables de 'vehicle_uid'
        $vehicleId = trim($contract['vehicle_uid']);

        // Récupérer les données du véhicule correspondant
        $vehicleData = current(array_filter($vehicleDataArray, function ($data) use ($vehicleId) {
            return $data['_id'] == $vehicleId;
        }));

        // Vérifier si les données du véhicule sont nulles
        if (!$vehicleData) {
            $contract['vehicle_licence_plate'] = 'Véhicule introuvable';
        } else {
            // Ajouter les données du véhicule correspondant
            $contract['vehicle_licence_plate'] = $vehicleData['licence_plate'];
        }
    }

$jsonContracts = json_encode($contracts, JSON_PRETTY_PRINT);

//var_dump($jsonContracts);


if ($jsonContracts === false) {
    // Il y a eu une erreur lors de la conversion en JSON
    $errorCode = json_last_error();
    switch ($errorCode) {
        case JSON_ERROR_NONE:
            echo "Aucune erreur n'a été rencontrée";
            break;
        case JSON_ERROR_DEPTH:
            echo 'Dépassement de la profondeur maximale de la pile';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo 'Désynchronisation ou mode non valide';
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo 'Caractère de contrôle inattendu trouvé';
            break;
        case JSON_ERROR_SYNTAX:
            echo 'Erreur de syntaxe, JSON mal formé';
            break;
        case JSON_ERROR_UTF8:
            echo 'Caractères UTF-8 mal formés, probablement encodés en ISO-8859-1';
            break;
        default:
            echo 'Erreur inconnue';
            break;
    }
} else {
    // La conversion en JSON a réussi
   //var_dump($jsonContracts);
}



    return $jsonContracts;
}


/*
       public function getContracts()
    {
        // Récupérer les données de la table Contrat sur SQL Server
        $sql = "SELECT TOP 5 * FROM Contract";
        $stmt = $this->pdo->query($sql);
        $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($contracts as &$contract) {
            // Supprimer les espaces indésirables de 'vehicle_uid'
            $contract['vehicle_uid'] = trim($contract['vehicle_uid']);
        }

        // Filtrer les valeurs invalides de customer_uid
        $customerIds = array_filter(array_map(function($contract) {
            $customerId = trim($contract['customer_uid']);
            try {
                $oid = new MongoDB\BSON\ObjectId($customerId);
                if ($oid instanceof MongoDB\BSON\ObjectId) {
                    return $oid;
                }
            } catch (Exception $e) {
                // Ignorer les valeurs invalides
            }
        }, $contracts));

        // Pipeline d'agrégation MongoDB pour récupérer les informations du client
        $customerPipeline = [
            ['$match' => ['_id' => ['$in' => $customerIds]]],
            ['$project' => [
                'first_name' => 1,
                'second_name' => 1,
            ]]
        ];

        // Récupérer les données des collections Customer sur MongoDB
        $customerData = $this->mongoDb->Customer->aggregate($customerPipeline);

        // Convertir le curseur en tableau
        $customerDataArray = iterator_to_array($customerData);

        // Parcourir les contrats et ajouter les données du client correspondant
        foreach ($contracts as &$contract) {
            // Supprimer les espaces indésirables de 'customer_uid'
            $customerId = trim($contract['customer_uid']);

            // Récupérer les données du client correspondant
            $customerData = current(array_filter($customerDataArray, function ($data) use ($customerId) {
                return $data['_id'] == $customerId;
            }));

            // Ajouter les données du client correspondant
            $contract['customer_name'] = $customerData['first_name'] . ' ' . $customerData['second_name'];
        }

        // Filtrer les valeurs invalides de vehicle_uid
        $vehicleIds = array_filter(array_map(function($contract) {
            $vehicleId = trim($contract['vehicle_uid']);
            try {
                $oid = new MongoDB\BSON\ObjectId($vehicleId);
                if ($oid instanceof MongoDB\BSON\ObjectId) {
                    return $oid;
                }
            } catch (Exception $e) {
                // Ignorer les valeurs invalides
            }
        }, $contracts));

        // Pipeline d'agrégation MongoDB pour récupérer les informations du véhicule
        $vehiclePipeline = [
            ['$match' => ['_id' => ['$in' => $vehicleIds]]],
            ['$project' => [
                'licence_plate' => 1
            ]]
        ];

        // Récupérer les données des collections Vehicle sur MongoDB
        $vehicleData = $this->mongoDb->Vehicle->aggregate($vehiclePipeline);

        // Convertir le curseur en tableau
        $vehicleDataArray = iterator_to_array($vehicleData);

        // Parcourir les contrats et ajouter les données du véhicule correspondant
        foreach ($contracts as &$contract) {
            // Supprimer les espaces indésirables de 'vehicle_uid'
            $vehicleId = trim($contract['vehicle_uid']);

            // Récupérer les données du véhicule correspondant
            $vehicleData = current(array_filter($vehicleDataArray, function ($data) use ($vehicleId) {
                return $data['_id'] == $vehicleId;
            }));

            // Ajouter les données du véhicule correspondant
            $contract['vehicle_licence_plate'] = $vehicleData['licence_plate'];
        }

        return $contracts;
    }
*/
/*public function getContracts()
{
    // Récupérer les données de la table Contrat sur SQL Server
    $sql = "SELECT TOP 5 * FROM Contract";
    $stmt = $this->pdo->query($sql);
    $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($contracts as &$contract) {
        // Supprimer les espaces indésirables de 'vehicle_uid'
        $contract['vehicle_uid'] = trim($contract['vehicle_uid']);

        echo "OID véhicules : ".$contract['vehicle_uid']."\r\n";


    }


    // Filtrer les valeurs invalides de customer_uid
    $customerIds = array_filter(array_map(function($contract) {
        $customerId = trim($contract['customer_uid']);
        //echo $customerId."\r\n";
        try {
            $oid = new MongoDB\BSON\ObjectId($customerId);
            if ($oid instanceof MongoDB\BSON\ObjectId) {
                return $oid;
            }
        } catch (Exception $e) {
            // Ignorer les valeurs invalides
        }
    }, $contracts));


var_dump($customerIds);
    // Pipeline d'agrégation MongoDB
    $pipeline = [
        ['$match' => ['_id' => ['$in' => $customerIds]]],
        ['$lookup' => [
            'from' => 'Vehicle',
            'localField' => 'vehicle_uid',
            'foreignField' => '_id',
            'as' => 'vehicle'
        ]],
        ['$project' => [
            'first_name' => 1,
            'second_name' => 1,
            'vehicle.licence_plate' => 1
        ]]
    ];

    // Récupérer les données des collections Customer et Vehicle sur MongoDB
    $customerVehicleData = $this->mongoDb->Customer->aggregate($pipeline);

    // Convertir le curseur en tableau
$customerVehicleDataArray = iterator_to_array($customerVehicleData);

// Parcourir les contrats et ajouter les données du client et du véhicule correspondant
foreach ($contracts as &$contract) {
    // Supprimer les espaces indésirables de 'customer_uid'
    $customerId = trim($contract['customer_uid']);
    // Supprimer les espaces indésirables de 'vehicle_uid'
    $vehicleId = trim($contract['vehicle_uid']);

    // Utiliser les identifiants traités pour récupérer les données correspondantes
    $customerData = current(array_filter($customerVehicleDataArray, function($data) use ($customerId) {
        return $data['_id'] == $customerId;
    }));

    $vehicleData = current(array_filter($customerVehicleDataArray, function($data) use ($vehicleId) {
        return $data['_id'] == $vehicleId;
    }));

    var_dump($vehicleData);

    // Ajouter les données du client et du véhicule correspondant
    $contract['customer_name'] = $customerData['first_name'] . ' ' . $customerData['second_name'];
    $contract['vehicle_licence_plate'] = $vehicleData['vehicle'][0]['licence_plate'];
}


    return $contracts;
}*/

}

