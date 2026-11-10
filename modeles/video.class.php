<?php

class Video{
    // ATTRIBUTS
    private $id;
    private $titre;
    private $description;
    private $chemin_video;
    private $taille_fichier;
    private $id_auteur;

    // CONSTRUCTEUR
    public function __construct($id, $titre, $description, $chemin_fichier, $taille_fichier, $id_auteur){
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->chemin_video = $chemin_video;
        $this->taille_fichier = $taille_fichier;
        $this->id_auteur = $id_auteur;
    }

    // GETTERS
    public function getId(): int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getCheminVideo(): string {
        return $this->chemin_video;
    }

    public function getTailleFichier(): int {
        return $this->taille_fichier;
    }

    public function getIdAuteur(): int {
        return $this->id_auteur;
    }
}

?>