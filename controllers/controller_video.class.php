<?php

/**
 * @brief Contrôleur permettant de gérer les vidéos.
 */
class ControllerVideo extends Controller{
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
    }

    /**
     * @brief Affiche la page d'une vidéo.
     * @return void
     */
    public function afficher(): void
    {
        $managerVideo = new VideoDAO($this->getPdo());
        // Récupération de la vidéo
        $video = $managerVideo->getVideoAleatoire();

        // Affichage de la vue
        $template = $this->getTwig()->load('video.html.twig');
        echo $template->render(array('video'=> $video));
    }
}

?>
