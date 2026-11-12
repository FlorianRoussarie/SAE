<?php
class Compte{
    private $id;
    private $pseudo;
    private $prenom;
    private $nom;
    private $adresse_mail;
    private $telephone;
    private $mdp;
    private $date_naissance;
    private $adresse;
    private $statut;
    private $est_admin;

    public function __construct($id,$pseudo,$prenom,$nom,$adresse_mail,$telephone,$mdp,$date_naissance,$adresse,$statut,$est_admin){
        $this->id=$id;
        $this->pseudo=$pseudo;
        $this->prenom=$prenom;
        $this->nom=$nom;
        $this->adresse_mail=$adresse_mail;
        $this->telephone=$telephone;
        $this->mdp=$mdp;
        $this->date_naissance=$date_naissance;
        $this->adresse=$adresse;
        $this->statut=$statut;
        $this->est_admin=$est_admin;
    }
    //On n'a pas mis de setId parce que l'id s'auto incrémente 
    public function getId(){
        return $this->id;
    }

    public function getPseudo(){
        return $this->pseudo;
    }
    public function setPseudo($pseudo){
        $this->pseudo=$pseudo;
    }

    public function getPrenom(){
        return $this->prenom;
    }
    public function setPrenom($prenom){
        $this->prenom=$prenom;
    }

    public function getNom(){
        return $this->nom;
    }
    public function setNom($nom){
        $this->nom=$nom;
    }

    public function getAdresseMail(){
        return $this->adresse_mail;
    }
    public function setAdresseMail($adresse_mail){
        $this->adresse_mail=$adresse_mail;
    }

    public function getTelephone(){
        return $this->telephone;
    }
    public function setTelephone($telephone){
        $this->telephone=$telephone;
    }

    public function getMdp(){
        return $this->mdp;
    }
    public function setMdp($mdp){
        $this->mdp=$mdp;
    }

    public function getDateNaissance(){
        return $this->date_naissance;
    }
    public function setDateNaissance($date_naissance){
        $this->date_naissance=$date_naissance;
    }

    public function getAdresse(){
        return $this->adresse;
    }
    public function setAdresse($adresse){
        $this->adresse=$adresse;
    }

    public function getStatut(){
        return $this->statut;
    }
    public function setStatut($statut){
        $this->statut=$statut;
    }
     public function getEst_admin(){
        return $this->est_admin;
    }
    public function setEstadmin($est_admin){
        $this->est_admin=$est_admin;
    }
}