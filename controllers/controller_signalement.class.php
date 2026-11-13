<?php

class ControllerSignalement extends Controller {

    //constructeur
    public function __construct(\TWIG\Environment $twig, \TWIG\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    //affichage d'un seul signalement
    public function afficher(){
         if(!isset($_SESSION['id_compte'])
            ||(int) ($_SESSION['est_admin']??0) !==1){
         throw new Exception("Accès non autorisé : cette page est réservée aux adminsitrateurs"); 
         }
        $id_signalement = isset($_GET['id'])?$_GET['id']:null;
        $managerSignalement = new SignalementDao($this->getPdo());
        $signalement = $managerSignalement->findAssoc($id_signalement);
        $template = $this->getTwig()->load('signalement.html.twig');
        echo $template->render(array(
                'signalement' => $signalement,
                'menu' => 'signalement'
            ));
    }

    //liste des signalements
    public function lister() {
         if(!isset($_SESSION['id_compte'])
            ||(int) ($_SESSION['est_admin']??0) !==1){
         throw new Exception("Accès non autorisé : cette page est réservée aux adminsitrateurs"); 
         }
        $managerSignalement = new SignalementDao($this->getPdo());
        $signalements = $managerSignalement->findAllAssoc();
        $template = $this->getTwig()->load('liste_signalements.html.twig');
        echo $template->render(array(
                'signalements' => $signalements,
                'menu' => 'liste_signalements'
            ));
    }
}