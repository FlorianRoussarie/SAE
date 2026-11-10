<?php
/** @brief Singleton de connexion MySQL utilisé par les contrôleurs. */
class Bd
{
    private static ?Bd $instance = null;
    private ?PDO $pdo;

    /** @brief Ouvre la connexion avec les secrets déjà chargés depuis .env. */
    private function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8mb4',
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Connexion à la base de données échouée.');
        }
    }

    /** @brief Retourne l'unique instance. @return Bd Singleton. */
    public static function getInstance(): Bd
    {
        if (self::$instance === null) {
            self::$instance = new Bd();
        }
        return self::$instance;
    }

    /** @brief Retourne la connexion partagée. @return PDO Connexion MySQL. */
    public function getConnexion(): PDO
    {
        return $this->pdo;
    }

    /** @brief Empêche le clonage du singleton. */
    private function __clone()
    {
    }

    /** @brief Empêche la désérialisation. @throws Exception Toujours. @return void */
    public function __wakeup(): void
    {
        throw new Exception('Un singleton ne doit pas être désérialisé');
    }
}
