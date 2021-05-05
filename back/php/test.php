<?php
require 'FormControl.php';
//var_dump($_POST);

/*
$requiredFields = [
    "nom" => FormControl::NOM,
    "prenom" => FormControl::NOM,
    "genre" => FormControl::GENRE,
    "naissance" => FormControl::DATE,
    "cp" => FormControl::CODEPOSTAL,
    "email" => FormControl::EMAIL,
    "sujet" => FormControl::TEXTE,
    "question" => FormControl::TEXTE,
    "accord" => FormControl::SELECTED
];
$nonRequiredFields = [
    "adresse" => FormControl::TEXTE,
    "ville" => FormControl::TEXTE
];
*/

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


$verify = new FormControl($requiredFields, $nonRequiredFields);
?>