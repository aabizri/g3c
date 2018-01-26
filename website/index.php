<?php

/* Déclaration des constantes pour le fonctionnement global du site */
// Typage strict
declare(strict_types=1);

/* Chargement de l'autoloader */
require_once(__DIR__ . "/Helpers/autoloader.php");

/* Méta-traitement de la requête */

// Création de la requête
$req = new \Entities\Request();

// Auto-enregistrement à la fin
$req->saveAtShutdown();

// Auto-population des données
$req->autoSet();

/* Traitement de la requête */
\Helpers\Handler::handle($req);