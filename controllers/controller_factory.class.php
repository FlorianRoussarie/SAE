<?php
/** @brief Fabrique des contrôleurs de SHARE. */
class ControllerFactory
{
    /**
     * @brief Construit le contrôleur demandé par le contrôleur frontal.
     * @param string $controleur Nom du contrôleur dans l'URL.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur des vues.
     * @param \Twig\Environment $twig Environnement Twig.
     * @return Controller Contrôleur demandé.
     * @throws Exception Si la classe demandée n'existe pas.
     */
    public static function getController(string $controleur, \Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig): Controller
    {
        $controllerName = 'Controller' . ucfirst($controleur);
        if (!class_exists($controllerName)) {
            throw new Exception("Le controleur $controllerName n'existe pas");
        }
        return new $controllerName($twig, $loader);
    }
}