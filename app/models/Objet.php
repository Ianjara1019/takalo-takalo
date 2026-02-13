<?php
require_once __DIR__ . '/../../config/database.php';

class Objet {
    private $db;
    private $lastDeleteError;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->lastDeleteError = null;
    }
    
    public function create($data) {
        $sql = "INSERT INTO objets (utilisateur_id, categorie_id, titre, description, prix_estimatif) 
                VALUES (:utilisateur_id, :categorie_id, :titre, :description, :prix_estimatif)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':utilisateur_id' => $data['utilisateur_id'],
            ':categorie_id' => $data['categorie_id'],
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':prix_estimatif' => $data['prix_estimatif']
        ]);
        
        $objetId = $this->db->lastInsertId();
        
        // Créer l'historique initial
        $sqlHistorique = "INSERT INTO historique_appartenance (objet_id, utilisateur_id) VALUES (:objet_id, :utilisateur_id)";
        $stmtHistorique = $this->db->prepare($sqlHistorique);
        $stmtHistorique->execute([
            ':objet_id' => $objetId,
            ':utilisateur_id' => $data['utilisateur_id']
        ]);
        
        return $objetId;
    }
    
    public function getByUtilisateur($utilisateurId) {
        $sql = "SELECT o.*, c.nom as categorie_nom, 
                (SELECT nom_fichier FROM photos_objets WHERE objet_id = o.id AND is_principale = 1 LIMIT 1) as photo_principale
                FROM objets o 
                LEFT JOIN categories c ON o.categorie_id = c.id
                WHERE o.utilisateur_id = :utilisateur_id
                ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        return $stmt->fetchAll();
    }
    
    public function getAutresObjets($utilisateurId) {
        $sql = "SELECT o.*, c.nom as categorie_nom, u.nom, u.prenom,
                (SELECT nom_fichier FROM photos_objets WHERE objet_id = o.id AND is_principale = 1 LIMIT 1) as photo_principale
                FROM objets o 
                LEFT JOIN categories c ON o.categorie_id = c.id
                LEFT JOIN utilisateurs u ON o.utilisateur_id = u.id
                WHERE o.utilisateur_id != :utilisateur_id AND o.statut = 'disponible'
                ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT o.*, c.nom as categorie_nom, u.nom, u.prenom, u.id as proprietaire_id
                FROM objets o 
                LEFT JOIN categories c ON o.categorie_id = c.id
                LEFT JOIN utilisateurs u ON o.utilisateur_id = u.id
                WHERE o.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getPhotos($objetId) {
        $sql = "SELECT * FROM photos_objets WHERE objet_id = :objet_id ORDER BY is_principale DESC, id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':objet_id' => $objetId]);
        return $stmt->fetchAll();
    }
    
    public function addPhoto($objetId, $nomFichier, $chemin, $isPrincipale = false) {
        $sql = "INSERT INTO photos_objets (objet_id, nom_fichier, chemin, is_principale) 
                VALUES (:objet_id, :nom_fichier, :chemin, :is_principale)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':objet_id' => $objetId,
            ':nom_fichier' => $nomFichier,
            ':chemin' => $chemin,
            ':is_principale' => $isPrincipale ? 1 : 0
        ]);
    }
    
    public function search($keyword, $categorieId = null) {
        $sql = "SELECT o.*, c.nom as categorie_nom, u.nom, u.prenom,
                (SELECT nom_fichier FROM photos_objets WHERE objet_id = o.id AND is_principale = 1 LIMIT 1) as photo_principale
                FROM objets o 
                LEFT JOIN categories c ON o.categorie_id = c.id
                LEFT JOIN utilisateurs u ON o.utilisateur_id = u.id
                WHERE o.statut = 'disponible' AND o.titre LIKE :keyword";
        
        if ($categorieId) {
            $sql .= " AND o.categorie_id = :categorie_id";
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $params = [':keyword' => '%' . $keyword . '%'];
        
        if ($categorieId) {
            $params[':categorie_id'] = $categorieId;
        }
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getHistorique($objetId) {
        $sql = "SELECT ha.*, u.nom, u.prenom, e.date_echange
                FROM historique_appartenance ha
                LEFT JOIN utilisateurs u ON ha.utilisateur_id = u.id
                LEFT JOIN echanges e ON ha.echange_id = e.id
                WHERE ha.objet_id = :objet_id
                ORDER BY ha.date_debut ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':objet_id' => $objetId]);
        return $stmt->fetchAll();
    }
    
    public function update($id, $data) {
        $sql = "UPDATE objets SET categorie_id = :categorie_id, titre = :titre, 
                description = :description, prix_estimatif = :prix_estimatif 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':categorie_id' => $data['categorie_id'],
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':prix_estimatif' => $data['prix_estimatif']
        ]);
    }
    
    public function delete($id) {
        $this->lastDeleteError = null;

        try {
            $this->db->beginTransaction();

            // Ne pas supprimer un objet déjà impliqué dans un échange validé.
            $sqlCountEchanges = "SELECT COUNT(*) FROM echanges WHERE objet1_id = :id OR objet2_id = :id";
            $stmtCountEchanges = $this->db->prepare($sqlCountEchanges);
            $stmtCountEchanges->execute([':id' => $id]);

            if ((int) $stmtCountEchanges->fetchColumn() > 0) {
                $this->db->rollBack();
                $this->lastDeleteError = 'has_exchange';
                return false;
            }

            $sqlDeleteHistorique = "DELETE FROM historique_appartenance WHERE objet_id = :id";
            $stmtDeleteHistorique = $this->db->prepare($sqlDeleteHistorique);
            $stmtDeleteHistorique->execute([':id' => $id]);

            $sqlDeletePropositions = "DELETE FROM propositions WHERE objet_propose_id = :id OR objet_demande_id = :id";
            $stmtDeletePropositions = $this->db->prepare($sqlDeletePropositions);
            $stmtDeletePropositions->execute([':id' => $id]);

            $sqlDeleteObjet = "DELETE FROM objets WHERE id = :id";
            $stmtDeleteObjet = $this->db->prepare($sqlDeleteObjet);
            $stmtDeleteObjet->execute([':id' => $id]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $this->lastDeleteError = 'db_error';
            return false;
        }
    }

    public function getLastDeleteError() {
        return $this->lastDeleteError;
    }
    
    public function changerProprietaire($objetId, $nouveauProprietaireId, $echangeId) {
        // Mettre à jour l'objet
        $sql = "UPDATE objets SET utilisateur_id = :utilisateur_id, statut = 'echange' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':utilisateur_id' => $nouveauProprietaireId,
            ':id' => $objetId
        ]);
        
        // Clôturer l'historique actuel
        $sqlCloture = "UPDATE historique_appartenance SET date_fin = NOW() 
                       WHERE objet_id = :objet_id AND date_fin IS NULL";
        $stmtCloture = $this->db->prepare($sqlCloture);
        $stmtCloture->execute([':objet_id' => $objetId]);
        
        // Créer nouveau historique
        $sqlNouveau = "INSERT INTO historique_appartenance (objet_id, utilisateur_id, echange_id) 
                       VALUES (:objet_id, :utilisateur_id, :echange_id)";
        $stmtNouveau = $this->db->prepare($sqlNouveau);
        return $stmtNouveau->execute([
            ':objet_id' => $objetId,
            ':utilisateur_id' => $nouveauProprietaireId,
            ':echange_id' => $echangeId
        ]);
    }
}
