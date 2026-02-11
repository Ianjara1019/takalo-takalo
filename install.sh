#!/bin/bash

echo "======================================"
echo "Installation de Takalo-takalo"
echo "======================================"
echo ""

# Vérifier si Composer est installé
if ! command -v composer &> /dev/null
then
    echo "❌ Composer n'est pas installé. Veuillez l'installer d'abord : https://getcomposer.org"
    exit 1
fi

echo "✅ Composer détecté"
echo ""

# Installer les dépendances
echo "📦 Installation des dépendances..."
composer install

if [ $? -eq 0 ]; then
    echo "✅ Dépendances installées avec succès"
else
    echo "❌ Erreur lors de l'installation des dépendances"
    exit 1
fi

echo ""

# Créer le dossier uploads s'il n'existe pas
echo "📁 Création du dossier uploads..."
mkdir -p public/uploads
chmod -R 777 public/uploads
echo "✅ Dossier uploads créé"

echo ""

# Informations de configuration
echo "======================================"
echo "Configuration de la base de données"
echo "======================================"
echo ""
echo "1. Créez une base de données MySQL :"
echo "   CREATE DATABASE takalo_takalo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo ""
echo "2. Importez le fichier database.sql :"
echo "   mysql -u root -p takalo_takalo < database.sql"
echo ""
echo "3. Modifiez les paramètres dans config/database.php si nécessaire"
echo ""

echo "======================================"
echo "Démarrage du serveur"
echo "======================================"
echo ""
echo "Option 1 - Serveur PHP intégré (développement) :"
echo "   cd public && php -S localhost:8000"
echo ""
echo "Option 2 - Apache/Nginx :"
echo "   Configurez un virtual host pointant vers le dossier 'public/'"
echo ""

echo "======================================"
echo "Comptes de test"
echo "======================================"
echo ""
echo "Admin :"
echo "  - URL : http://localhost:8000/admin/login"
echo "  - Utilisateur : admin"
echo "  - Mot de passe : admin123"
echo ""
echo "Utilisateur :"
echo "  - URL : http://localhost:8000/login"
echo "  - Email : jean.rakoto@email.com"
echo "  - Mot de passe : password123"
echo ""

echo "✅ Installation terminée !"
echo ""
