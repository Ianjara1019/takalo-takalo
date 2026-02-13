<?php
require_once __DIR__ . '/../../config/database.php';

class Proposition {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO propositions (objet_propose_id, objet_demande_id, utilisateur_propose_id, utilisateur_demande_id, message) 
                VALUES (:objet_propose_id, :objet_demande_id, :utilisateur_propose_id, :utilisateur_demande_id, :message)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':objet_propose_id' => $data['objet_propose_id'],
            ':objet_demande_id' => $data['objet_demande_id'],
            ':utilisateur_propose_id' => $data['utilisateur_propose_id'],
            ':utilisateur_demande_id' => $data['utilisateur_demande_id'],
            ':message' => $data['message'] ?? null
        ]);
    }
    
    public function getPropositionsRecues($utilisateurId) {
        $sql = "SELECT p.*, 
                op.titre as objet_propose_titre, op.prix_estimatif as objet_propose_prix,
                od.titre as objet_demande_titre, od.prix_estimatif as objet_demande_prix,
                u.nom, u.prenom,
                (SELECT nom_fichier FROM photos_objets WHERE objet_id = p.objet_propose_id AND is_principale = 1 LIMIT 1) as photo_propose,
                (SELECT nom_fichier FROM photos_objets WHERE objet_id = p.objet_demande_id AND is_principale = 1 LIMIT 1) as photo_demande
                FROM propositions p
                LEFT JOIN objets op ON p.objet_propose_id = op.id
                LEFT JOIN objets od ON p.objet_demande_id = od.id
                LEFT JOIN utilisateurs u ON p.utilisateur_propose_id = u.id
                WHERE p.utilisateur_demande_id = :utilisateur_id
                ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        return $stmt->fetchAll();
    }
    
    public function getPropositionsEnvoyees($utilisateurId) {
        $sql = "SELECT p.*, 
                op.titre as objet_propose_titre, op.prix_estimatif as objet_propose_prix,
                od.titre as objet_demande_titre, od.prix_estimatif as objet_demande_prix,
                u.nom, u.prenom,
                (SELECT nom_fichier FROM photos_objets WHERE objet_id = p.objet_propose_id AND is_principale = 1 LIMIT 1) as photo_propose,
                (SELECT nom_fichier FROM photos_objets WHERE objet_id = p.objet_demande_id AND is_principale = 1 LIMIT 1) as photo_demande
                FROM propositions p
                LEFT JOIN objets op ON p.objet_propose_id = op.id
                LEFT JOIN objets od ON p.objet_demande_id = od.id
                LEFT JOIN utilisateurs u ON p.utilisateur_demande_id = u.id
                WHERE p.utilisateur_propose_id = :utilisateur_id
                ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        return $stmt->fetchAll();
    }
    
    public function accepter($propositionId) {
        // Récupérer les détails de la proposition
        $sqlGet = "SELECT * FROM propositions WHERE id = :id";
        $stmtGet = $this->db->prepare($sqlGet);
        $stmtGet->execute([':id' => $propositionId]);
        $proposition = $stmtGet->fetch();
        
        if (!$proposition) {
            return false;
        }
        
        // Commencer une transaction
        $this->db->beginTransaction();
        
        try {
            // Mettre à jour le statut de la proposition
            $sqlUpdate = "UPDATE propositions SET statut = 'accepte' WHERE id = :id";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->execute([':id' => $propositionId]);
            
            // Créer l'échange
            $sqlEchange = "INSERT INTO echanges (proposition_id, objet1_id, objet2_id, utilisateur1_id, utilisateur2_id) 
                           VALUES (:proposition_id, :objet1_id, :objet2_id, :utilisateur1_id, :utilisateur2_id)";
            $stmtEchange = $this->db->prepare($sqlEchange);
            $stmtEchange->execute([
                ':proposition_id' => $propositionId,
                ':objet1_id' => $proposition['objet_propose_id'],
                ':objet2_id' => $proposition['objet_demande_id'],
                ':utilisateur1_id' => $proposition['utilisateur_propose_id'],
                ':utilisateur2_id' => $proposition['utilisateur_demande_id']
            ]);
            
            $echangeId = $this->db->lastInsertId();
            
            // Changer les propriétaires des objets
            require_once __DIR__ . '/Objet.php';
            $objetModel = new Objet();
            
            $objetModel->changerProprietaire($proposition['objet_propose_id'], $proposition['utilisateur_demande_id'], $echangeId);
            $objetModel->changerProprietaire($proposition['objet_demande_id'], $proposition['utilisateur_propose_id'], $echangeId);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    public function refuser($propositionId) {
        $sql = "UPDATE propositions SET statut = 'refuse' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $propositionId]);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM propositions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
