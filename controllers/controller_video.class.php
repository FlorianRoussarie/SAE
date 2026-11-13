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
     * @brief Affiche la page d'une vidéo, avec la vidéo suivante pour le défilement.
     * @details Paramètre GET facultatif id_video : la vidéo à afficher.
     * S'il est absent ou invalide, une vidéo est tirée au hasard.
     * Page réservée aux comptes connectés.
     * @return void
     */
    public function afficher(): void
    {
        $this->verifierConnexion();

        $idVideo = filter_input(INPUT_GET, 'id_video', FILTER_VALIDATE_INT);
        $managerVideo = new VideoDAO($this->getPdo());

        // Récupération de la vidéo
        $video = null;
        if ($idVideo !== null && $idVideo !== false) {
            $video = $managerVideo->find($idVideo);
        }
        if ($video === null) {
            $video = $managerVideo->getVideoAleatoire();
        }

        // Vidéo suivante : une autre vidéo au hasard
        $videoSuivante = null;
        if ($video !== null) {
            $videoSuivante = $managerVideo->getVideoAleatoire($video->getId());
        }

        // Affichage de la vue
        $template = $this->getTwig()->load('video.html.twig');
        echo $template->render(array('video'=> $video, 'videoSuivante' => $videoSuivante));
    }
}

?>
