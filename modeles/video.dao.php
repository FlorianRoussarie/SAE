
<?php

class VideoDAO{
    private $bd;

    public function __construct($bd){
        $this->bd = $bd;
    }

    public function getVideoAleatoire()
    {
        $sql = "SELECT * FROM " . PREFIXE_TABLE . "Video ORDER BY RAND() LIMIT 1";

        $req = $this->bd->prepare($sql);
        $req->execute();

        return $req->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter une vidéo dans la base de données
    public function ajouterVideo($video)
    {
        $sql = "INSERT INTO " . PREFIXE_TABLE . "Video
                (titre, description, chemin_fichier, taille_fichier, id_auteur)
                VALUES (:titre, :description, :chemin_fichier, :taille_fichier, :id_auteur)";

        $req = $this->bd->prepare($sql);

        $req->execute([
            ':titre' => $video->getTitre(),
            ':description' => $video->getDescription(),
            ':chemin_fichier' => $video->getCheminVideo(),
            ':taille_fichier' => $video->getTailleFichier(),
            ':id_auteur' => $video->getIdAuteur()
        ]);

        return $this->bd->lastInsertId();
    }
}

?>
