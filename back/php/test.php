<?php
require 'FormControl.php';
//var_dump($_POST);

/*
$requiredFields = [
    "nom" => "nom",
    "prenom" => "nom",
    "genre" => "genre",
    "naissance" => "date",
    "cp" => "codepostal",
    "email" => "email",
    "sujet" => "texte",
    "question" => "texte",
    "accord" => "selected"
];
$nonRequiredFields = [
    "adresse" => FormControl::TEXTE,
    "ville" => FormControl::TEXTE
];
*/

$requiredFields = array(
    "nom" => "nom",
    "prenom" => "nom",
    "genre" => "genre",
    "naissance" => "date",
    "cp" => "codepostal",
    "email" => "email",
    "sujet" => "texte",
    "question" => "texte",
    "accord" => "selected"
);
$nonRequiredFields = array(
    "adresse" => FormControl::TEXTE,
    "ville" => FormControl::TEXTE
);


$verify = new FormControl($requiredFields, $nonRequiredFields);
?>