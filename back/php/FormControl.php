<?php
/**
 * Classe de vérification de formulaires
 * 
 * Fonctionnement
 * Soit 3 pages : #formulaire, #traitement, #resultat
 * 
 * 1- #formulaire (page où se trouve le formulaire)
 * -méthode POST obligatoire
 * -session_start(); obligatoire
 * -require 'chemin/vers/FormControl.php';
 * -en début de code : 
 *      $uneVariable = FormControl::unSerialization();
 * -à l'endroit où on veut afficher le message d'erreur pour chaque champs :
 *      $uneVariable->displayFieldErrors('leNomDuChamps');
 * 
 * 2- #traitement (page qui vérifie le formulaire)
 * -session_start() obligatoire
 * -require 'chemin/vers/FormControl.php';
 * -déclaration de deux tableaux. Un qui représente les champs 'required'
 * et le deuxième qui représente les champs non-requis.
 * Pour les deux tableau, chaque entrée représente un champs a contrôler
 * avec "nomDuChamps" => "typeDeControle"
 * exemple :
 *      $requiredFields = array(
 *          "prenom" => "nom",
 *          "naissance" => "date"
 *      );
 *      $nonRequiredFields = array(
 *          "adresse" => 'texte',
 *          "cp" => "codepostal"
 *      );
 * -à la suite des deux tableaux :
 *      $uneVariable = new FormControl($requiredFields, $nonRequiredFields);
 *      $uneVariable->serialization($uneVariable);
 *      $verify->root('chemin/vers/formulaireOk.php', 'chemin/vers/formulaireErreur.php');
 *      
 * 3- #resultat (page qui va afficher traiter le résultat, par exemple "Votre demande a bien été enregistrée")//inutile si #resultat = traitement
 * -session_start(); obligatoire
 * -require 'chemin/vers/FormControl.php';
 * -uneVariable = FormControl::unSerialization();
 */

class FormControl {
    /*Les types de contrôles*/
    const NOM = 'nom';
    const CODEPOSTAL = 'codepostal';
    const TELEPHONE = 'telephone';
    const EMAIL = 'email';
    const DATE = 'date';
    const GENRE = 'genre';
    const TEXTE = 'texte';
    const SELECTED = 'selected';

    /*Regex de contrôle et erreurs associées       /!\ php7.3 = pcre2 >>> les "-" doivent être échappés /!\ */
    const REQUIRED_ERROR = 'Ce champs est obligatoire';//Erreurs générique quand il n'y a rien dans un champs requis
    const NOM_REGEX = '~^[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ]([\'\-\s])?[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+([\'\-\s][a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+)?$~';
    const NOM_ERROR = 'Exemples valides : "Dupont", "dupont", "Jean Claude", "jean claude", "Jean-Claude", "N\'Bekele"';
    const CODEPOSTAL_REGEX = '~^[0-9]{5}$~';
    const CODEPOSTAL_ERROR = 'Mauvais format : composé de 5 chiffres uniquement.';
    const TELEPHONE_REGEX = '~^[0-9]{10}$|[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}$~';
    const TELEPHONE_ERROR = 'Exemples valides : "0699999999", "06 99 99 99 99", "06-99-99-99-99", "06.99.99.99.99"';
    const EMAIL_REGEX = '~^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$~';
    const EMAIL_ERROR = 'Structure d\'une adresse e-mail : "moi@exemple.com".';
    const DATE_REGEX = '~^[0-9]{4}[\s\-/][0-9]{2}[\s\-/][0-9]{2}$|[0-9]{2}[\s\-/][0-9]{2}[\s\-/][0-9]{4}$~';
    const DATE_ERROR = 'Mauvais format. Formats supporté : "00/00/0000" "0000/00/00" avec des slashs, espaces et tirets comme séparateurs.';
    const GENRE_REGEX = '~^femme$|^homme$|^neutre$~';
    const GENRE_ERROR = 'Soit : "femme", "homme", ou "neutre"';
    const TEXTE_REGEX = '~[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ\'\s\-.]+~';
    const TEXTE_ERROR = 'Doit être composé de lettres et de chiffres ainsi que " ", "-", "."';
    const SELECTED_REGEX = '~^on$~';
    const SELECTED_ERROR = 'Vous devez accepter en cochant cette case';
    
    public static $regexList = [
        self::NOM =>        ['regex' => self::NOM_REGEX,          'error' => self::NOM_ERROR],
        self::CODEPOSTAL => ['regex' => self::CODEPOSTAL_REGEX,   'error' => self::CODEPOSTAL_ERROR],
        self::TELEPHONE =>  ['regex' => self::TELEPHONE_REGEX,    'error' => self::TELEPHONE_ERROR],
        self::EMAIL =>      ['regex' => self::EMAIL_REGEX,        'error' => self::EMAIL_ERROR],
        self::DATE =>       ['regex' => self::DATE_REGEX,         'error' => self::DATE_ERROR],
        self::GENRE =>      ['regex' => self::GENRE_REGEX,        'error' => self::GENRE_ERROR],
        self::TEXTE =>      ['regex' => self::TEXTE_REGEX,        'error' => self::TEXTE_ERROR],
        self::SELECTED =>   ['regex' => self::SELECTED_REGEX,     'error' => self::SELECTED_ERROR]
    ];
    private $requiredFields;
    private $nonRequiredFields;
    private $fieldsErrorList;
    
    public function __construct($requiredFields, $nonRequiredFields){
        $this->destroySession();
        $this->fieldsErrorList = new ArrayObject();
        $this->setRequiredFields($requiredFields);
        $this->setNonRequiredFields($nonRequiredFields);
        $this->verifyRequired($this->getRequiredFields(), $this->getFieldsErrorList());
        $this->verifyValues($this->getRequiredFields(), $this->getNonRequiredFields(), $this->getFieldsErrorList());
    }

    public function __clone(){}
    
    public function verifyRequired($reqList, $fieldsErrList){
        //Pour chaque champs requis, dans la liste, si le $_POST[$champs] n'existe pas ou est null, on l'ajoute à la liste d'erreurs et on l'enleve de la liste des valeurs à contrôler 

        foreach ($reqList as $field => $value){//Pour chaque champs requis du formulaire
            if (!isset($_POST[$field]) || empty($_POST[$field])) {//Si la donnée n'existe pas
                $fieldsErrList[$field] = self::REQUIRED_ERROR;
                unset($reqList[$field]);
            }
        }
    }


    public function verifyValues($reqList, $nonReqList, $fieldsErrList){
        //pour chaque champs $field de $requiredFields, verifier si !preg_match(regexList[$field][regex]) > ajout de $fieldsErrorList[$field] = NOM_ERROR

        foreach ($reqList as $field => $regName) {
            if (!preg_match(self::$regexList[$regName]['regex'], $_POST[$field])) {
                $fieldsErrList[$field] = self::$regexList[$regName]['error'];
            }
        }
        foreach ($nonReqList as $field => $regName) {
            if (!preg_match(self::$regexList[$regName]['regex'], $_POST[$field])) {
                $fieldsErrList[$field] = self::$regexList[$regName]['error'];
            }
        }
    }

    public function root($successPage, $errorPage){
        if (count($this->getFieldsErrorList()) > 0) {
            header('Location: '.$errorPage);
        } else {
            header('Location: '.$successPage);
        }
    }

    public function displayFieldError($field){//A TESTER
        foreach ($this->getFieldsErrorList() as $key => $value) {
            if ($key === $field) {
                return $value;
            }
        }
    }

    public function serialization($instance){
        $_SESSION['formControl'] = serialize(clone($instance));
    }

    public static function unSerialization(){
        if (isset($_SESSION['formControl']) && $_SESSION['formControl'] != null){
            return unserialize($_SESSION['formControl']);
        }
    }

    public function destroySession(){
        if (isset($_SESSION['formControl'])) {
            unset($_SESSION['formControl']);
        }
    }
    
    public function getRequiredFields(){
        return $this->requiredFields;
    }
    
    public function setRequiredFields($tableau){
        if (is_array($tableau)) {
            $this->requiredFields = new ArrayObject($tableau);
        }
    }
    
    public function getNonRequiredFields(){
        return $this->nonRequiredFields;
    }
    
    public function setNonRequiredFields($tableau){
        if (is_array($tableau)) {
            $this->nonRequiredFields = new ArrayObject($tableau);
        }
    }
    
    public function getFieldsErrorList() {
        return $this->fieldsErrorList;
    }
    
    public function setFieldsErrorList($tableau) {
        $fieldsErrorList = new ArrayObject($tableau);
    }
}
