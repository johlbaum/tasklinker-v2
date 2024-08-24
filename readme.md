# OCR Développeur d'application PHP Symfony - Projet n° 8 : Mettez en place un outil de gestion de projet avec Symfony

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

composer install

## 3. Configurer l’environnement

Créez un fichier `.env.local` à la racine du projet.

Ajoutez la ligne suivante dans le fichier `.env.local` :

DATABASE_URL="mysql://utilisateur:mot_de_passe@127.0.0.1:8889/tasklinker?charset=utf8"

Remplacez `<utilisateur>` et `<mot_de_passe>` par les valeurs appropriées pour votre environnement.

## 4. Créer la base de données

Créez la base de données avec la commande suivante :

symfony console doctrine:database:create --if-not-exists

## 5. Créer la structure de la base de données

Appliquez les migrations pour créer la structure de la base de données :

symfony console doctrine:migrations:migrate  

## 6. Générer les données de test

Chargez les données de test avec la commande suivante :

symfony console doctrine:fixtures:load  


