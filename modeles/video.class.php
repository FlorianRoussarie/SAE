<?php

class Video{
    // ATTRIBUTS
    private ?int $id;
    private ?string $titre;
    private ?string $description;
    private ?string $chemin_video;
    private ?int $taille_fichier;
    private ?int $id_auteur;

    // CONSTRUCTEUR
    public function __construct(
        ?int $id = null, 
        ?string $titre = null, 
        ?string $description = null, 
        ?string $chemin_video = null, 
        ?int $taille_fichier = null, 
        ?int $id_auteur = null
    ){
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->chemin_video = $chemin_video;
        $this->taille_fichier = $taille_fichier;
        $this->id_auteur = $id_auteur;
    }

    // GETTERS
    public function getId(): ?int {
        return $this->id;
    }

    public function getTitre(): ?string {
        return $this->titre;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getCheminVideo(): ?string {
        return $this->chemin_video;
    }

    public function getTailleFichier(): ?int {
        return $this->taille_fichier;
    }

    public function getIdAuteur(): ?int {
        return $this->id_auteur;
    }
}

?>