<?php

/**
 * @brief Accès aux vidéos dans la base de données.
 */
class VideoDAO{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null){
        $this->setPDO($pdo);
    }

    public function getPDO(): ?PDO
    {
        return $this->pdo;
    }

    public function setPDO(?PDO $pdo): void{
        $this->pdo = $pdo;
    }

    /**
     * @return Video|null Vidéo aléatoire, ou null s'il n'en existe aucune.
     */
    public function getVideoAleatoire(): ?Video
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Video ORDER BY RAND() LIMIT 1";     
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $donnees = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if ($donnees === false) {
            return null;
        }

        return new Video(
            (int) $donnees["id"],
            $donnees["titre"],
            $donnees["description"],
            $donnees["chemin_video"],
            (int) $donnees["taille_fichier"],
            (int) $donnees["id_auteur"]
        );
    }
}

?>