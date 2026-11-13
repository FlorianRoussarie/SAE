CREATE TABLE SIP (
    id_paiement INT PRIMARY KEY AUTO_INCREMENT, 
    id_transaction VARCHAR(50) NOT NULL UNIQUE, 
    montant_total DECIMAL (15, 2) NOT NULL, 
    statut_paiement VARCHAR(50) NOT NULL DEFAULT 'en cours' CHECK (statut_paiement IN ("en cours", "accepte", "refuse")), 
    date_transaction DATETIME NOT NULL,
	id_annonce INT REFERENCES Annonce(id)
	id_acheteur INT NOT NULL REFERENCES Compte(id));
	
INSERT INTO SIP (id_paiement, id_transaction, montant_total, statut_paiement, date_transaction, id_annonce, id_acheteur) VALUES
('1d5sf4sdf5', '1500.90', 'en cours', '2026-11-09 13:46:52', 1, 2);

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
    statut VARCHAR(50) NOT NULL DEFAULT "actif" CHECK (statut IN ("actif", "avertissement 1", "avertissement 2", "banni"))
	est_admin TINYINT(1) NOT NULL DEFAULT 0);
	
INSERT INTO `Compte` (`id`, `pseudo`, `prenom`, `nom`, `adresse_mail`, `telephone`, `mdp`, `date_naissance`, `adresse`, `statut`, `est_admin`) VALUES
(1, 'nino', 'Nino', 'Claudepierre--Lassalle', 'nino@nino.nino', NULL, 'nino', '2007-09-19', '1 Rue du Nino 00000 Ninoville', 'actif', 0),
(2, 'victoria', 'Victoria', 'Bonaert', 'victoria@victoria.victoria', '0606060606', 'victoria', '2007-07-17', NULL, 'actif', 0),
(3, 'guilherme', 'Guilherme', 'Grave Salgueiro', 'guilherme@guilherme.guilherme', NULL, 'guilherme', '2007-02-15', NULL, 'actif', 0),
(4, 'florian', 'Florian', 'Roussarie', 'florian@florian.florian', 0707070707, 'florian', '2007-05-24', NULL, 'actif', 0),
(5, 'tao', 'Tao', 'Cassat', 'tao@tao.tao', NULL, 'tao', '2005-11-18', NULL, 'actif', 0),
(6, 'alexandra', 'Alexandra', 'Tajan', 'alexandra@alexandra.alexandra', 0505050505, 'alexandra', '2006-01-29', NULL, 'actif', 0);


CREATE TABLE Video (
    id INT PRIMARY KEY AUTO_INCREMENT, 
    titre VARCHAR(100) NOT NULL, 
    description VARCHAR(1000), 
    chemin_fichier VARCHAR(250) NOT NULL, 
    taille_fichier BIGINT NOT NULL, 
    id_auteur INT NOT NULL REFERENCES Compte(id));
	
INSERT INTO `Video` (`id`, `titre`, `description`, `chemin_video`, `taille_fichier`, `id_auteur`) VALUES
(1, 'La délicieuse pizza de Nino', 'Une bonne recette pour une pizza au chorizo, n\'oubliez pas de liker de vous abonner et de sonner la cloche', 'videos/test.mp4', 100000000, 1);


CREATE TABLE Signalement (
    id INT PRIMARY KEY AUTO_INCREMENT,
    raison VARCHAR(300) NOT NULL,
    date DATETIME NOT NULL,
    statut VARCHAR(30) NOT NULL DEFAULT 'a traiter' CHECK (statut IN ('a traiter', 'traite', 'ignore')),
    id_auteur INT NOT NULL,
    id_cible INT NOT NULL,
	id_video INT,
    FOREIGN KEY (id_auteur) REFERENCES Compte(id),
    FOREIGN KEY (id_cible) REFERENCES Compte(id),
	FOREIGN KEY (id_video) REFERENCES Video(id)
);

INSERT INTO `Signalement` (`id`, `raison`, `date`, `statut`, `id_auteur`, `id_cible`, `id_video`) VALUES
(2, 'autre: la recette n\' a pas fonctionné', '2026-11-09 13:46:52', 'a traiter', 3, 1, NULL);


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

INSERT INTO `Annonce` (`id`, `titre`, `type`, `etat`, `prix`, `caution`, `description`, `id_vendeur`) VALUES
(1, 'Four à pizza', 'vente', 'bon etat', '1500.90', NULL, 'Un four à pizza pour faire des bonnes pizzas', 1);


CREATE TABLE Messagerie (
    id INT PRIMARY KEY AUTO_INCREMENT, 
    message VARCHAR(1000) NOT NULL, 
    date DATETIME NOT NULL, 
    id_envoyeur INT NOT NULL REFERENCES Compte(id), 
    id_receveur INT NOT NULL REFERENCES Compte(id), 
    id_annonce INT REFERENCES Annonce(id));
	
INSERT INTO `Messagerie` (`id`, `message`, `date`, `id_envoyeur`, `id_destinataire`, `id_annonce`) VALUES
(1, 'Coucou, j\'ai vu tes vidéos sur les pizzas et la recette était super bonne, du coup je suis intéressé par ton four pour les faire encore mieux', '2026-11-09 13:54:32', 2, 1, 1);


CREATE TABLE Photos (
    id_photo INT PRIMARY KEY AUTO_INCREMENT, 
    id_annonce INT REFERENCES Annonce(id), 
    id_compte INT REFERENCES Compte(id), 
    id_video INT REFERENCES Video(id), 
    id_message INT REFERENCES Messagerie(id)
	chemin VARCHAR(200));
	
INSERT INTO `Photos` (`id_photo`, `id_annonce`, `id_compte`, `id_video`, `id_message`, `chemin`) VALUES
(2, NULL, NULL, NULL, 1, 'photos/photo1.jpg');
