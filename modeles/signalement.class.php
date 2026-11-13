<?php
class Signalement {

    private int|null $id;
    private string|null $raison;
    private string|null $date;
    private string|null $statut;
    private int|null $id_auteur;
    private int|null $id_cible;
    private int|null $id_video;
    //private Compte|null $auteur;
    //private Compte|null $cible;
    //private Video|null $video;

    public function __construct(?int $id = null, ?string $raison = null, ?string $date = null, ?string $statut = null, 
                                ?int $id_auteur = null, ?int $id_cible = null,?int $id_video = null/*, 
                                ?Compte $cible, ?Compte $auteur, ?Video $video*/) {

        $this->id = $id;
        $this->raison = $raison;
        $this->date = $date;
        $this->statut = $statut;
        $this->id_auteur = $id_auteur;
        $this->id_cible = $id_cible;
        $this->id_video = $id_video;
        //$this->video = 
    }

    public function getId(): ?int { return $this->id; }
    //setId est pas utilisé à priori vu que c'est un auto incrément
    public function setId(?int $id): void { $this->id = $id; } 
    public function getRaison(): ?string { return $this->raison; }
    public function setRaison(?string $raison): void { $this->raison = $raison; } 
    public function getDate(): ?string { return $this->date; }
    public function setDate(?string $date): void { $this->date = $date; } 
    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(?string $statut): void { $this->statut = $statut; } 
    public function getIdAuteur(): ?int { return $this->id_auteur; }
    public function setIdAuteur(?int $id_auteur): void { $this->id_auteur = $id_auteur; } 
    public function getIdCible(): ?int { return $this->id_cible; }
    public function setIdCible(?int $id_cible): void { $this->id_cible = $id_cible; } 
    public function getIdVideo(): ?int { return $this->id_video; }
    public function setIdVideo(?int $id_video): void { $this->id_video = $id_video; } 
   
}