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

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        if (!$donnees) {
            return null;
        }

        return new Video(
            $donnees["id"],
            $donnees["titre"],
            $donnees["description"],
            $donnees["chemin_video"],
            $donnees["taille_fichier"],
            $donnees["id_auteur"]
        );
    }
}

?>