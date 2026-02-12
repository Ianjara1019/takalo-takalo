# Takalo-takalo - Système d'échange d'objets

## Description
Takalo-takalo est une plateforme web permettant aux utilisateurs d'échanger des objets entre eux. Les utilisateurs peuvent créer un compte, ajouter leurs objets, consulter les objets des autres utilisateurs et proposer des échanges.

## Technologies utilisées
- **Backend** : PHP 7.4+
- **Framework** : FlightPHP (MVC)
- **Base de données** : MySQL / PostgreSQL
- **Frontend** : Bootstrap 5, HTML5, CSS3, JavaScript
- **Icônes** : Bootstrap Icons

## Fonctionnalités

### Backoffice (Administration)
- ✅ Connexion admin (login par défaut : admin / admin123)
- ✅ Gestion des catégories (CRUD complet)
- ✅ Statistiques :
  - Nombre d'utilisateurs inscrits
  - Nombre d'échanges effectués
  - Nombre d'objets
  - Répartition par catégorie

### Frontoffice (Utilisateurs)
- ✅ Inscription et connexion
- ✅ Gestion des objets personnels :
  - Ajouter un objet (titre, description, photos multiples, prix estimatif)
  - Modifier un objet
  - Supprimer un objet
- ✅ Consultation des objets disponibles
- ✅ Barre de recherche (titre + catégorie)
- ✅ Fiche détaillée d'un objet
- ✅ Proposition d'échange
- ✅ Gestion des échanges :
  - Liste des propositions reçues
  - Liste des propositions envoyées
  - Acceptation / Refus des propositions
- ✅ Historique d'appartenance d'un objet (visible au public)
  - Affichage des propriétaires successifs
  - Dates et heures d'échange

## Installation

### 1. Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7+ ou PostgreSQL
- Apache ou Nginx
- Composer

### 2. Installation du projet

```bash
# Cloner le projet
git clone [URL_DE_VOTRE_REPO]
cd takalo-takalo

# Installer les dépendances
composer install
```

### 3. Configuration de la base de données

1. Créer une base de données MySQL :
```sql
CREATE DATABASE takalo_takalo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importer le fichier SQL :
```bash
mysql -u root -p takalo_takalo < database.sql
```

3. Configurer la connexion dans `config/database.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'takalo_takalo');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Configuration du serveur

#### Avec Apache
Créer un virtual host pointant vers le dossier `public/` :

```apache
<VirtualHost *:80>
    ServerName takalo-takalo.local
    DocumentRoot /chemin/vers/takalo-takalo/public
    
    <Directory /chemin/vers/takalo-takalo/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Avec PHP Built-in Server (développement)
```bash
cd public
php -S localhost:8000
```

### 5. Permissions
Donner les permissions d'écriture au dossier uploads :
```bash
chmod -R 777 public/uploads
```

## Structure du projet

```
takalo-takalo/
├── app/
│   ├── controllers/
│   ├── models/
│   │   ├── Admin.php
│   │   ├── Utilisateur.php
│   │   ├── Categorie.php
│   │   ├── Objet.php
│   │   ├── Proposition.php
│   │   └── Statistique.php
│   └── views/
│       ├── admin/
│       │   ├── login.php
│       │   ├── dashboard.php
│       │   └── categories.php
│       ├── user/
│       │   ├── login.php
│       │   ├── inscription.php
│       │   ├── mes_objets.php
│       │   ├── objets.php
│       │   ├── fiche_objet.php
│       │   └── echanges.php
│       └── layouts/
│           ├── main.php
│           └── admin.php
├── config/
│   └── database.php
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   ├── js/
│   └── uploads/
├── database.sql
├── composer.json
└── README.md
```

## Comptes de test

### Administrateur
- Utilisateur : `admin`
- Mot de passe : `admin123`

### Utilisateurs
1. Email : `jean.rakoto@email.com` - Mot de passe : `password123`
2. Email : `marie.rabe@email.com` - Mot de passe : `password123`
3. Email : `paul.randria@email.com` - Mot de passe : `password123`
4. Email : `sophie.rasoa@email.com` - Mot de passe : `password123`

## Fonctionnement des échanges

1. L'utilisateur A propose d'échanger son objet contre l'objet de l'utilisateur B
2. L'utilisateur B reçoit la proposition dans "Mes échanges"
3. L'utilisateur B peut accepter ou refuser la proposition
4. Si accepté :
   - Les deux objets changent de propriétaire
   - Un enregistrement est créé dans la table `echanges`
   - L'historique d'appartenance est mis à jour pour les deux objets
   - Le statut des objets passe à "échangé"

## Base de données

### Tables principales
- `admins` : Administrateurs du système
- `utilisateurs` : Utilisateurs inscrits
- `categories` : Catégories d'objets
- `objets` : Objets mis en ligne
- `photos_objets` : Photos des objets
- `propositions` : Propositions d'échange
- `echanges` : Échanges effectués
- `historique_appartenance` : Historique des propriétaires

## Développeurs

**Nom :** [RAHARINJATOVO Ianjara Nomena] - **N° ETU :** [4011]

## Liens

- **Liste des tâches** : [LIEN_VERS_FICHIER_EXCEL]
- **Repository GIT** : [LIEN_GIT_PUBLIC]

## Licence

Février 2026
