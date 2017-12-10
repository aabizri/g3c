<?php

/* Déclaration des constantes pour le fonctionnement global du site */
// Typage strict
declare(strict_types=1);
// UTF8 Header
header('Content-type: text/html; charset=utf-8');
// Temporisation
ob_start();

/* Chargement de l'autoloader */
require_once(__DIR__."/Helpers/autoloader.php");

/* Configuration des sessions */
/*
// Set session handler
session_set_save_handler(new \Helpers\SessionSaveHandler);
// Start a session
$sess_opt = [
    "name" => "LiveWellSessionID",
    //"cookie_secure" => true, // TANT QU'ON NE SERA PAS EN HTTPS NE PAS ACTIVER
    "cookie_lifetime" => \Helpers\SessionSaveHandler::lifetime * 60 * 60 * 24,
];
session_start($sess_opt);*/

/* Routage */
// Récupération de la (c)atégorie et de l'(a)ction
http_response_code(400);
if (empty($_GET["c"])) {
    echo "400 error: no category given";
    die;
}
$category = $_GET["c"];

if (empty($_GET["a"])) {
    echo "400 error: no action given";
    die;
}
$action = $_GET["a"];

// Récupération des paramètres GET
$get = $_GET;
unset($get["c"], $get["a"]); // On enlève l'incation de controlleurs et d'action

// Récupération des paramètres POST
$post = $_POST;

// Route
http_response_code(200);
$exists = \Helpers\Router::route($category,$action,$get,$post);

// If it doesn't exist, return a 404
if (!$exists) {
    http_response_code(404);
    die;
}
