# 🚀 Guide de démarrage rapide - Takalo-takalo

## Installation en 5 minutes

### 1️⃣ Cloner le projet
```bash
git clone [URL_VOTRE_REPO]
cd takalo-takalo
```

### 2️⃣ Installer les dépendances
```bash
composer install
```
Si vous n'avez pas Composer : https://getcomposer.org/download/

### 3️⃣ Configurer la base de données

**a) Créer la base de données**
```sql
CREATE DATABASE takalo_takalo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**b) Importer les données**
```bash
mysql -u root -p takalo_takalo < database.sql
```

**c) Configurer la connexion** (optionnel si différent de localhost/root)

Éditez `config/database.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'takalo_takalo');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4️⃣ Donner les permissions
```bash
chmod -R 777 public/uploads
```

### 5️⃣ Démarrer le serveur

**Option A - Serveur PHP (simple et rapide)**
```bash
cd public
php -S localhost:8000
```

**Option B - Apache/Nginx**
Configurez un virtual host pointant vers `public/`

### 6️⃣ Accéder à l'application

**Interface utilisateur :** http://localhost:8000
- Email : jean.rakoto@email.com
- Mot de passe : password123

**Interface admin :** http://localhost:8000/admin
- Utilisateur : admin
- Mot de passe : admin123

---

## 📋 Checklist de vérification

- [ ] Composer installé
- [ ] Base de données créée
- [ ] Fichier SQL importé
- [ ] Dossier uploads accessible en écriture
- [ ] Serveur démarré
- [ ] Login utilisateur fonctionne
- [ ] Login admin fonctionne
- [ ] Upload de photos fonctionne
- [ ] Recherche fonctionne
- [ ] Proposition d'échange fonctionne
- [ ] Acceptation d'échange fonctionne

---

## 🔧 Résolution des problèmes courants

### Erreur "Flight not found"
```bash
composer install
```

### Erreur de connexion à la base de données
Vérifiez les paramètres dans `config/database.php`

### Photos ne s'uploadent pas
```bash
chmod -R 777 public/uploads
```

### Page blanche
Activez l'affichage des erreurs dans `public/index.php` :
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### Erreur 404 sur toutes les pages
Vérifiez que mod_rewrite est activé pour Apache :
```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

---

## 📱 Tester rapidement

### Scénario de test complet

1. **Inscription** d'un nouvel utilisateur
2. **Ajout** de 2-3 objets avec photos
3. **Recherche** d'objets par catégorie
4. **Consultation** de la fiche d'un objet
5. **Proposition** d'échange
6. **Connexion** avec un autre compte
7. **Acceptation** de la proposition
8. **Vérification** de l'historique d'appartenance
9. **Connexion admin** 
10. **Consultation** des statistiques

---

## 🎯 Fonctionnalités principales

### Utilisateurs
- ✅ Inscription / Connexion
- ✅ Gestion des objets (CRUD)
- ✅ Upload de photos multiples
- ✅ Recherche par titre et catégorie
- ✅ Proposition d'échange
- ✅ Acceptation/Refus d'échanges
- ✅ Historique d'appartenance public

### Admin
- ✅ Connexion sécurisée
- ✅ Gestion des catégories (CRUD)
- ✅ Statistiques complètes
- ✅ Répartition par catégorie

---

## 📞 Support

Pour toute question, consultez le fichier `README.md` complet.

**Bon développement ! 🚀**
