<?php
/**
 * @file index.php
 * @brief Point d'entrée unique de l'application Share (contrôleur frontal).
 *
 * @details Toutes les URL passent par ce fichier avec deux paramètres GET :
 * - controleur : le nom de l'objet (ex. "video" pour ControllerVideo) ;
 * - methode : la méthode du contrôleur à appeler (ex. "defiler").
 * Exemple : index.php?controleur=video&methode=defiler&id_video=3
 */

//Ajout du code commun à toutes les pages
require_once 'include.php';

try {
    if (isset($_GET['controleur'])) {
        $controllerName = $_GET['controleur'];
    } else {
        $controllerName = '';
    }

    if (isset($_GET['methode'])) {
        $methode = $_GET['methode'];
    } else {
        $methode = '';
    }

    //Gestion de la page d'accueil par défaut : le fil de vidéos
    //(la protection des pages renverra vers la connexion si besoin, tâche 0211)
    if ($controllerName == '' && $methode == '') {
        $controllerName = 'video';
        $methode = 'defiler';
    }

    if ($controllerName == '') {
        throw new Exception('Le controleur n\'est pas défini');
    }

    if ($methode == '') {
        throw new Exception('La méthode n\'est pas définie');
    }

    $controller = ControllerFactory::getController($controllerName, $loader, $twig);

    $controller->call($methode);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
