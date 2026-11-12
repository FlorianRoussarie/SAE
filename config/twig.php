<?php
/** @file twig.php @brief Initialisation unique des vues Twig de SHARE. */
use Twig\Extra\Intl\IntlExtension;

$loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new Twig\Environment($loader, array('debug' => true));
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Paris');
$twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->addExtension(new IntlExtension());
$twig->addGlobal('config', Config::getConfig());
?>