<?php
session_start();
require 'FormControl.php';

$verify = FormControl::unSerialization();

echo 'Page d\'erreur';

var_dump($verify);


?>