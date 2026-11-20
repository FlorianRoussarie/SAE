<?php
/**
 * @file include.php
 * @brief Code commun à toutes les pages, inclus en haut de index.php.
 *
 * @details Ordre des inclusions (à respecter) :
 * session → autoload Composer → .env → config.yml → Twig → Bd → contrôleurs → modèles.
 * Chaque nouveau fichier de classe doit être ajouté ici avec require_once.
 */

//Démarrage de la session (avant tout affichage)
session_start();

//Le formulaire de déconnexion utilise un jeton propre à la session connectée.
if (isset($_SESSION['id_compte']) && !isset($_SESSION['csrf_deconnexion'])) {
    $_SESSION['csrf_deconnexion'] = bin2hex(random_bytes(32));
}

//Ajout de l'autoload de composer
require_once __DIR__ . '/vendor/autoload.php';

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
require_once __DIR__ . '/modeles/config.class.php';

//Préfixe des tables lu dans config.yml, exposé en constante pour écrire les requêtes
//des DAO comme dans le cours : "SELECT ... FROM " . PREFIXE_TABLE . "video"
define('PREFIXE_TABLE', Config::getConfig()['db']['prefixe_table']);

//Ajout du code pour initialiser twig
require_once __DIR__ . '/config/twig.php';

//Ajout du modèle qui gère la connexion mysql
require_once __DIR__ . '/modeles/bd.class.php';

//Ajout des contrôleurs
require_once __DIR__ . '/controllers/controller.class.php';
require_once __DIR__ . '/controllers/controller_factory.class.php';
require_once __DIR__ . '/controllers/controller_compte.class.php';
require_once __DIR__ . '/controllers/controller_video.class.php';
require_once __DIR__ . '/controllers/controller_signalement.class.php';

//Ajout des modèles
require_once __DIR__ . '/modeles/compte.class.php';
require_once __DIR__ . '/modeles/compte.dao.php';
require_once __DIR__ . '/modeles/video.class.php';
require_once __DIR__ . '/modeles/video.dao.php';
require_once __DIR__ . '/modeles/compte.dao.php';
require_once __DIR__ . '/modeles/signalement.class.php';
require_once __DIR__ . '/modeles/signalement.dao.php';



