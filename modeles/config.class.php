<?php
use Symfony\Component\Yaml\Yaml;

/** @brief Singleton de chargement de la configuration YAML existante. */
class Config
{
    private static ?Config $instance = null;
    private static ?array $config = null;

    /** @brief Charge la configuration. @throws Exception Si le préfixe SQL manque. */
    private function __construct()
    {
        self::$config = Yaml::parseFile(__DIR__ . '/../config/config.yml');
        if (!is_array(self::$config)
            || !isset(self::$config['db']['prefixe_table'])
            || !is_string(self::$config['db']['prefixe_table'])) {
            throw new Exception('Configuration incomplète : renseignez db.prefixe_table dans config/config.yml avec le préfixe réel des tables (chaîne vide si aucun).');
        }
    }

    /** @brief Retourne les paramètres YAML. @return array Configuration. */
    public static function getConfig(): array
    {
        if (self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$config;
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
