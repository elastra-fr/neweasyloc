# Fil Rouge 2.2 – Développer les composant d’accès aux données.
# Réalisation d’une bibliothèque d’accès aux données pour la société EasyLoc

## Environnement de développement et de test
PHP 8.0.26
Server Apache
Installation des extensions mongodb et sqlserver dans le dossier ext de la version de php concernée.
Activation des extensions dans les fichier php.ini. Ne pas oublier l'activation des extensions pour le terminal.

## Outils en ligne de commande
Le projet contient un outils en ligne de commande qui va vérifier les connexions aux bases de données, créer les tables Contract et Billing si elles n’existent pas et permettre de tester les fonctionnalités de la bibliothèque.


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




## Bibliothèques




### Classes de connexion

### Classes abstraites

### Classes concrètes

### Modularité, nouvelles tables et évolutions des sgbd

### Implémentation Sharding concernant la table Billing



