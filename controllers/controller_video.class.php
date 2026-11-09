<?php

class ControllerVideo extends Controller{
    private $videoDAO;
    private $twig;

    public function __construct($videoDAO, $twig){
        $this->videoDAO = $videoDAO;
        $this->twig = $twig;
    }

    public function defiler(){
        $video = $this->videoDAO->getVideoAleatoire();

        echo $this->twig->render('video.html.twig', [
            'video' => $video
        ]);
    }
}

?>