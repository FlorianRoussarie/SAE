
<?php

// Indique à Twig ou se trouvent les fichiers HTML
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

// initialise Twig pour pouvoir afficher les pages de l'application
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);

// activer les fonctions de débogage Twig (dump)
$twig->addExtension(new \Twig\Extension\DebugExtension());

?>
