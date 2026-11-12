<?php
/** @brief Accès aux comptes pour la seule création de compte. */
class CompteDao
{
    private ?PDO $pdo;

    /** @brief Initialise le DAO. @param PDO|null $pdo Connexion du contrôleur. */
    public function __construct(?PDO $pdo = null)
    {
        $this->setPdo($pdo);
    }

    /** @brief Retourne la connexion. @return PDO|null Connexion. */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /** @brief Affecte la connexion. @param PDO|null $pdo Connexion. @return void */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Recherche les collisions avec les trois contraintes d'unicité.
     * @param Compte $compte Données du compte à créer.
     * @return array Champs déjà utilisés : pseudo, adresse_mail ou telephone.
     */
    public function findDoublons(Compte $compte): array
    {
        // Les comparaisons SQL respectent la collation de la table, notamment pour les accents.
        // Une recherche par champ permet d'identifier exactement la contrainte concernée.
        $doublons = array();
        foreach (array('pseudo' => $compte->getPseudo(), 'adresse_mail' => $compte->getAdresseMail(), 'telephone' => $compte->getTelephone()) as $champ => $valeur) {
            if ($valeur === null) {
                continue;
            }
            $sql = 'SELECT id FROM ' . PREFIXE_TABLE . 'Compte WHERE ' . $champ . ' = :valeur LIMIT 1';
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(array(':valeur' => $valeur));
            if ($pdoStatement->fetchColumn() !== false) {
                $doublons[] = $champ;
            }
        }
        return $doublons;
    }

    /**
     * @brief Insère un compte ; la base attribue id, statut et est_admin.
     * @param Compte $compte Compte dont le mot de passe est déjà hashé.
     * @return void
     * @throws LengthException Si la colonne fournie ne peut pas contenir le hash.
     * @throws PDOException Si l'enregistrement échoue.
     */
    public function enregistrerCompte(Compte $compte): void
    {
        // L'élargissement de Compte.mdp à VARCHAR(255) sera effectué par l'utilisateur.
        if (strlen($compte->getMdp() ?? '') > 255) {
            throw new LengthException('Le hash du mot de passe dépasse la capacité de stockage prévue.');
        }

        try {
            $this->pdo->beginTransaction();
            $sql = 'INSERT INTO ' . PREFIXE_TABLE . 'Compte
                    (pseudo, prenom, nom, adresse_mail, telephone, mdp, date_naissance, adresse)
                    VALUES (:pseudo, :prenom, :nom, :adresse_mail, :telephone, :mdp, :date_naissance, :adresse)';
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->execute(array(
                ':pseudo' => $compte->getPseudo(),
                ':prenom' => $compte->getPrenom(),
                ':nom' => $compte->getNom(),
                ':adresse_mail' => $compte->getAdresseMail(),
                ':telephone' => $compte->getTelephone(),
                ':mdp' => $compte->getMdp(),
                ':date_naissance' => $compte->getDateNaissance(),
                ':adresse' => $compte->getAdresse()
            ));
            $idCompte = (int) $this->pdo->lastInsertId();
            $this->pdo->commit();
            $compte->setId($idCompte);
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
