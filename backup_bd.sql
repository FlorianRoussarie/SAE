CREATE TABLE SIP (
    id_paiement INT PRIMARY KEY AUTO_INCREMENT, 
    id_transaction VARCHAR(50) NOT NULL UNIQUE, 
    montant_total DECIMAL (15, 2) NOT NULL, 
    statut_paiement VARCHAR(50) NOT NULL DEFAULT 'en cours' CHECK (statut_paiement IN ("en cours", "accepte", "refuse")), 
    date_transaction DATETIME NOT NULL,
	id_annonce INT REFERENCES Annonce(id));

CREATE TABLE Compte (
    id INT PRIMARY KEY AUTO_INCREMENT, 
    pseudo VARCHAR(50) NOT NULL UNIQUE, 
    prenom VARCHAR(50) NOT NULL, 
    nom VARCHAR(50) NOT NULL, 
    adresse_mail VARCHAR(50) NOT NULL UNIQUE, 
    telephone VARCHAR(30) UNIQUE, 
    mdp VARCHAR(50) NOT NULL, 
    date_naissance DATE NOT NULL, 
    adresse VARCHAR(250), 
    statut VARCHAR(50) NOT NULL DEFAULT "actif" CHECK (statut IN ("actif", "avertissement 1", "avertissement 2", "banni")));

CREATE TABLE Video (
    id INT PRIMARY KEY AUTO_INCREMENT, 
    titre VARCHAR(100) NOT NULL, 
    description VARCHAR(1000), 
    chemin_fichier VARCHAR(250) NOT NULL, 
    taille_fichier BIGINT NOT NULL, 
    id_auteur INT NOT NULL REFERENCES Compte(id));

CREATE TABLE Signalement (
    id INT PRIMARY KEY AUTO_INCREMENT,
    raison VARCHAR(300) NOT NULL,
    date DATETIME NOT NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'a traiter' CHECK (statut IN ('a traiter', 'traite', 'ignore')),
    id_auteur INT NOT NULL,
    id_cible INT NOT NULL,
    FOREIGN KEY (id_auteur) REFERENCES Compte(id),
    FOREIGN KEY (id_cible) REFERENCES Compte(id)
);

CREATE TABLE Annonce (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(75) NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('vente', 'pret', 'don')),
    etat VARCHAR(50) NOT NULL CHECK (etat IN ('neuf', 'tres bon etat', 'bon etat', 'use')),
    prix DECIMAL(15,2),
    caution DECIMAL(15,2),
    description VARCHAR(1000) NOT NULL,
    id_vendeur INT NOT NULL,
    FOREIGN KEY (id_vendeur) REFERENCES Compte(id)
);

CREATE TABLE Messagerie (
    id INT PRIMARY KEY AUTO_INCREMENT, 
    message VARCHAR(1000) NOT NULL, 
    date DATETIME NOT NULL, 
    id_envoyeur INT NOT NULL REFERENCES Compte(id), 
    id_receveur INT NOT NULL REFERENCES Compte(id), 
    id_annonce INT REFERENCES Annonce(id));

CREATE TABLE Photos (
    id_photo INT PRIMARY KEY AUTO_INCREMENT, 
    id_annonce INT REFERENCES Annonce(id), 
    id_compte INT REFERENCES Compte(id), 
    id_video INT REFERENCES Video(id), 
    id_message INT REFERENCES Messagerie(id));