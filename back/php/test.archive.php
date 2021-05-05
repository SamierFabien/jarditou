<?php
var_dump($_POST);

/*Regex de contrôle*/
define('NOM', '#^[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ]([\'-\s])?[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+([\'-\s][a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+)?$#');
define('CODEPOSTAL', '#^[0-9]{5}$#');
define('TELEPHONE', '#^[0-9]{10}$|[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}$#');
define('EMAIL', '#^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$#');
define('DATE', '#^[0-9]{4}[\s-/][0-9]{2}[\s-/][0-9]{2}$|[0-9]{2}[\s-/][0-9]{2}[\s-/][0-9]{4}$#');
define('GENRE', '#^femme|homme|neutre$#');
define('TEXTE', '#[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ]+#');

/**
 * Elements requis :
 * nom
 * prenom
 * genre
 * date
 * cp
 * email
 * sujet
 * question
 * accord
 * 
 * Non-requis :
 * Adresse
 * Ville
 */

$requiredFields = ["nom" => NOM, "prenom" => NOM, "genre" => GENRE, "date" => DATE, "cp" => CODEPOSTAL, "email" => EMAIL, "sujet" => TEXTE, "question" => TEXTE, "accord" => "on"];
$nonRequiredFields = ["adresse" => TEXTE, "ville" => TEXTE];












?>