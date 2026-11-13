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

    /**
     * @brief Enregistre les métadonnées d'une nouvelle vidéo.
     * @param Video $video Vidéo à publier, associée à l'auteur connecté.
     * @return void
     * @throws PDOException Si l'insertion échoue.
     */
    public function enregistrerVideo(Video $video): void
    {
        $sql = 'INSERT INTO ' . PREFIXE_TABLE . 'Video
                (titre, description, chemin_video, taille_fichier, id_auteur)
                VALUES (:titre, :description, :chemin_video, :taille_fichier, :id_auteur)';
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(
            ':titre' => $video->getTitre(),
            ':description' => $video->getDescription(),
            ':chemin_video' => $video->getCheminVideo(),
            ':taille_fichier' => $video->getTailleFichier(),
            ':id_auteur' => $video->getIdAuteur()
        ));
    }
}

?>
