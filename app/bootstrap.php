<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

require_once __DIR__ . '/controllers/UtilisateurController.php';
require_once __DIR__ . '/controllers/ObjetController.php';
require_once __DIR__ . '/controllers/PropositionController.php';
require_once __DIR__ . '/controllers/AdminController.php';

Flight::set('flight.views.path', __DIR__ . '/views');

return [
    'utilisateurCtrl' => new UtilisateurController(),
    'objetCtrl' => new ObjetController(),
    'propositionCtrl' => new PropositionController(),
    'adminCtrl' => new AdminController(),
];

