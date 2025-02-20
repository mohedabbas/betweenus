# Galerie Photo

Une application web de galerie photo avec système d'authentification permettant aux utilisateurs de télécharger et gérer leurs photos.

## 🚀 Fonctionnalités

- Système d'authentification complet
- Téléchargement de photos
- Affichage des photos en galerie
- Vue détaillée des photos
- Système de récupération de mot de passe
- Interface responsive

## 📋 Prérequis

- PHP 8.0 ou supérieur
- Serveur web (Apache/Nginx)
- MySQL/MariaDB

## 🛠 Installation

1. Clonez le repository

2. Installez les dépendances

3. Configurez votre base de données dans le fichier de configuration

4. Lancez les migrations : docker-compose exec php php /home/php/migration_script.php up

## 🎨 Personnalisation

Le style de l'application peut être personnalisé en modifiant les fichiers SCSS dans le dossier `sources/scss/components/`.

## 🔒 Sécurité

- Protection contre les injections SQL
- Hachage sécurisé des mots de passe
- Validation des fichiers uploadés
- Protection CSRF

## 📝 Licence

[Votre licence]

## 👥 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou soumettre une pull request.

## 📁 Structure du Projet

├── docker/
│   ├── php/
│   ├── nginx/
│   └── mysql/
├── sources/
│   ├── app/
│   │   ├── controllers/
│   │   ├── models/
│   │   └── middleware/
│   ├── config/
│   │   ├── database.php
│   │   └── app.php
│   ├── public/
│   │   ├── index.php
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── scss/
│   │   ├── components/
│   │   └── partials/
│   └── views/
│       ├── auth/
│       │   ├── login.php
│       │   ├── register.php
│       │   └── ...
│       ├── gallery/
│       └── layouts/
├── docker-compose.yml
├── composer.json
└── README.md

