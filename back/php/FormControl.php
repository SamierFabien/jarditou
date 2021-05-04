<?php
class FormControl {
    /*Regex de contrôle*/
    const NOM = '#^[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ]([\'-\s])?[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+([\'-\s][a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+)?$#';
    const NOM_ERROR = 'Exemples valides : "Dupont", "dupont", "Jean Claude", "jean claude", "Jean-Claude", "N\'Bekele"';
    const CODEPOSTAL = '#^[0-9]{5}$#';
    const CODEPOSTAL_ERROR = 'Mauvais format : composé de 5 chiffres uniquement.';
    const TELEPHONE = '#^[0-9]{10}$|[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}$#';
    const TELEPHONE_ERROR = 'Exemples valides : "0699999999", "06 99 99 99 99", "06-99-99-99-99", "06.99.99.99.99"';
    const EMAIL = '#^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$#';
    const EMAIL_ERROR = 'Structure d\'une adresse e-mail : "moi@exemple.com".';
    const DATE = '#^[0-9]{4}[\s-/][0-9]{2}[\s-/][0-9]{2}$|[0-9]{2}[\s-/][0-9]{2}[\s-/][0-9]{4}$#';
    const DATE_ERROR = 'Mauvais format. Formats supporté : "00/00/0000" "0000/00/00" avec des slashs, espaces et tirets comme séparateurs.';
    const GENRE = '#^femme$|^homme$|^neutre$#';
    const GENRE_ERROR = 'Soit : "femme", "homme", ou "neutre"';
    const TEXTE = '#[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ\'\s-.]+#';
    const TEXTE_ERROR = 'Doit être composé de lettres et de chiffres ainsi que " ", "-", "."';
    
    public static $regexList = [NOM, CODEPOSTAL, TELEPHONE, EMAIL, DATE, GENRE, TEXTE];
    private $requiredFields;
    private $nonRequiredFields;
    private $mainErrorList = [];
    private $fieldsErrorList = [];
    
    public function __construct($requiredFields, $nonRequiredFields){
        setRequiredFields($requiredFields);
        setNonRequiredFields($nonRequiredFields);
        requiredVerify();
        
    }
    
    public function createFieldsErrorList(){//Vraiment utile ???
        foreach (getRequiredFields() as $key => $value){
            //array_push(getFieldsErrorList, array($key => ''));
            getFieldsErrorList()[$key] = '';
        }
        foreach (getNonRequiredFields() as $key => $value){
            //array_push(getFieldsErrorList, array($key => ''));
            getFieldsErrorList()[$key] = '';
        }
    }
    
    public function requiredVerify(){
        foreach (getRequiredFields() as $key){//Pour chaque champs requis du formulaire
            try {
                if (!isset($_POST($key))) {//Si la donnée n'existe pas
                    throw new Error('Ce champs est obligatoire');
                }
            } catch (Exception $e) {
                getFieldsErrorList()[$key] = '$e->getMessage()';
            }
        }
    }
    
    public function valuesVerify(){
        
    }
    
    public function arrayVerify($table){
        if (is_array($table)) {//Si c'est un tableau
            foreach ($table as $key => $value){
                try {
                    if (!in_array($value, FormControl::$regexList)) {//Si la veleur de chaque clé correspond bien a une regex de $regexList
                        //Valeur ne se trouve pas dans la liste des regex disponibles.
                        throw new Error('Expression régulière introuvable.');
                    }
                } catch (Exception $e) {
                     array_push(getMainErrorList(), $e->getMessage());
                }
            }
        } else {
            //N'est pas un tableau
        }
    }
    
    public function getRequiredFields(){
        return $requiredFields;
    }
    
    public function setRequiredFields($tableau){//VERIFIER
        $requiredFields = $tableau;
    }
    
    public function getNonRequiredFields(){
        return $nonRequiredFields;
    }
    
    public function setNonRequiredFields($tableau){//VERIFIER
        $nonRequiredFields = $tableau;
    }
    
    public function getMainErrorList() {
        return $mainErrorList;
    }
    
    public function setMainErrorList($tableau) {//VERIFIER
        $mainErrorList $tableau
    }
    
    public function getFieldsErrorList($param) {
        return $fieldsErrorList;
    }
    
    public function setFieldsErrorList($tableau) {//VERIFIER
        $fieldsErrorList = $tableau;
    }
}
