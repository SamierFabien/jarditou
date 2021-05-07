<?php
session_start();
require 'FormControl.php';

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
$verify->serialization($verify);
$verify->root('successPage.php', 'contact.php');
?>