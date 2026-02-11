<?php

class FormValidator {
    public static function validateInscriptionData($data) {
        $errors = [];

        if (empty(trim($data['nom'] ?? ''))) {
            $errors['nom'] = 'Le nom est obligatoire.';
        }

        if (empty(trim($data['prenom'] ?? ''))) {
            $errors['prenom'] = 'Le prénom est obligatoire.';
        }

        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'L\'email est obligatoire.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format d\'email invalide.';
        }

        if (empty($data['password'] ?? '')) {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        } elseif (
            !preg_match('/[A-Z]/', $data['password']) ||
            !preg_match('/[a-z]/', $data['password']) ||
            !preg_match('/[0-9]/', $data['password']) ||
            !preg_match('/[^a-zA-Z0-9]/', $data['password'])
        ) {
            $errors['password'] = 'Le mot de passe doit contenir une majuscule, une minuscule, un chiffre et un caractère spécial.';
        }

        if (!empty(trim($data['telephone'] ?? '')) && !preg_match('/^[0-9+\s().-]{6,20}$/', $data['telephone'])) {
            $errors['telephone'] = 'Format de téléphone invalide.';
        }

        return $errors;
    }

    public static function validateObjetData($data) {
        $errors = [];

        if (empty($data['categorie_id']) || !is_numeric($data['categorie_id'])) {
            $errors['categorie_id'] = 'La catégorie est obligatoire.';
        }

        if (empty(trim($data['titre'] ?? ''))) {
            $errors['titre'] = 'Le titre est obligatoire.';
        }

        if (!isset($data['prix_estimatif']) || $data['prix_estimatif'] === '') {
            $errors['prix_estimatif'] = 'Le prix estimatif est obligatoire.';
        } elseif (!is_numeric($data['prix_estimatif']) || (float) $data['prix_estimatif'] < 0) {
            $errors['prix_estimatif'] = 'Le prix estimatif doit être un nombre positif.';
        }

        return $errors;
    }

    public static function validatePropositionData($data, $objetDemande, $objetPropose, $userId) {
        $errors = [];
        $objetDemandeId = $data['objet_demande_id'] ?? null;
        $objetProposeId = $data['objet_propose_id'] ?? null;

        if (empty($objetDemandeId) || !is_numeric($objetDemandeId)) {
            $errors['objet_demande_id'] = 'Objet demandé invalide.';
        }

        if (empty($objetProposeId) || !is_numeric($objetProposeId)) {
            $errors['objet_propose_id'] = 'Vous devez choisir un objet à proposer.';
        }

        if (empty($errors) && !$objetDemande) {
            $errors['objet_demande_id'] = 'Objet demandé introuvable.';
        }

        if (empty($errors) && !$objetPropose) {
            $errors['objet_propose_id'] = 'Objet proposé introuvable.';
        }

        if (empty($errors) && $objetDemande['proprietaire_id'] == $userId) {
            $errors['objet_demande_id'] = 'Vous ne pouvez pas proposer un échange sur votre propre objet.';
        }

        if (empty($errors) && $objetDemande['statut'] !== 'disponible') {
            $errors['objet_demande_id'] = 'Cet objet n\'est plus disponible.';
        }

        if (empty($errors) && $objetPropose['utilisateur_id'] != $userId) {
            $errors['objet_propose_id'] = 'L\'objet proposé doit vous appartenir.';
        }

        if (empty($errors) && $objetPropose['statut'] !== 'disponible') {
            $errors['objet_propose_id'] = 'Votre objet proposé n\'est pas disponible.';
        }

        if (empty($errors) && (int) $objetProposeId === (int) $objetDemandeId) {
            $errors['objet_propose_id'] = 'Vous ne pouvez pas proposer le même objet.';
        }

        return $errors;
    }
}
