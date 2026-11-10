<?php
class ControllerCompte extends Controller  {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader){
        parent:: __construct($twig,$loader);
    }
    //Permet de bannir un utilisateur ciblé "
    public function bannir() {
        //Vérifiaction que l'utilisateur est bien un administrateur
        $estAdmin =0;
        if(!isset($_SESSION['est_admin'])){
            $estAdmin= $_SESSION['est_admin'];
        }
        if ($estAdmin!=1){
            throw new Exception("Accès non autorisé : cett action est réservé aux adminsitrateurs"); 
        }
        //Reécuparation de l'ID de l'utilisateur
        $id=null;
        if (isset($_GET['id'])) {
            $id=$_GET['id'];
        }
        if($id!== null){
            if(is_numeric($id)){
            $dao =new CompteDAO(BD:: getInstance());
            $dao->bannir($id);  
            }
           
        }
        // Redirige vers la liste des compte après le signalement
         header('Location: index.php?controlles=compteaction=lister');
            exit();
    }
}
