<?php

use Symfony\Component\Yaml\Yaml;

class Config
{
    private static ?array $config = null;

    public static function getConfig(): array
    {
        if (self::$config === null) {
            $config = Yaml::parseFile(__DIR__ . '/../config/config.yml');

            if (!is_array($config) || !isset($config['db']['prefixe_table'])
                || !is_string($config['db']['prefixe_table'])) {
                throw new RuntimeException('Configuration invalide : db.prefixe_table doit être une chaîne.');
            }

            self::$config = $config;
        }

        return self::$config;
    }
}