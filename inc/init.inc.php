<?php
// Connexio BDD
$pdo = new PDO('mysql:host=localhost;dbname=site', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING,PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// Session
session_start();

// Chemin
define("RACINE_SITE","/lokisalle/");

// Variables
$msg = '';
$page = '';
$contenu = '';
// Autres inclusions
require_once("fonctions.inc.php");

?>
