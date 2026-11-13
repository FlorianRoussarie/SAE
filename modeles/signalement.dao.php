<?php
class SignalementDao {
    private ?PDO $pdo;

    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): ?PDO {return $this->pdo;}
    public function setPdo(?PDO $pdo): void {$this->pdo = $pdo;}

    public function find(?int $id): ?Signalement {

        $sql = "SELECT * FROM ". PREFIXE_TABLE."Signalement WHERE id = :id";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array("id" => $id));
        $pdo_statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Signalement');
        $signalement = $pdo_statement->fetch();
        return $signalement === false ? null : $signalement;
    }
    public function findDetail(?int $id): ?Signalement {

        $sql = "SELECT * FROM ". PREFIXE_TABLE."Signalement INNER JOIN ". PREFIXE_TABLE."Signalement ON Video.id = Signalement.id_video INNER JOIN ". PREFIXE_TABLE."Compte ON Compte.id = Signalement.id_cible WHERE Video.id = :id";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array("id" => $id));
        $pdo_statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Signalement');
        $signalement = $pdo_statement->fetch();
        return $signalement === false ? null : $signalement;
    }
   
    public function findAll() {

        $sql = "SELECT * FROM ". PREFIXE_TABLE."Signalement";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();
        $pdo_statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Signalement');
        $signalement = $pdo_statement->fetchAll();
        return $signalement === false ? null : $signalement;
    }
    public function findAllDetail() {

        $sql = "SELECT * FROM ". PREFIXE_TABLE."Signalement INNER JOIN ". PREFIXE_TABLE."Signalement ON Video.id = Signalement.id_video INNER JOIN ". PREFIXE_TABLE."Compte ON Compte.id = Signalement.id_cible";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();
        $pdo_statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Signalement');
        $signalements = $pdo_statement->fetch();
        return $signalements === false ? null : $signalements;
    }
    public function findAssoc(?int $id): ?array {

        $sql = "SELECT * FROM ". PREFIXE_TABLE."Signalement WHERE id = :id";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array("id" => $id));
        $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
        $signalement = $pdo_statement->fetch();
        return $signalement === false ? null : $signalement;
    }
    public function findAllAssoc() {

        $sql = "SELECT * FROM ". PREFIXE_TABLE."Signalement";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();
        $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
        $signalements = $pdo_statement->fetchAll();
        return $signalements === false ? null : $signalements;
    }
    public function findAssocDetail(?int $id): ?array {

        $sql = "SELECT * FROM ". PREFIXE_TABLE."Signalement INNER JOIN ". PREFIXE_TABLE."Signalement ON Video.id = Signalement.id_video INNER JOIN ". PREFIXE_TABLE."Compte ON Compte.id = Signalement.id_cible WHERE Video.id = :id";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array("id" => $id));
        $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
        $signalement = $pdo_statement->fetch();
        return $signalement === false ? null : $signalement;
    }
    public function findAllAssocDetail() {

        $sql = "SELECT * FROM ". PREFIXE_TABLE."Signalement INNER JOIN ". PREFIXE_TABLE."Signalement ON Video.id = Signalement.id_video INNER JOIN ". PREFIXE_TABLE."Compte ON Compte.id = Signalement.id_cible";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();
        $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
        $signalements = $pdo_statement->fetchAll();
        return $signalements === false ? null : $signalements;
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