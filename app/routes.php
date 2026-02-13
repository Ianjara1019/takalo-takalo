<?php

/**
 * @var array{
 *   utilisateurCtrl: UtilisateurController,
 *   objetCtrl: ObjetController,
 *   propositionCtrl: PropositionController,
 *   adminCtrl: AdminController
 * } $controllers
 */
$controllers = $controllers ?? [];

$utilisateurCtrl = $controllers['utilisateurCtrl'] ?? new UtilisateurController();
$objetCtrl = $controllers['objetCtrl'] ?? new ObjetController();
$propositionCtrl = $controllers['propositionCtrl'] ?? new PropositionController();
$adminCtrl = $controllers['adminCtrl'] ?? new AdminController();

// Routes utilisateur
Flight::route('/', function () {
    if (isset($_SESSION['user_id'])) {
        Flight::redirect('/objets');
        return;
    }

    Flight::redirect('/login');
});

Flight::route('GET /inscription', [$utilisateurCtrl, 'showInscription']);
Flight::route('POST /inscription', [$utilisateurCtrl, 'inscrire']);

Flight::route('GET /login', [$utilisateurCtrl, 'showLogin']);
Flight::route('POST /login', [$utilisateurCtrl, 'login']);
Flight::route('/logout', [$utilisateurCtrl, 'logout']);

Flight::route('GET /mes-objets', [$objetCtrl, 'mesObjets']);
Flight::route('GET /objets', [$objetCtrl, 'listeObjets']);
Flight::route('GET /recherche', [$objetCtrl, 'recherche']);
Flight::route('GET /objet/@id', [$objetCtrl, 'ficheObjet']);

Flight::route('POST /objet/ajouter', [$objetCtrl, 'ajouter']);
Flight::route('POST /objet/modifier/@id', [$objetCtrl, 'modifier']);
Flight::route('GET /objet/supprimer/@id', [$objetCtrl, 'supprimer']);

Flight::route('POST /proposition/creer', [$propositionCtrl, 'creer']);
Flight::route('GET /echanges', [$propositionCtrl, 'mesEchanges']);
Flight::route('GET /proposition/accepter/@id', [$propositionCtrl, 'accepter']);
Flight::route('GET /proposition/refuser/@id', [$propositionCtrl, 'refuser']);

// Routes admin
Flight::route('GET /admin/login', [$adminCtrl, 'showLogin']);
Flight::route('POST /admin/login', [$adminCtrl, 'login']);
Flight::route('/admin/logout', [$adminCtrl, 'logout']);

Flight::route('GET /admin', [$adminCtrl, 'dashboard']);

Flight::route('GET /admin/categories', [$adminCtrl, 'categories']);
Flight::route('POST /admin/categorie/ajouter', [$adminCtrl, 'ajouterCategorie']);
Flight::route('POST /admin/categorie/modifier/@id', [$adminCtrl, 'modifierCategorie']);
Flight::route('GET /admin/categorie/supprimer/@id', [$adminCtrl, 'supprimerCategorie']);

