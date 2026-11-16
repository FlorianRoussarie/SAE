<?php
class SignalementDao {
    private ?PDO $pdo;

    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): ?PDO {return $this->pdo;}
    public function setPdo(?PDO $pdo): void {$this->pdo = $pdo;}

    public function find(?int $id): array {

        $sql = "SELECT S.id, S.raison, S.date, S.statut,
                       S.id_video, S.id_auteur, S.id_cible,
                       auteur.pseudo AS pseudo_auteur,
                       cible.pseudo AS pseudo_cible,
                       V.titre AS titre_video
                FROM " . PREFIXE_TABLE . "Signalement S
                LEFT JOIN " . PREFIXE_TABLE . "Compte auteur ON S.id_auteur = auteur.id
                LEFT JOIN " . PREFIXE_TABLE . "Compte cible ON S.id_cible = cible.id
                LEFT JOIN " . PREFIXE_TABLE . "Video V ON S.id_video = V.id
                WHERE S.id = :id
                ORDER BY S.date DESC, S.id DESC ";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id" => $id));
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        /*$pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array("id" => $id));
        $pdo_statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Signalement');
        return $pdo_statement->fetch();*/
    }
   
    public function findAll(): array
    {
        $sql = "SELECT S.id, S.raison, S.date, S.statut,
                       S.id_video, S.id_auteur, S.id_cible,
                       auteur.pseudo AS pseudo_auteur,
                       cible.pseudo AS pseudo_cible,
                       V.titre AS titre_video
                FROM " . PREFIXE_TABLE . "Signalement S
                LEFT JOIN " . PREFIXE_TABLE . "Compte auteur ON S.id_auteur = auteur.id
                LEFT JOIN " . PREFIXE_TABLE . "Compte cible ON S.id_cible = cible.id
                LEFT JOIN " . PREFIXE_TABLE . "Video V ON S.id_video = V.id
                ORDER BY S.date DESC, S.id DESC ";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function hydrate($tab_assoc): ?Signalement {

        $signalement = new Signalement();
        $signalement->setId($tab_assoc['id']);    
        $signalement->setRaison($tab_assoc['raison']);    
        $signalement->setDate($tab_assoc['date']);    
        $signalement->setStatut($tab_assoc['statut']);    
        $signalement->setIdAuteur($tab_assoc['id_auteur']);    
        $signalement->setIdCible($tab_assoc['id_cible']);    
        $signalement->setIdVideo($tab_assoc['id_video']);  
      
        return $signalement;
    }
    public function hydrateAll($tab): ?array {

        $signalements = [];
        foreach($tab as $tab_assoc) {
            $signalement = $this->hydrate($tab_assoc);
            $signalements[] = $signalement;
        }
        return $signalements;
    }
}