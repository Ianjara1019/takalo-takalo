# Utilite concrete de chaque partie du projet Takalo-takalo

Ce document explique, de facon pratique, a quoi sert chaque element important du projet.

## 1) Point d'entree et demarrage

- `public/index.php`:
  - point d'entree HTTP de l'application.
  - charge le bootstrap, les routes, puis lance `Flight::start()`.
- `app/bootstrap.php`:
  - demarre la session PHP.
  - charge l'autoload Composer et la config globale.
  - instancie les controllers (`UtilisateurController`, `ObjetController`, `PropositionController`, `AdminController`).
  - configure le dossier des vues pour Flight.
- `app/routes.php`:
  - declare toutes les routes (front utilisateur + backoffice admin).
  - connecte chaque URL a une methode de controller.

## 2) Configuration

- `config/database.php`:
  - contient les constantes de connexion DB (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).
  - definit la classe `Database` (singleton PDO) pour reutiliser une seule connexion.
  - definit aussi `BASE_URL`, `UPLOAD_DIR`, `UPLOAD_URL` utilisees dans l'app.
- `app/config.php`:
  - charge la configuration base de donnees dans le contexte applicatif.
- `composer.json`:
  - declare les dependances PHP (notamment Flight).

## 3) Logique metier (Controllers)

- `app/controllers/UtilisateurController.php`:
  - gere inscription, connexion utilisateur, deconnexion.
  - ecrit dans `$_SESSION` l'utilisateur connecte et les messages flash.
- `app/controllers/ObjetController.php`:
  - gere CRUD des objets utilisateur.
  - gere upload des photos, edition, suppression et pages liste/fiche/recherche.
- `app/controllers/PropositionController.php`:
  - cree les propositions d'echange.
  - liste les propositions recues/envoyees.
  - accepte/refuse une proposition.
- `app/controllers/AdminController.php`:
  - login/logout admin.
  - dashboard statistiques.
  - gestion des categories cote admin.

## 4) Acces donnees (Models)

- `app/models/Utilisateur.php`:
  - requetes SQL sur les utilisateurs (inscription, auth, etc.).
  - hash/verifie les mots de passe utilisateur.
- `app/models/Admin.php`:
  - authentification admin via `password_verify`.
- `app/models/Objet.php`:
  - CRUD objet.
  - gestion des photos d'objets.
  - historique d'appartenance.
  - changement de proprietaire lors des echanges.
- `app/models/Proposition.php`:
  - creation et consultation des propositions.
  - logique transactionnelle d'acceptation d'echange (creation `echanges` + changements de proprietaires).
- `app/models/Categorie.php`:
  - CRUD des categories.
- `app/models/Statistique.php`:
  - calcule les indicateurs admin (nb utilisateurs, objets, echanges, repartition par categorie).

## 5) Validation et utilitaires

- `app/validators/FormValidator.php`:
  - centralise les regles de validation serveur (inscription, objet, proposition).
  - evite de dupliquer les verifications dans les controllers.
- `app/helpers/RequestHelper.php`:
  - detecte si la requete est AJAX/JSON.
  - permet de repondre en JSON ou via redirection HTML selon le contexte.

## 6) Interface (Views)

### Layouts (gabarits)

- `app/views/layouts/main.php`:
  - squelette des pages utilisateur (header/menu/footer communs).
  - affiche le contenu de la page via `$content`.
  - affiche aussi les messages flash (`$_SESSION['success']`, `$_SESSION['error']`).
- `app/views/layouts/admin.php`:
  - meme principe pour l'interface admin.

### Pages utilisateur

- `app/views/user/login.php`: formulaire de connexion utilisateur.
- `app/views/user/inscription.php`: formulaire d'inscription.
- `app/views/user/objets.php`: liste publique des objets disponibles + recherche.
- `app/views/user/fiche_objet.php`: details d'un objet + historique + proposition d'echange.
- `app/views/user/mes_objets.php`: gestion des objets du user connecte.
- `app/views/user/echanges.php`: propositions recues/envoyees et actions accepter/refuser.

### Pages admin

- `app/views/admin/login.php`: formulaire de connexion admin.
- `app/views/admin/dashboard.php`: tableau de bord statistiques.
- `app/views/admin/categories.php`: gestion CRUD des categories.

## 7) Base de donnees

- `database.sql`:
  - cree toutes les tables (`admins`, `utilisateurs`, `objets`, `photos_objets`, `propositions`, `echanges`, `historique_appartenance`, etc.).
  - definit les relations (cles etrangeres) et les regles `ON DELETE`.
  - insere des donnees de test (comptes, categories, objets, propositions).

## 8) Upload et fichiers publics

- `public/uploads/`:
  - dossier de stockage des photos uploades.
- `public/uploads/.gitkeep`:
  - garde le dossier `uploads` versionne meme quand il est vide.

## 9) Docs et scripts utilitaires

- `README.md`:
  - documentation complete (fonctionnalites, installation, architecture).
- `QUICKSTART.md`:
  - guide rapide pour lancer le projet vite.
- `install.sh`:
  - script d'installation/initialisation pour simplifier la mise en route.
- `TODO.md` et `TODO_Projet_Takalo_Takalo_v2.xlsx`:
  - suivi des taches et avancement du projet.

## 10) Sessions et messages flash (important)

- `$_SESSION['user_id']` / `$_SESSION['admin_id']`:
  - servent a savoir qui est connecte et proteger les routes.
- `$_SESSION['success']` / `$_SESSION['error']`:
  - messages temporaires apres action + redirection (flash messages).
  - exemple: apres suppression d'objet, message affiche sur la page suivante.

## 11) Flux concret d'une requete

1. Requete arrive sur `public/index.php`.
2. Bootstrap charge config + controllers + session.
3. `app/routes.php` trouve la bonne route.
4. Le controller appelle le model pour lire/ecrire en DB.
5. Le controller rend une vue de contenu (`content`), puis un layout.
6. Le layout injecte `$content` et affiche les messages session.

