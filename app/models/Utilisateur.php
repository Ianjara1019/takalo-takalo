<?php
require_once __DIR__ . '/../../config/database.php';

class Utilisateur {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function emailExiste($email) {
        $sql = 'SELECT id FROM utilisateurs WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);

        return (bool) $stmt->fetch();
    }
    
    public function inscrire($data) {
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, password, telephone, adresse) 
                VALUES (:nom, :prenom, :email, :password, :telephone, :adresse)";
        
        $stmt = $this->db->prepare($sql);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':password' => $hashedPassword,
            ':telephone' => $data['telephone'] ?? null,
            ':adresse' => $data['adresse'] ?? null
        ]);
    }
    
    public function login($email, $password) {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM utilisateurs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM utilisateurs ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM utilisateurs";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
