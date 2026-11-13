<?php

/**
 * @brief Accès aux vidéos dans la base de données.
 */
class VideoDAO
{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->setPDO($pdo);
    }

    public function getPDO(): ?PDO
    {
        return $this->pdo;
    }

    public function setPDO(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Récupère une vidéo au hasard.
     * @param int|null $idExclu Identifiant d'une vidéo à ne pas renvoyer
     * (la vidéo affichée, pour le défilement de l'US06), ou null.
     * @return Video|null Vidéo aléatoire, ou null s'il n'en existe aucune.
     */
    public function getVideoAleatoire(?int $idExclu = null): ?Video
    {
        if ($idExclu === null) {
            $sql = "SELECT * FROM " . PREFIXE_TABLE . "Video ORDER BY RAND() LIMIT 1";
            $parametres = array();
        } else {
            // Défilement (US06) : on exclut la vidéo affichée pour en montrer une autre
            $sql = "SELECT * FROM " . PREFIXE_TABLE . "Video
                    WHERE id <> :id ORDER BY RAND() LIMIT 1";
            $parametres = array(':id' => $idExclu);
        }

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute($parametres);
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
     * @brief Récupère une vidéo à partir de son identifiant (US06).
     * @param int $id Identifiant de la vidéo.
     * @return Video|null Vidéo trouvée, ou null si aucune vidéo n'a cet identifiant.
     */
    public function find(int $id): ?Video
    {
        $sql = "SELECT id, titre, description, chemin_video,
                       taille_fichier, id_auteur
                FROM " . PREFIXE_TABLE . "Video
                WHERE id = :id";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));

        $pdoStatement->setFetchMode(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            'Video'
        );

        $video = $pdoStatement->fetch();

        if ($video === false) {
            return null;
        }

        return $video;
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
                VALUES (:titre, :description, :chemin_video,
                        :taille_fichier, :id_auteur)';

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