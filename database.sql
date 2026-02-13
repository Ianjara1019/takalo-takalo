-- Base de données Takalo-takalo
-- Système d'échange d'objets

CREATE DATABASE IF NOT EXISTS takalo_takalo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE takalo_takalo;

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des objets
CREATE TABLE IF NOT EXISTS objets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    categorie_id INT NOT NULL,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    prix_estimatif DECIMAL(10,2),
    statut ENUM('disponible', 'en_echange', 'echange') DEFAULT 'disponible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categories(id)
);

-- Table des photos d'objets
CREATE TABLE IF NOT EXISTS photos_objets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_id INT NOT NULL,
    nom_fichier VARCHAR(255) NOT NULL,
    chemin VARCHAR(500) NOT NULL,
    is_principale BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (objet_id) REFERENCES objets(id) ON DELETE CASCADE
);

-- Table des propositions d'échange
CREATE TABLE IF NOT EXISTS propositions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_propose_id INT NOT NULL,
    objet_demande_id INT NOT NULL,
    utilisateur_propose_id INT NOT NULL,
    utilisateur_demande_id INT NOT NULL,
    statut ENUM('en_attente', 'accepte', 'refuse') DEFAULT 'en_attente',
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (objet_propose_id) REFERENCES objets(id),
    FOREIGN KEY (objet_demande_id) REFERENCES objets(id),
    FOREIGN KEY (utilisateur_propose_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (utilisateur_demande_id) REFERENCES utilisateurs(id)
);

-- Table des échanges effectués
CREATE TABLE IF NOT EXISTS echanges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proposition_id INT NOT NULL,
    objet1_id INT NOT NULL,
    objet2_id INT NOT NULL,
    utilisateur1_id INT NOT NULL,
    utilisateur2_id INT NOT NULL,
    date_echange TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposition_id) REFERENCES propositions(id),
    FOREIGN KEY (objet1_id) REFERENCES objets(id),
    FOREIGN KEY (objet2_id) REFERENCES objets(id),
    FOREIGN KEY (utilisateur1_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (utilisateur2_id) REFERENCES utilisateurs(id)
);

-- Table de l'historique d'appartenance
CREATE TABLE IF NOT EXISTS historique_appartenance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    date_debut TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_fin TIMESTAMP NULL,
    echange_id INT NULL,
    FOREIGN KEY (objet_id) REFERENCES objets(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (echange_id) REFERENCES echanges(id)
);

-- Insertion de données de test

-- Admin par défaut (password: admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$12$JvEqtgWmCqILV4340nC1j.OY9x5tMfBUFkQuKELEO77juFIaVZBUy');

-- Autre admin (password: admin)
INSERT INTO admins (username, password) VALUES 
('admin2', '$2y$12$hNnWKcKp9jl6HkZwFbYq3.wtbj1nzDInmAwGDtkWfyCiIFfsEfTNK');

-- Catégories
INSERT INTO categories (nom, description) VALUES
('Vêtements', 'Vêtements pour hommes, femmes et enfants'),
('Livres', 'Romans, BD, magazines, manuels'),
('DVD/Blu-ray', 'Films et séries'),
('Électronique', 'Appareils électroniques et accessoires'),
('Jeux vidéo', 'Jeux et consoles'),
('Sport', 'Équipements et accessoires de sport'),
('Décoration', 'Objets de décoration pour la maison'),
('Jouets', 'Jouets pour enfants');

-- Utilisateurs de test (password: password123)
INSERT INTO utilisateurs (nom, prenom, email, password, telephone, adresse) VALUES
('Weedman', 'Yuchang', 'weedman@gmail.com', '$2y$10$JoxAvksn89dM9jBMJLGoJeBdbTYYmMic3SF5uKPM.5dwp5FJUbqJm', '0341234567', 'Antananarivo'),
('Rakoto', 'Jean', 'jean.rakoto@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0341234567', 'Antananarivo'),
('Rabe', 'Marie', 'marie.rabe@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0349876543', 'Antsirabe'),
('Randria', 'Paul', 'paul.randria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0347654321', 'Fianarantsoa'),
('Rasoa', 'Sophie', 'sophie.rasoa@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0345556666', 'Toamasina');

-- Objets de test
INSERT INTO objets (utilisateur_id, categorie_id, titre, description, prix_estimatif, statut) VALUES
(1, 2, 'Harry Potter - Tome 1', 'Livre en bon état, quelques pages cornées', 5000, 'disponible'),
(1, 1, 'T-shirt Nike taille M', 'T-shirt de sport, porté 2 fois, comme neuf', 15000, 'disponible'),
(2, 3, 'Avatar - DVD', 'Film Avatar en DVD, parfait état', 3000, 'disponible'),
(2, 4, 'Casque audio Bluetooth', 'Casque sans fil, bonne qualité sonore', 25000, 'disponible'),
(3, 5, 'FIFA 2023 - PS4', 'Jeu de football pour PlayStation 4', 30000, 'disponible'),
(3, 6, 'Ballon de basketball', 'Ballon en bon état, utilisé quelques fois', 8000, 'disponible'),
(4, 7, 'Cadre photo vintage', 'Beau cadre doré, style ancien', 7000, 'disponible'),
(4, 8, 'Puzzle 1000 pièces', 'Puzzle complet, jamais ouvert', 12000, 'disponible'),
(1, 2, 'Le Petit Prince', 'Édition illustrée, état neuf', 6000, 'disponible'),
(2, 1, 'Jean Levi\'s 501', 'Jean classique taille 32, peu porté', 35000, 'disponible');

-- Historique initial (chaque objet appartient à son créateur)
INSERT INTO historique_appartenance (objet_id, utilisateur_id, date_debut) VALUES
(1, 1, NOW()),
(2, 1, NOW()),
(3, 2, NOW()),
(4, 2, NOW()),
(5, 3, NOW()),
(6, 3, NOW()),
(7, 4, NOW()),
(8, 4, NOW()),
(9, 1, NOW()),
(10, 2, NOW());

-- Quelques propositions de test
INSERT INTO propositions (objet_propose_id, objet_demande_id, utilisateur_propose_id, utilisateur_demande_id, statut, message) VALUES
(3, 1, 2, 1, 'en_attente', 'Je suis intéressé par votre livre Harry Potter'),
(5, 4, 3, 2, 'en_attente', 'Voulez-vous échanger mon jeu FIFA contre votre casque ?'),
(7, 6, 4, 3, 'refuse', 'Proposition d\'échange cadre contre ballon');

-- Exemple d'échange effectué
INSERT INTO echanges (proposition_id, objet1_id, objet2_id, utilisateur1_id, utilisateur2_id) VALUES
(3, 7, 6, 4, 3);

-- Mise à jour de l'historique après échange
UPDATE historique_appartenance SET date_fin = NOW() WHERE objet_id = 7 AND utilisateur_id = 4;
UPDATE historique_appartenance SET date_fin = NOW() WHERE objet_id = 6 AND utilisateur_id = 3;
INSERT INTO historique_appartenance (objet_id, utilisateur_id, echange_id, date_debut) VALUES
(7, 3, 1, NOW()),
(6, 4, 1, NOW());

UPDATE objets SET statut = 'echange', utilisateur_id = 3 WHERE id = 7;
UPDATE objets SET statut = 'echange', utilisateur_id = 4 WHERE id = 6;
UPDATE propositions SET statut = 'accepte' WHERE id = 3;
