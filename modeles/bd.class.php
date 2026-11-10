<?php

class Bd
{
    private static ?Bd $instance = null;
    private PDO $connexion;

    private function __construct()
    {
        $variables = [];
        foreach (['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'] as $nom) {
            $valeur = $_ENV[$nom] ?? getenv($nom);
            if ($valeur === false || $valeur === null) {
                throw new RuntimeException('Variable d\'environnement manquante : ' . $nom);
            }
            $variables[$nom] = $valeur;
        }

        $dsn = 'mysql:host=' . $variables['DB_HOST']
            . ';dbname=' . $variables['DB_NAME'] . ';charset=utf8mb4';

        $this->connexion = new PDO($dsn, $variables['DB_USER'], $variables['DB_PASS'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public static function getInstance(): Bd
    {
        if (self::$instance === null) {
            self::$instance = new Bd();
        }

        return self::$instance;
    }

    public function getConnexion(): PDO
    {
        return $this->connexion;
    }

    private function __clone()
    {
    }

    public function __wakeup(): void
    {
        throw new LogicException('La connexion Bd ne peut pas être désérialisée.');
    }
}