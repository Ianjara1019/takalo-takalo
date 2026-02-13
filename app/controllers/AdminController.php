<?php
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../models/Statistique.php';

class AdminController {
    private $adminModel;
    private $categorieModel;
    private $statistiqueModel;
    
    public function __construct() {
        $this->adminModel = new Admin();
        $this->categorieModel = new Categorie();
        $this->statistiqueModel = new Statistique();
    }
    
    public function showLogin() {
        if (isset($_SESSION['admin_id'])) {
            Flight::redirect('/admin');
            return;
        }
        Flight::render('admin/login', [], 'content');
        Flight::render('layouts/admin', ['title' => 'Admin - Connexion']);
    }
    
    public function login() {
        $result = $this->adminModel->login($_POST['username'], $_POST['password']);
        
        if ($result) {
            $_SESSION['admin_id'] = $result['id'];
            $_SESSION['admin_username'] = $result['username'];
            Flight::redirect('/admin');
        } else {
            $_SESSION['error'] = "Identifiants incorrects.";
            Flight::redirect('/admin/login');
        }
    }
    
    public function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        Flight::redirect('/admin/login');
    }
    
    public function dashboard() {
        if (!isset($_SESSION['admin_id'])) {
            Flight::redirect('/admin/login');
            return;
        }
        
        $stats = [
            'utilisateurs' => $this->statistiqueModel->getNombreUtilisateurs(),
            'echanges' => $this->statistiqueModel->getNombreEchanges(),
            'objets' => $this->statistiqueModel->getNombreObjets(),
            'propositions' => $this->statistiqueModel->getNombrePropositions(),
            'categories' => $this->statistiqueModel->getStatsParCategorie()
        ];
        
        Flight::render('admin/dashboard', ['stats' => $stats], 'content');
        Flight::render('layouts/admin', ['title' => 'Admin - Tableau de bord']);
    }
    
    public function categories() {
        if (!isset($_SESSION['admin_id'])) {
            Flight::redirect('/admin/login');
            return;
        }
        
        $categories = $this->categorieModel->getAll();
        
        Flight::render('admin/categories', ['categories' => $categories], 'content');
        Flight::render('layouts/admin', ['title' => 'Admin - Catégories']);
    }
    
    public function ajouterCategorie() {
        if (!isset($_SESSION['admin_id'])) {
            Flight::redirect('/admin/login');
            return;
        }
        
        if ($this->categorieModel->create($_POST['nom'], $_POST['description'])) {
            $_SESSION['success'] = "Catégorie ajoutée avec succès !";
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout.";
        }
        
        Flight::redirect('/admin/categories');
    }
    
    public function modifierCategorie($id) {
        if (!isset($_SESSION['admin_id'])) {
            Flight::redirect('/admin/login');
            return;
        }
        
        if ($this->categorieModel->update($id, $_POST['nom'], $_POST['description'])) {
            $_SESSION['success'] = "Catégorie modifiée avec succès !";
        } else {
            $_SESSION['error'] = "Erreur lors de la modification.";
        }
        
        Flight::redirect('/admin/categories');
    }
    
    public function supprimerCategorie($id) {
        if (!isset($_SESSION['admin_id'])) {
            Flight::redirect('/admin/login');
            return;
        }
        
        if ($this->categorieModel->delete($id)) {
            $_SESSION['success'] = "Catégorie supprimée avec succès !";
        } else {
            $_SESSION['error'] = "Impossible de supprimer cette catégorie (objets associés).";
        }
        
        Flight::redirect('/admin/categories');
    }
}
