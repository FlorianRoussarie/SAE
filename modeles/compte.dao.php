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
     * @brief Recherche le compte associé à une adresse e-mail (connexion, US02).
     * @details Table simple sans jointure : récupération directe en objet Compte
     * avec FETCH_CLASS | FETCH_PROPS_LATE, comme CategorieDao::find() dans le cours.
     * @param string $adresseMail Adresse e-mail saisie dans le formulaire de connexion.
     * @return Compte|null Le compte trouvé (avec le hash du mot de passe), ou null si l'adresse est inconnue.
     * @throws PDOException Si la requête échoue.
     */
    public function findByAdresseMail(string $adresseMail): ?Compte
    {
        $sql = 'SELECT id, pseudo, prenom, nom, adresse_mail, telephone, mdp, date_naissance, adresse, statut, est_admin
                FROM ' . PREFIXE_TABLE . 'Compte
                WHERE adresse_mail = :adresse_mail';
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':adresse_mail' => $adresseMail));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Compte');
        $compte = $pdoStatement->fetch();
        if ($compte === false) {
            return null;
        }
        return $compte;
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

    public function bannir($id){
        $sqlcheck="SELECT est_admin,statut FROM". PREFIXE_TABLE . " Compte 
        WHERE id= ?";
        $stmtcheck =$this->pdo->prepare($sqlcheck);
        $stmtcheck->execute([$id]);
        $compte=$stmtcheck->fetch(PDO::FETCH_ASSOC);
        if($compte===false){
            throw new Exception("aucun utilisateur ne possede cet id");
        }
        if((int)$compte['est_admin']===1)
            {throw new Exception("Impossible de bannir un autre administrateur");}
        if($compte['statut']==='banni')
            {throw new Exception("Ce compte est déja banni");}

        $sql="UPDATE". PREFIXE_TABLE . " Compte 
        SET statut ='banni'
        WHERE id= ?";
        $stmt =$this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return true;
    }
  
    public function debannir($id){
        $sqlcheck="SELECT est_admin,statut FROM". PREFIXE_TABLE . " Compte 
        WHERE id= ?";
        $stmtcheck =$this->pdo->prepare($sqlcheck);
        $stmtcheck->execute([$id]);
         $compte=$stmtcheck->fetch(PDO::FETCH_ASSOC);
        if($compte===false){
            throw new Exception("aucun utilisateur ne possede cet id");
        }
        if($compte['statut']==='actif')
            {throw new Exception("Ce compte est actif(déja débanni)");
            }
        $sql="UPDATE". PREFIXE_TABLE . " Compte 
        SET statut ='actif'
        WHERE id= ?";
        $stmt =$this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return true;
    }
}
