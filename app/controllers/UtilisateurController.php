<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../validators/FormValidator.php';
require_once __DIR__ . '/../helpers/RequestHelper.php';

class UtilisateurController {
    private $utilisateurModel;
    
    public function __construct() {
        $this->utilisateurModel = new Utilisateur();
    }
    
    public function showInscription() {
        Flight::render('user/inscription', [], 'content');
        Flight::render('layouts/main', ['title' => 'Inscription']);
    }

    public function inscrire() {
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'telephone' => trim($_POST['telephone'] ?? ''),
            'adresse' => trim($_POST['adresse'] ?? '')
        ];

        if ($data['telephone'] === '') {
            $data['telephone'] = null;
        }

        if ($data['adresse'] === '') {
            $data['adresse'] = null;
        }

        $errors = FormValidator::validateInscriptionData($data);
        if ($this->utilisateurModel->emailExiste($data['email'])) {
            $errors['email'] = 'Cet email est déjà utilisé.';
        }

        if (!empty($errors)) {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => false,
                    'message' => 'Veuillez corriger les erreurs du formulaire.',
                    'errors' => $errors
                ], 422);
                return;
            }

            $_SESSION['error'] = implode(' ', array_values($errors));
            Flight::redirect('/inscription');
            return;
        }
        
        if ($this->utilisateurModel->inscrire($data)) {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => true,
                    'message' => 'Inscription réussie ! Vous pouvez maintenant vous connecter.',
                    'redirect' => '/login'
                ]);
                return;
            }

            $_SESSION['success'] = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
            Flight::redirect('/login');
        } else {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => false,
                    'message' => "Erreur lors de l'inscription."
                ], 500);
                return;
            }

            $_SESSION['error'] = "Erreur lors de l'inscription.";
            Flight::redirect('/inscription');
        }
    }
    
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            Flight::redirect('/objets');
            return;
        }
        Flight::render('user/login', [], 'content');
        Flight::render('layouts/main', ['title' => 'Connexion']);
    }
    
    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => false,
                    'message' => 'Email et mot de passe sont obligatoires.'
                ], 422);
                return;
            }

            $_SESSION['error'] = "Email et mot de passe sont obligatoires.";
            Flight::redirect('/login');
            return;
        }

        $user = $this->utilisateurModel->login($email, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];

            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => true,
                    'message' => 'Connexion réussie.',
                    'redirect' => '/objets'
                ]);
                return;
            }

            Flight::redirect('/objets');
        } else {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => false,
                    'message' => "Email ou mot de passe incorrect."
                ], 401);
                return;
            }

            $_SESSION['error'] = "Email ou mot de passe incorrect.";
            Flight::redirect('/login');
        }
    }
    
    public function logout() {
        session_destroy();
        Flight::redirect('/login');
    }
}
