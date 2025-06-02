CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('visiteur', 'admin', 'comptable') DEFAULT 'visiteur'
);

CREATE TABLE frais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    date DATE NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    description TEXT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

CREATE TABLE rapport_visite (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    date_visite DATE NOT NULL,
    commentaire TEXT,
    statut ENUM('prévu', 'réalisé') DEFAULT 'prévu',
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

CREATE TABLE fiche_frais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    mois VARCHAR(6) NOT NULL, 
    etat ENUM('en_cours', 'validée', 'remboursée') DEFAULT 'en_cours',
    montant_total DECIMAL(10, 2) DEFAULT 0,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

CREATE TABLE ligne_frais_forfait (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fiche_frais_id INT NOT NULL,
    type_frais ENUM('kilometrage', 'repas', 'nuitée') NOT NULL,
    quantite INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (fiche_frais_id) REFERENCES fiche_frais(id) ON DELETE CASCADE
);

CREATE TABLE ligne_frais_hors_forfait (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fiche_frais_id INT NOT NULL,
    date DATE NOT NULL,
    libelle VARCHAR(255) NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (fiche_frais_id) REFERENCES fiche_frais(id) ON DELETE CASCADE
);

CREATE TABLE fiches_frais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    description TEXT
);

CREATE TABLE type_de_frais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    montant DECIMAL(10,2) NOT NULL,
    remboursement tinyint(1),
    FOREIGN KEY (fiche_frais) REFERENCES type_de_frais(id) ON DELETE CASCADE
)

INSERT INTO types_de_frais (nom, description, montant, remboursement)
VALUES
('Déplacement', 'frais de déplacements', 50.00, TRUE),
('Repas', 'Frais des repas', 20.00, TRUE),
('Hébergement', 'Frais pour l\'hébergement', 100.00, TRUE),