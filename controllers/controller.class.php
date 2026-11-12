<?php

/**
 * @brief Classe mère de tous les contrôleurs.
 */
class Controller{
    private \Twig\Environment $twig;
    private \Twig\Loader\FilesystemLoader $loader;

    /**
     * @brief Constructeur de contrôleur.
     * @param Twig\Environment $twig Environment Twig.
     * @param Twig\Loader\FilesystemLoader $loader Chargeur des vues.
     */
    public function __construct(
        \Twig\Environment $twig, 
        \Twig\Loader\FilesystemLoader $loader
    ){
        $this->twig = $twig;
        $this->loader = $loader;
    }

    /**
     * @brief Appelle une méthode du contrôleur.
     * @param string $methode Nom de la méthode à appeler.
     * @return mixed Résultat de la méthode appelée.
     * @throws Exception Si la méthodee n'existe pas ou n'est pas autorisée.
     */
    public function call(string $methode): mixed
    {
        if (!method_exists($this, $methode)) {
            throw new Exception("Méthode inconnue : " . $methode);
        }

        if ($methode === '__construct' || $methode === 'call') {
            throw new Exception("Méthode non autorisée");
        }

        return $this->$methode();
    }
}