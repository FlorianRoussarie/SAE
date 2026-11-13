<?php

class ControllerSignalement extends Controller {

    //constructeur
    public function __construct(\TWIG\Environment $twig, \TWIG\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    //affichage d'un seul signalement
    /*public function afficher(){
        $id_signalement = isset($_GET['id'])?$_GET['id']:null;
        $managerSignalement = new SignalementDao($this->getPdo());
        $signalement = $managerSignalement->find($id_signalement);
        $template = $this->getTwig()->load('liste_signalements.html.twig');
        echo $template->render(array(
                'signalement' => $signalement,
            ));
    }*/

    //liste des signalements
    public function lister() {
        $managerSignalement = new SignalementDao($this->getPdo());
        $signalements = $managerSignalement->findAll();
        $template = $this->getTwig()->load('liste_signalements.html.twig');
        echo $template->render(array(
                'signalements' => $signalements,
                'menu' => 'liste_signalements'
            ));
    }
}