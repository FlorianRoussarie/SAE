<?php
class CompteDAO{
    private $pdo;
    public fuunction __construct($pdo){
        $this->pdo=$pdo;
    }
    public function bannir($id){
        $sql="UPDATE Compte SET statut ='banni'WHERE id= :id";
        $stmt =$this->pdo->prepare($sql);
        $stmt->execute(['id'=>$id]);
    }
}