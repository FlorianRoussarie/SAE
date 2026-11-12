<?php
class ControllerCompte extends Controller  {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader){
        parent:: __construct($twig,$loader);
    }
    //Permet de bannir un utilisateur ciblé "
    public function bannir() {
        if(($_SERVER['REQUEST_METHOD']??'')!== 'POST'){
            throw new Exception('Méthode non autorisé');
        }
        //Vérifiaction que l'utilisateur est bien un administrateur
        if(!isset($_SESSION['id_compte'])
            ||(int) ($_SESSION['est_admin']??0) !==1){
         throw new Exception("Accès non autorisé : cett action est réservé aux adminsitrateurs"); 
         }
    
           
        }
        //Reécuparation de l'ID de l'utilisateur
        $id=filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT,
            array('options' => array('min_range'=> 1))
        );
        if($id==null|| $id == false){
            throw new Exception("Identifiant de compte invalide."); 
        }
        $managerCompte =new CompteDAO($this->getPdo());
        $managerCompte->bannir($id);  
        // Redirige vers la liste des compte après le signalement
         header('Location: index.php?controleur=compte&methode=lister');
            exit();
    }

