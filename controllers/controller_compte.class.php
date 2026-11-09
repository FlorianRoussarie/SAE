<?php
class ControllerCompte extends Controller  {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader){
        parent:: __construct($twig,$loader);
    }
    public function bannir() {
        $id=null;
        if (isset($_POST['id'])) {
            $id=intval($_POST['id']);
        }
        if($id){
            $managerCompte =new CompteDAO($this->getPdo());
            $managerCompte->bannir($id);
            header('Location: index.php?controlles=compteaction=lister');
            exit();
        }
     
    }
}
