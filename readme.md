# OCR Développeur d'application PHP Symfony - Projet n° 10 : Mettez en place une gestion d'utilisateurs et d'authentification

**V2 du projet tasklinker :**

Création des pages de connexion et d’inscription.

Gestion des rôles d’accès : simple collaborateur et chef de projet.

Possiblité de modifier la fiche d’un employé afin de changer son rôle.

### Configuration de l'accès des pages en fonction des rôles :

- Seuls les chefs de projet peuvent créer et modifier un projet ;
- Seuls les chefs de projet peuvent modifier les fiches Employés ;
- Les employés ne peuvent voir que les projets auxquels ils ont accès (à l’exception des chefs de projet qui peuvent tous les voir) ;
- Les utilisateurs non connectés ne peuvent accéder qu’à la page de connexion et d’inscription.

### Données de test : 

- **Chef de projet :** 

E-mail : thomas.verdier@hotmail.fr 
Mot de passe : thomas

- **Collaborateur :**

E-mail : maxime.tournon@hotmail.fr 
Mot de passe : maxime

- **Collaborateur :**

E-mail : nathalie.motard@hotmail.fr
Mot de passe : nathalie

## Prérequis

- Un serveur local (MAMP, WAMP, LAMP, etc.)
- PHP
- MySQL
- Composer 
- Symfony CLI

## 1. Cloner le projet

Clonez le dépôt du projet avec la commande suivante :

git clone <URL_DU_DEPOT>
cd <NOM_DU_DOSSIER>

## 2. Installer les dépendances

Installez les dépendances du projet en utilisant Composer avec la commande suivante :
```bash
composer install
```

## 3. Configurer l’environnement

Créez un fichier `.env.local` à la racine du projet.

Ajoutez la ligne suivante dans le fichier `.env.local` :

DATABASE_URL="mysql://utilisateur:mot_de_passe@127.0.0.1:3306/tasklinker_v2?charset=utf8"

Remplacez `<utilisateur>` et `<mot_de_passe>` par les valeurs appropriées pour votre environnement.

Par défaut, MySQL utilise le port 3306. Si votre installation de MySQL utilise un port différent, ajustez la valeur en conséquence.

## 4. Créer la base de données

Créez la base de données avec la commande suivante :
```bash
symfony console doctrine:database:create --if-not-exists
```

## 5. Créer la structure de la base de données

Appliquez les migrations pour créer la structure de la base de données :
```bash
symfony console doctrine:migrations:migrate  
```

## 6. Générer les données de test

Chargez les données de test avec la commande suivante :
```bash
symfony console doctrine:fixtures:load  
```

