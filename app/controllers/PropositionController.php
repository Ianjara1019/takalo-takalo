<?php
require_once __DIR__ . '/../models/Proposition.php';
require_once __DIR__ . '/../models/Objet.php';
require_once __DIR__ . '/../validators/FormValidator.php';
require_once __DIR__ . '/../helpers/RequestHelper.php';

class PropositionController {
    private $propositionModel;
    private $objetModel;
    
    public function __construct() {
        $this->propositionModel = new Proposition();
        $this->objetModel = new Objet();
    }

    public function creer() {
        if (!isset($_SESSION['user_id'])) {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => false,
                    'message' => 'Vous devez être connecté.'
                ], 401);
                return;
            }
            Flight::redirect('/login');
            return;
        }

        $objetDemandeId = $_POST['objet_demande_id'] ?? null;
        $objetProposeId = $_POST['objet_propose_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        $objetDemande = null;
        $objetPropose = null;
        if (is_numeric($objetDemandeId) && is_numeric($objetProposeId)) {
            $objetDemande = $this->objetModel->getById($objetDemandeId);
            $objetPropose = $this->objetModel->getById($objetProposeId);
        }

        $errors = FormValidator::validatePropositionData(
            [
                'objet_demande_id' => $objetDemandeId,
                'objet_propose_id' => $objetProposeId,
            ],
            $objetDemande,
            $objetPropose,
            (int) $_SESSION['user_id']
        );

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
            $redirectPath = (is_numeric($objetDemandeId) && (int) $objetDemandeId > 0)
                ? '/objet/' . (int) $objetDemandeId
                : '/objets';
            Flight::redirect($redirectPath);
            return;
        }
        
        $data = [
            'objet_propose_id' => $objetProposeId,
            'objet_demande_id' => $objetDemandeId,
            'utilisateur_propose_id' => $_SESSION['user_id'],
            'utilisateur_demande_id' => $objetDemande['utilisateur_id'],
            'message' => $message === '' ? null : $message
        ];
        
        if ($this->propositionModel->create($data)) {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => true,
                    'message' => "Proposition d'échange envoyée !",
                    'redirect' => '/objet/' . $objetDemandeId
                ]);
                return;
            }
            $_SESSION['success'] = "Proposition d'échange envoyée !";
        } else {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => false,
                    'message' => "Erreur lors de l'envoi de la proposition."
                ], 500);
                return;
            }
            $_SESSION['error'] = "Erreur lors de l'envoi de la proposition.";
        }
        
        Flight::redirect('/objet/' . $objetDemandeId);
    }
    
    public function mesEchanges() {
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return;
        }
        
        $propositionsRecues = $this->propositionModel->getPropositionsRecues($_SESSION['user_id']);
        $propositionsEnvoyees = $this->propositionModel->getPropositionsEnvoyees($_SESSION['user_id']);
        
        Flight::render('user/echanges', [
            'propositionsRecues' => $propositionsRecues, 
            'propositionsEnvoyees' => $propositionsEnvoyees
        ], 'content');
        Flight::render('layouts/main', ['title' => 'Mes échanges']);
    }
    
    public function accepter($id) {
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return;
        }
        
        $proposition = $this->propositionModel->getById($id);
        
        if ($proposition['utilisateur_demande_id'] != $_SESSION['user_id']) {
            Flight::redirect('/echanges');
            return;
        }
        
        if ($this->propositionModel->accepter($id)) {
            $_SESSION['success'] = "Échange accepté ! Les objets ont changé de propriétaire.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'acceptation.";
        }
        
        Flight::redirect('/echanges');
    }
    
    public function refuser($id) {
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return;
        }
        
        $proposition = $this->propositionModel->getById($id);
        
        if ($proposition['utilisateur_demande_id'] != $_SESSION['user_id']) {
            Flight::redirect('/echanges');
            return;
        }
        
        if ($this->propositionModel->refuser($id)) {
            $_SESSION['success'] = "Proposition refusée.";
        } else {
            $_SESSION['error'] = "Erreur lors du refus.";
        }
        
        Flight::redirect('/echanges');
    }
}
