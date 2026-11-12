<?php
/** @brief Services communs aux contrôleurs MVC de SHARE. */
class Controller
{
    private ?PDO $pdo;
    private \Twig\Loader\FilesystemLoader $loader;
    private \Twig\Environment $twig;
    private ?array $get = null;
    private ?array $post = null;

    /**
     * @brief Initialise la connexion et les paramètres de la requête.
     * @param \Twig\Environment $twig Environnement Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Chargeur des vues.
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        $this->setPdo(Bd::getInstance()->getConnexion());
        $this->setTwig($twig);
        $this->setLoader($loader);
        $this->setGet(!empty($_GET) ? $_GET : null);
        $this->setPost(!empty($_POST) ? $_POST : null);
    }

    /**
     * @brief Appelle la méthode demandée par le contrôleur frontal.
     * @param string $methode Nom de la méthode.
     * @return mixed Résultat de l'action.
     * @throws Exception Si la méthode n'existe pas.
     */
    public function call(string $methode): mixed
    {
        if (!method_exists($this, $methode)) {
            throw new Exception("La méthode $methode n'existe pas dans le controller " . __CLASS__);
        }
        return $this->$methode();
    }

    /** @brief Retourne la connexion transmise aux DAO. @return PDO|null Connexion. */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /** @brief Affecte la connexion. @param PDO|null $pdo Connexion. @return void */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /** @brief Retourne le chargeur des vues. @return \Twig\Loader\FilesystemLoader Chargeur. */
    public function getLoader(): \Twig\Loader\FilesystemLoader
    {
        return $this->loader;
    }

    /** @brief Affecte le chargeur. @param \Twig\Loader\FilesystemLoader $loader Chargeur. @return void */
    public function setLoader(\Twig\Loader\FilesystemLoader $loader): void
    {
        $this->loader = $loader;
    }

    /** @brief Retourne Twig. @return \Twig\Environment Environnement Twig. */
    public function getTwig(): \Twig\Environment
    {
        return $this->twig;
    }

    /** @brief Affecte Twig. @param \Twig\Environment $twig Environnement Twig. @return void */
    public function setTwig(\Twig\Environment $twig): void
    {
        $this->twig = $twig;
    }

    /** @brief Retourne les paramètres GET. @return array|null Paramètres. */
    public function getGet(): ?array
    {
        return $this->get;
    }

    /** @brief Affecte les paramètres GET. @param array|null $get Paramètres. @return void */
    public function setGet(?array $get): void
    {
        $this->get = $get;
    }

    /** @brief Retourne les paramètres POST. @return array|null Paramètres. */
    public function getPost(): ?array
    {
        return $this->post;
    }

    /** @brief Affecte les paramètres POST. @param array|null $post Paramètres. @return void */
    public function setPost(?array $post): void
    {
        $this->post = $post;
    }
}
