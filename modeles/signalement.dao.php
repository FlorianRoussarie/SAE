<?php
class SiignalementDao {
    private ?PDO $pdo;

    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): ?PDO {return $this->pdo;}
    public function setPdo(?PDO $pdo): void {$this->pdo = $pdo;}

    public function find(?int $id): ?Signalement {

        $sql = "SELECT * FROM Signalement WHERE id = :id";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array("id" => $id));
        $pdo_statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Signalement');
        $signalement = $pdo_statement->fetch();
        return $signalement;
    }
    public function findAll() {

        $sql = "SELECT * FROM Signalement";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();
        $pdo_statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Signalement');
        $signalement = $pdo_statement->fetchAll();
        return $signalement;
    }

    public function findAssoc(?int $id): ?array {

        $sql = "SELECT * FROM Signalement WHERE id = :id";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array("id" => $id));
        $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
        $signalement = $pdo_statement->fetch();
        return $signalement;
    }
    public function findAllAssoc() {

        $sql = "SELECT * FROM Signalement";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();
        $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
        $categories = $pdo_statement->fetchAll();
        return $categories;
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