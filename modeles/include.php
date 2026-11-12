<?php
/**
 * @file include.php
 * @brief Code commun à toutes les pages, inclus en haut de index.php.
 *
 * @details Ordre des inclusions (à respecter) :
 * session → autoload Composer → .env → config.yml → Twig → Bd → contrôleurs → modèles.
 * Chaque nouveau fichier de classe doit être ajouté ici avec require_once.
 */

//Démarrage de la session (avant tout affichage) : utilisée par la connexion (US02)
session_start();

//Ajout de l'autoload de composer
require_once 'vendor/autoload.php';

//Chargement des secrets depuis le fichier .env (accès à la base de données)
//Les valeurs sont ensuite disponibles dans $_ENV['DB_HOST'], $_ENV['DB_NAME']...
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
} catch (Exception $e) {
    die('Configuration incomplète : copiez .env.example en .env et renseignez les accès à la base (voir README). Détail : ' . $e->getMessage());
}

//Chargement des paramètres de l'application (config/config.yml) via le Singleton Config
require_once 'modeles/config.class.php';

//Préfixe des tables lu dans config.yml, exposé en constante pour écrire les requêtes
//des DAO comme dans le cours : "SELECT ... FROM " . PREFIXE_TABLE . "video"
define('PREFIXE_TABLE', Config::getConfig()['db']['prefixe_table']);

//Ajout du code pour initialiser twig
require_once 'config/twig.php';

//Ajout du modèle qui gère la connexion mysql
require_once 'modeles/bd.class.php';

//Ajout des contrôleurs
require_once 'controllers/controller.class.php';
require_once 'controllers/controller_factory.class.php';
require_once 'controllers/controller_compte.class.php';
require_once 'controllers/controller_video.class.php';
require_once 'controllers/controller_signalement.class.php';

//Ajout des modèles
require_once 'modeles/compte.class.php';
require_once 'modeles/compte.dao.php';
require_once 'modeles/video.class.php';
require_once 'modeles/video.dao.php';
require_once 'modeles/compte.dao.php';
require_once 'modeles/signalement.class.php';
require_once 'modeles/signalement.dao.php';
