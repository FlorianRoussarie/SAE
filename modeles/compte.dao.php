<?php
class CompteDAO{
    private $pdo;
    public function __construct($pdo){
        $this->pdo=$pdo;
    }
    /*Bannit un compte en modifiant son statut a 'banni' dans la base de données via un baouton '*/
    public function bannir($id){
        $sql="UPDATE". PREFIXE_TABLE . " Compte 
        SET statut ='banni'
        WHERE id= ?";
        $stmt =$this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}