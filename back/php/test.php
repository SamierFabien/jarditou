<?php
require 'FormControl.php';
//var_dump($_POST);

$requiredFields = [
    "nom" => FormControl::NOM,
    "prenom" => FormControl::NOM,
    "genre" => FormControl::GENRE,
    "date" => FormControl::DATE,
    "cp" => FormControl::CODEPOSTAL,
    "email" => FormControl::EMAIL,
    "sujet" => FormControl::TEXTE,
    "question" => FormControl::TEXTE,
    "accord" => "on"
];
$nonRequiredFields = [
    "adresse" => FormControl::TEXTE,
    "ville" => FormControl::TEXTE
];
var_dump($requiredFields);
var_dump($nonRequiredFields);

$verify = new FormControl($requiredFields, $nonRequiredFields);
?>