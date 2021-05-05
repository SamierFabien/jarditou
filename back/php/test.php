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
$mainErrors = $verify->getMainErrorList();
$fieldsErrors = $verify->getFieldsErrorList();

echo 'Retour sur les listes des erreurs';
echo '<pre>';
var_dump($mainErrors);
var_dump($fieldsErrors);
echo '<pre>';
?>