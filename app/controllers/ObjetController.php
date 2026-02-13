<?php
require_once __DIR__ . '/../models/Objet.php';
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../validators/FormValidator.php';
require_once __DIR__ . '/../helpers/RequestHelper.php';

class ObjetController {
    private $objetModel;
    private $categorieModel;
    
    public function __construct() {
        $this->objetModel = new Objet();
        $this->categorieModel = new Categorie();
    }

    private function jsonUnauthorized() {
        Flight::json([
            'success' => false,
            'message' => 'Vous devez être connecté.'
        ], 401);
    }

    public function mesObjets() {
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return;
        }
        
        $objets = $this->objetModel->getByUtilisateur($_SESSION['user_id']);
        $categories = $this->categorieModel->getAll();
        
        Flight::render('user/mes_objets', ['objets' => $objets, 'categories' => $categories], 'content');
        Flight::render('layouts/main', ['title' => 'Mes objets']);
    }
    
    public function listeObjets() {
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return;
        }
        
        $objets = $this->objetModel->getAutresObjets($_SESSION['user_id']);
        $categories = $this->categorieModel->getAll();
        
        Flight::render('user/objets', ['objets' => $objets, 'categories' => $categories], 'content');
        Flight::render('layouts/main', ['title' => 'Objets disponibles']);
    }
    
    public function recherche() {
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return;
        }
        
        $keyword = $_GET['keyword'] ?? '';
        $categorieId = $_GET['categorie_id'] ?? null;
        
        $objets = $this->objetModel->search($keyword, $categorieId);
        $categories = $this->categorieModel->getAll();
        
        Flight::render('user/objets', [
            'objets' => $objets, 
            'categories' => $categories, 
            'keyword' => $keyword, 
            'selected_categorie' => $categorieId
        ], 'content');
        Flight::render('layouts/main', ['title' => 'Résultats de recherche']);
    }
    
    public function ficheObjet($id) {
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return;
        }
        
        $objet = $this->objetModel->getById($id);
        $photos = $this->objetModel->getPhotos($id);
        $historique = $this->objetModel->getHistorique($id);
        $mesObjets = $this->objetModel->getByUtilisateur($_SESSION['user_id']);
        
        Flight::render('user/fiche_objet', [
            'objet' => $objet, 
            'photos' => $photos, 
            'historique' => $historique, 
            'mesObjets' => $mesObjets
        ], 'content');
        Flight::render('layouts/main', ['title' => $objet['titre']]);
    }
    
    public function ajouter() {
        if (!isset($_SESSION['user_id'])) {
            if (RequestHelper::isAjaxRequest()) {
                $this->jsonUnauthorized();
                return;
            }
            Flight::redirect('/login');
            return;
        }
        
        $data = [
            'utilisateur_id' => $_SESSION['user_id'],
            'categorie_id' => $_POST['categorie_id'] ?? null,
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'prix_estimatif' => $_POST['prix_estimatif'] ?? null
        ];

        $errors = FormValidator::validateObjetData($data);
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
            Flight::redirect('/mes-objets');
            return;
        }
        
        $objetId = $this->objetModel->create($data);
        
        // Gérer l'upload des photos
        if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            foreach ($_FILES['photos']['name'] as $key => $filename) {
                if ($_FILES['photos']['error'][$key] == 0) {
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    $newFilename = uniqid() . '.' . $extension;
                    $destination = $uploadDir . $newFilename;
                    
                    if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $destination)) {
                        $isPrincipale = ($key == 0);
                        $this->objetModel->addPhoto($objetId, $newFilename, '/uploads/' . $newFilename, $isPrincipale);
                    }
                }
            }
        }
        
        if (RequestHelper::isAjaxRequest()) {
            Flight::json([
                'success' => true,
                'message' => "Objet ajouté avec succès !",
                'redirect' => '/mes-objets'
            ]);
            return;
        }

        $_SESSION['success'] = "Objet ajouté avec succès !";
        Flight::redirect('/mes-objets');
    }
    
    public function modifier($id) {
        if (!isset($_SESSION['user_id'])) {
            if (RequestHelper::isAjaxRequest()) {
                $this->jsonUnauthorized();
                return;
            }
            Flight::redirect('/login');
            return;
        }
        
        $objet = $this->objetModel->getById($id);
        
        if (!$objet || $objet['utilisateur_id'] != $_SESSION['user_id']) {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => false,
                    'message' => 'Action non autorisée.'
                ], 403);
                return;
            }
            Flight::redirect('/mes-objets');
            return;
        }
        
        $data = [
            'categorie_id' => $_POST['categorie_id'] ?? null,
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'prix_estimatif' => $_POST['prix_estimatif'] ?? null
        ];

        $errors = FormValidator::validateObjetData($data);
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
            Flight::redirect('/mes-objets');
            return;
        }
        
        if ($this->objetModel->update($id, $data)) {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => true,
                    'message' => "Objet modifié avec succès !",
                    'redirect' => '/mes-objets'
                ]);
                return;
            }
            $_SESSION['success'] = "Objet modifié avec succès !";
        } else {
            if (RequestHelper::isAjaxRequest()) {
                Flight::json([
                    'success' => false,
                    'message' => "Erreur lors de la modification."
                ], 500);
                return;
            }
            $_SESSION['error'] = "Erreur lors de la modification.";
        }
        
        Flight::redirect('/mes-objets');
    }
    
    public function supprimer($id) {
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return;
        }
        
        $objet = $this->objetModel->getById($id);
        
        if ($objet['utilisateur_id'] != $_SESSION['user_id']) {
            Flight::redirect('/mes-objets');
            return;
        }
        
        if ($this->objetModel->delete($id)) {
            $_SESSION['success'] = "Objet supprimé avec succès !";
        } else {
            $deleteError = $this->objetModel->getLastDeleteError();
            if ($deleteError === 'has_exchange') {
                $_SESSION['error'] = "Impossible de supprimer un objet déjà impliqué dans un échange.";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression.";
            }
        }
        
        Flight::redirect('/mes-objets');
    }
}
