<?php

/**
 * @brief Contrôleur permettant de gérer les vidéos.
 */
class ControllerVideo extends Controller{
    private VideoDAO $videoDAO;

    /**
     * @brief Contructeur du contrôleur vidéo.
     * @param \Twig\Environment $twig Environment Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur des vues.
     */
    public function __construct(
        \Twig\Environment $twig,
        \Twig\Loader\FilesystemLoader $loader
    ){
        parent::__construct($twig, $loader);

        $bd = Bd::getInstance()->getConnexion();
        $this->videoDAO = new VideoDAO($bd);
    }

    /**
     * @brief Affiche la page d'une vidéo.
     * @return void
     */
    public function afficher(): void
    {
        // Récupération de la vidéo
        $video = $this->videoDAO->getVideoAleatoire();

        // Affichage de la vue
        $template = $this->getTwig()->load('video.html.twig');
        echo $template->render(array('video'=> $video));
    }
}

?>
