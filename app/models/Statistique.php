<?php
require_once __DIR__ . '/../../config/database.php';

class Statistique {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getNombreUtilisateurs() {
        $sql = "SELECT COUNT(*) as total FROM utilisateurs";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function getNombreEchanges() {
        $sql = "SELECT COUNT(*) as total FROM echanges";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function getNombreObjets() {
        $sql = "SELECT COUNT(*) as total FROM objets";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function getNombrePropositions() {
        $sql = "SELECT COUNT(*) as total FROM propositions";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function getStatsParCategorie() {
        $sql = "SELECT c.nom, COUNT(o.id) as nombre_objets
                FROM categories c
                LEFT JOIN objets o ON c.id = o.categorie_id
                GROUP BY c.id, c.nom
                ORDER BY nombre_objets DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
