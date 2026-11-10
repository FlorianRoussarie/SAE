<?php

class ControllerFactory{
    public static function getController($controllerName, $loader, $twig)
    {
        switch ($controllerName) {
            case 'video':
                $bd = Bd::getInstance()->getConnexion();
                $videoDAO = new VideoDAO($bd);

                return new ControllerVideo($videoDAO, $twig);
            
            default:
                throw new Exception("Contrôleur inconnu : ". $controllerName);
        }
    }
}