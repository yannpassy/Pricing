# Pricing


## Contexte

Un vendeur vend 1500 jeux vidéo neufs et d'occasion sur la marketplace Amazon.fr.
Il a des centaines de concurrents différents qui offrent les mêmes produits, en état neuf ou d'occasion.
Certains produits de ses concurrents sont en moins bon état que les siens, d'autres en meilleur état que les siens. 

Il n’existe que 5 états de produits possibles : « Etat moyen », « Bon état », « Très bon état », « Comme neuf » et « Neuf »

Il définit des bornes de prix pour chacun de ses articles : un prix plancher et un prix haut. 
Il veut être moins cher que la concurrence mais ne pas brader ses produits : ne pas être nécessairement le moins cher que la concurrence s’il a des produits en meilleur état,
et en ne descendant pas en dessous de son prix plancher.

Il définit une stratégie de prix qui consiste à être :
  - X centimes moins cher si son état est le même que celui de son concurrent.
  - Y centimes moins cher si son état est moins bon que celui de son concurrent.
  - à sa borne haute s’il n’y a pas de concurrence ou si son état est meilleur que tous les concurrents.
  
Son prix devrait être le moins cher des états égaux ou meilleurs que le sien.
Dans tous les cas, ne jamais descendre en dessous de son prix plancher et ne jamais monter au-dessus de la borne haute.



## Routes

### Initialisation de la database

```bash
http://127.0.0.1:8000/data/initiate
```
Le but de cette route est de créer un jeux de données de base pour pouvoir estimer un prix.
Nous verrons en dessous plus d'information concernant la base de données.

### Estimation de prix

```bash
http://127.0.0.1:8000/pricing
```
Cet api permet d'estimer un prix en fonction des concurrenst dans la database.
Il faudra mettre dans cette requète un body de ce type ci :

```bash
{
    "jeuID" : 1,
    "prixMin" : 10,
    "prixMax" : 29.49,
    "etat" : "moyen"
}
```
il y a 4 paramètres dans le body:
  - jeuID : un référentiel du jeu. il représente l'id du jeu dans la database
  - prixMin : le prix minimum que peut s'accorder le vendeur sur le jeu.
  - prixMax : le prix maximum que donnera le vendeur au jeu s'il n'a aucune concurrence ou s'il est le seul à posséder le jeu en meilleure état
  - etat : représente l'état du jeu. Les états que comprend le programme dans le body sont : "moyen", "bon", "très bon", "comme neuf", "neuf".
  
La réponse de cet api est en format JSON de cette forme:
```bash
{
    "message": "un concurrent possédant le jeu dans un meilleur état le vend 10.5 euros",
    "prix": 10
}
```
il y a 2 paramètres dans la réponse:
  - message: un petit descriptif de la situation pouvant justifié le prix estimé.
  - prix :  le prix estimé.

l'ensemble de l'algo pour estimer le prix se trouve dans:

```bash
./src/Controller/PricingController.php
```
l'algo y est bien expliqué.
  
## Database

### Tables
Le programme utilise 2 tables "jeux" et "concurrent":
  - jeux:
      - id : l'id du jeu.
      - nom :  le nom du jeu.
      - editeur : l'éditeur du jeu.
      - type : le type du jeu (platforme, course, rpg, etc).
      
  - conurrent:
      - id : l'id de la vente du concurent.
      - jeu_id_id : clé étrangère sur la table "jeux". (je n'ai pas choisi ce nom. C'est le nom donné par defaut durant la migration. J'ai essayé de le changer mais j'ai eu que des problèmes).
      - vendeur : le nom du vendeur.
      - prix : le prix donnée par le vendeur.
      - etat : l'état du jeu.
      
### Créer les tables via les commandes
      
Vous pouvez créer ces tables à partir des entity présent dans le programme.
Voici les commandes:
  
```bash
$ symfony console make:migration
```
Cette commande permet de créer un fichier migration à partir des entity.
Ce fichier migration contien les requètes sql de la crationdes classe.

Ensuit, tapez la commande suivante:
```bash
$ symfony console doctrine:migrations:migrate
```
Cette commande sert à creer dans la database les tables à partir du fichier migration.

NB: ne pas oublier de configuer l'accès à la database dans le fichier ".env"

### Les requètes sql des tables

si vous voulez creer les tables manuellement dans la database sans passer par les migrations, voici les requètes:
```bash
CREATE TABLE concurrent (
      id INT AUTO_INCREMENT NOT NULL, 
      jeu_id_id INT NOT NULL,
      vendeur VARCHAR(255) NOT NULL,
      prix DOUBLE PRECISION NOT NULL,
      etat VARCHAR(255) NOT NULL,
      INDEX IDX_DE7EC08C4DA19DAF (jeu_id_id),
      PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
      
CREATE TABLE jeux (
      id INT AUTO_INCREMENT NOT NULL,
      nom VARCHAR(255) NOT NULL,
      editeur VARCHAR(255) DEFAULT NULL,
      type LONGTEXT DEFAULT NULL,
      PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
      
ALTER TABLE concurrent ADD CONSTRAINT FK_DE7EC08C4DA19DAF FOREIGN KEY (jeu_id_id) REFERENCES jeux (id);      
```

     
     
     
