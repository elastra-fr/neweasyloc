# Fil Rouge 2.2 – Développer les composant d’accès aux données.
# Réalisation d’une bibliothèque d’accès aux données pour la société EasyLoc

## Environnement de développement et de test

- PHP 8.0.26
- Server Apache 2.4.54.2
- Microsoft SQL Server 2022 (RTM) - 16.0.1000.6 (X64)   
- MongoDb 7.0.8

Installation des extensions mongodb et sqlserver dans le dossier ext de la version de php concernée.

Activation des extensions dans les fichier php.ini. Ne pas oublier l'activation des extensions pour le terminal.

## Outils en ligne de commande
Le projet contient un outils en ligne de commande qui va vérifier les connexions aux bases de données, créer les tables Contract et Billing si elles n’existent pas et permettre de tester les fonctionnalités de la bibliothèque.

Pour utiliser l'outils : php app-cli.php

Menu de l'outils en lignes de commande :

    1- Requêtes globales
        -Liste des locations en retard
        -Liste des locations non payées
        -Nombre de retards entre deux dates données
        -Obtenir le nombre de retard moyen par client
        -Obtenir la moyenne du temps de retard par véhicule
        -Obtenir tous les contrats regroupés par véhicules
        -Obtenir tous les contrats regroupés par clients
        -Retour au menu principal

    2- Requête par Contrat  
            -Créer un Contrat à la date actuelle
            -Créer un Contrat à une autre date
            -Rechercher un Contrat par Id 
            -Afficher tous les contrats
                    -Modifier un contrat
                    -Supprimer un contrat
                    -Lister les paiements pour ce contrat
                    -Vérifier le paiement intégrale pour ce contrat
                    -retour
            -Retour au menu principal

    3- Requêtes par Client
            - Créer un client
            - Rechercher un client par nom et prénom
            -Afficher tous les clients
                    - Lister tous les contrats pour un client
                    - Lister tous les contrats en cours pour un clients
                    - Modifier un client
                    - Supprimer un client
                    -Retour
             -Retour au menu principal

    4- Requêtes par véhicule
            - Créer un véhicule
            - Rechercher un véhicule par son immatriculation
            - Rechercher un vehicule avec km supérieur
            - Rechercher un vehicule avec km inférieur
            - Afficher tous les véhicules
                    -Obtenir tous les contrats pour ce véhicule
                    - Modifier un véhicule
                    - Supprimer un véhicule
                    -Retour
             -Retour au menu principal

    5- Requêtes sur les paiements
            - Créer un paiement
            - Rechercher un paiement par Id
            -Afficher tous les paiements
                    - Modifier un paiement
                    - Supprimer un paiement
             -Retour au menu principal

    6- Quitter l'application


## Architecture générales

Le projet est structuré autour de plusieurs composants principaux :

1- Classes de connexion : 
Ces classes gèrent la connexion aux bases de données SQL Server et MongoDB. Elles sont définies dans database/SqlSrv_con.php et database/MongoDb_con.php.

2- Classes abstraites : Ces classes, spécifiques à chaque système de gestion de base de données (SGBD), implémentent les opérations CRUD de base. Elles sont étendues par les classes concrètes pour chaque modèle de données.

3- Classes concrètes : Ces classes étendent les classes abstraites et ajoutent des méthodes spécifiques pour chaque modèle de données. Par exemple, la classe ContractModel dans model/Contract.php et la classe BillingModel dans model/Billing.php.

4- Modèles de données : Ces classes définissent les structures de données pour chaque entité dans l'application, comme Contract, Billing, et Vehicle.

5- Fichier de configuration : Le fichier globals.php dans le dossier config/ contient les éléments de connexion pour les SGBD.

Ce fichier n'est pas repris dans le repository sur GitHub.


Le projet utilise des namespaces pour organiser le code et éviter les collisions de noms.

Il a été choisi de limiter l'abstraction pour chaque SGDB pour tenir compte des spécificités de chaque système, en gardant un code compréhensible et maintenable . Un niveau d'abstraction supérieur aurait été possible en regroupant par type de SGBD (SQL et noSQL) mais cela obligerait à rendre générique le code ce qui limiterait l'interêt d'utiliser un SGBD plutôt qu'un autre.

## Difficultées liées à l'architecture imposée par le client

Type de champs Char 255 va créer des difficultés au dans les méthodes d'aggrégation entre SQL Server et Mongo, notamment au niveau du stockage des OID Customer et Vehicle dans la table Contract SQL Server car ce type de données va remplir les espaces à droite au maximum du champ et donc générer des erreurs lors des "jointures". La fonction trim() a été utilisée pour réduire ces erreurs. Toutefois revoir l'architecture avec avec une colonne varchar avec une longueur variable et ne remplissant pas les espaces à droite permettrait de réduire ces problème et de simplifier le code.

## Sécurité 

Dans les classes abstraites utilisation des marqueurs de position pour les paramètres de la requête SQL et echappement des valeurs des paramètres avant de les utiliser dans la requête, ce qui permet de prévenir les injections SQL en séparant la structure de la requête des données fournies par l'utilisateur.


### Modularité, nouvelles tables et évolutions des sgbd

#### Modularité 

Dans le projet actuel, la modularité est assurée par l'utilisation de classes abstraites et concrètes. Les classes abstraites définissent des méthodes de base pour les opérations CRUD (Create, Read, Update, Delete) qui sont communes à tous les modèles de données. Les classes concrètes étendent ces classes abstraites et ajoutent des méthodes spécifiques pour chaque modèle de données.

#### Ajout d'une nouvelle table

Pour ajouter une nouvelle table à la base de données, vous devrez suivre ces étapes :

- Créer la table dans la base de données : Vous devrez d'abord créer la table dans votre base de données SQL Server ou MongoDB.

- Créer une classe de modèle de données : Ensuite, vous devrez créer une nouvelle classe dans le dossier model/ qui représente la nouvelle table. Cette classe devrait étendre la classe abstraite correspondante (AbstractSqlSrv pour SQL Server ou AbstractMongoDb pour MongoDB) et implémenter les méthodes spécifiques à la nouvelle table.

- Définir les tests unitaires correspondant.

#### Evolution des SGBD

Pour ajouter un nouveau système de gestion de base de données (SGBD) comme PostgreSQL ou Elasticsearch, vous devrez suivre ces étapes :

- Créer une classe de connexion : Vous devrez créer une nouvelle classe de connexion dans le dossier database/. Cette classe gérera la connexion à la nouvelle base de données. Par exemple, pour PostgreSQL, vous pourriez créer un fichier Postgres_con.php.

- Créer une classe abstraite : Vous devrez créer une nouvelle classe abstraite qui implémente les opérations CRUD de base pour le nouveau SGBD. Cette classe sera étendue par les classes concrètes pour chaque modèle de données.

- Mettre à jour les classes de modèle de données : Pour chaque modèle de données, vous devrez créer une nouvelle classe concrète qui étend la nouvelle classe abstraite. Ces classes implémenteront les méthodes spécifiques à chaque modèle de données pour le nouveau SGBD.

- Mettre à jour le fichier de configuration : Vous devrez ajouter les détails de connexion pour le nouveau SGBD dans le fichier globals.php dans le dossier config/.

- Définir les tests unitaires correspondant.




### Implémentation Sharding concernant la table Billing

Le sharding est une technique de partitionnement de données utilisée dans les bases de données distribuées. Il consiste à diviser horizontalement une base de données en plusieurs fragments plus petits appelés "shards", chacun contenant une partie des données. Chaque shard peut être stocké sur un serveur différent comme demandé ici par le client. Le sharding permet de distribuer la charge de travail et de stockage sur plusieurs serveurs, améliorant ainsi les performances et la capacité de montée en charge du système. 

Pour mettre en place du sharding sur une table, il est généralement judicieux de choisir une clé de sharding qui répartit les données de manière équilibrée entre les différents shards.

Dans le cas de la table "Billing", la clé la plus pertinente pour le sharding va dépendre de la manière dont les données sont généralement consultées et de leur distribution. Dans le cahier des charges le client ne donne pas de précision sur la distribution des requêtes, la charge et les performances. Un échange avec le client sur ces points et des tests sont nécessaires.

Si aucune clé unique ne permet de répartir de manière équilibrée la charge, une clé composite pourrait être envisagée.

Afin de proposer une implémentation nous pouvons prendre arbitrairement comme clé le Contract_id. Cette clé pourra évoluer en cas d'identification d'une clé plus équilibrée.

Pour l'implémentation, plusieurs solutions en fonctions du ou des sgbd choisis pour constituer les shards :
-  soit revoir la structure et le niveau d'abstraction mise en place pour créer un classe abstraite commune aux sgbd constituant les shards. Comme expliqué dans l'architecture générale, cela va rendre le code plus générique et reduire l'intérêt d'utiliser des types de SGBD différents. 
- continuer avec l'architecture actuelle en créant autant de classe abstraite/concrète que de shards ce qui va créer une certaine lourdeur et rendre difficile la maintenance en cas de multiplication des shards.


Dans tous les cas il sera nécessaire de créer une classe sharding afin de déterminer le serveur à utiliser en fonction de la clé choisie et instancier la classe concrète d'un shard pour utiliser ses méthodes. Par exemple :

<pre><code class="php">

class ShardedDatabase
{
    protected $shardKey;

    public function __construct($shardKey)
    {
        $this->shardKey = $shardKey;
    }

    public function getDatabase()
    {
        if ($this->shardKey % 2 == 0) {
            
            return new /*Instance d'un shard*/
        } else {
            // Utiliser le serveur 3 ou 4 pour les clés impaires
            return new /*Instance d'un autre shard*/ 
        }
    }
}

</code></pre>













