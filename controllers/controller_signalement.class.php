<?php

class ControllerSignalement extends Controller {

    //constructeur
    public function __construct(\TWIG\Loader\FilesystemLoader $loader, \TWIG\Environment $twig) {
        parent::__construct($loader, $twig);
    }

    //affichage d'un seul signalement
    public function afficher(){
        $id_signalement = isset($_GET['id'])?$_GET['id']:null;
        $managerSignalement = new SignalementDao($this->getPdo());
        $signalement = $managerSignalement->find($id_signalement);
        $template = $this->getTwig()->load('categorie.html.twig');
        echo $template->render(array(
                'signalement' => $signalement,
            ));
    }

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