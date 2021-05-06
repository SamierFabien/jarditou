<?php
session_start();
require 'FormControl.php';

echo '<pre>';
var_dump($_POST);
echo '</pre>';

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
    "adresse" => 'texte',
    "ville" => 'texte'
);

$verify = new FormControl($requiredFields, $nonRequiredFields);
//var_dump($verify);
$verify->serialization($verify);
$verify->root('successPage.php', 'contact.php');
?>