<?php
/*

MA QUESTION : COMMENT PASSER DES TABLEAUX PAR REFERENCE ?

PAR EXEMPLE PASSER $requiredFields DANS LA FONCTION verifyRequired()


essayer : 
$array = array('1' => 'one',
'2' => 'two',
'3' => 'three');

$arrayobject = new ArrayObject($array);

$iterator = $arrayobject->getIterator();

echo '<pre>';


while($iterator->valid()) {
    echo $iterator->key() . ' => ' . $iterator->current() . "\n";

    $iterator->next();
}
echo '<pre>';

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
    private $mainErrorList;
    private $fieldsErrorList;
    
    public function __construct($requiredFields, $nonRequiredFields){
        echo '<pre>';
        var_dump($_POST);
        echo '<pre>';
        $this->setRequiredFields($requiredFields);
        $this->setNonRequiredFields($nonRequiredFields);
        $this->verifyRequired($this->getRequiredFields(), $this->getFieldsErrorList());
        $this->verifyValues($this->getRequiredFields(), $this->getNonRequiredFields(), $this->getFieldsErrorList());
    }
    
    public function verifyRequired($reqList, $fieldErrList){
        //Pour chaque champs requis, dans la liste, si le $_POST[$champs] n'existe pas ou est null, on l'ajoute à la liste d'erreurs et on l'enleve de la liste des valeurs à contrôler 

        echo '-------------------------------------------------------------------verifyRequired()------------------------------------------------------------<br/>';
        foreach ($reqList as $field => $value){//Pour chaque champs requis du formulaire
            echo $field.' testé<br/>';//test
            if (!isset($_POST[$field]) || empty($_POST[$field])) {//Si la donnée n'existe pas
                echo $field.' pas bon<br/>';
                $fieldErrList[$field] = self::REQUIRED_ERROR;
                unset($reqList[$field]);
            }
        }
        echo '<br/>Liste des erreurs :<br/>';//test
        foreach ($fieldErrList as $key => $value) {//test
            echo 'Clé : '.$key.' | Valeur : '.$value.'<br/>';
        }
        echo '<br/>Liste des champs requis restant à controler :<br/>';//test
        foreach ($reqList as $key => $value) {//test
            echo 'Clé : '.$key.' | Valeur : '.$value.'<br/>';
        }
        $this->setRequiredFields($reqList);
        $this->setFieldsErrorList($fieldErrList);
    }


    public function verifyValues($reqList, $nonReqList, $fieldErrList){
        //pour chaque champs $field de $requiredFields, verifier si !preg_match(regexList[$field][regex]) > ajout de $fieldsErrorList[$field] = NOM_ERROR

        echo '-------------------------------------------------------------------verifyValues()------------------------------------------------------------<br/>';
        foreach ($reqList as $key => $value) {//test
            echo 'Clé : '.$key.' | Valeur : '.$value.'<br/>';
        }
        echo "<pre>";
        var_dump($reqList);//test
        var_dump($fieldErrList);//test
        echo "<pre>";

        foreach ($reqList as $field => $regName) {
            echo "-------------------------\$field :---------------------------<br/>";
            echo $field.'<br/>';
            if (!preg_match(FormControl::$regexList[$regName]['regex'], $_POST[$field])) {
                $fieldErrList[$field] = FormControl::$regexList[$regName]['error'];
            }
        }
        foreach ($nonReqList as $field => $regName) {
            echo "-------------------------\$field :---------------------------<br/>";
            echo $field.'<br/>';
            if (!preg_match(FormControl::$regexList[$regName]['regex'], $_POST[$field])) {
                $fieldErrList[$field] = FormControl::$regexList[$regName]['error'];
            }
        }
        echo '<br/>Liste des erreurs dans les champs :<br/>';//test
        if ($fieldErrList != null) {
            foreach ($fieldErrList as $key => $value) {//test
                echo 'Clé : '.$key.' | Valeur : '.$value.'<br/>';
            }
        }
        echo '<br/>Liste des erreurs principales :<br/>';//test
        if ($this->getMainErrorList() != null) {
            foreach ($this->getMainErrorList() as $key => $value) {//test
                echo 'Clé : '.$key.' | Valeur : '.$value.'<br/>';
            }
        } else {
            # code...
        }
    }
    
    public function arrayVerify($table){
        try {
            if (is_array($table)) {
                foreach ($table as $key => $value) {
                    if (!in_array($value, FormControl::$regexList)) {
                        //Si valeur ne se trouve pas dans la liste des regex disponibles.
                        throw new Error('Expression régulière introuvable : vérifier les tableaux passés au constructeur');
                    }
                }
            } elseif (condition) {
                //Si $table n'est pas un tableau
                throw new Error('N\'est pas un tableau');
            } else {
                return true;
            }
        } catch (Exception $e) {
            array_push($this->getMainErrorList(), $e->getMessage());
            //TODO : retour a la page du formulaire !!!!
        } finally {
            return false;
        }

        /*
        try {
            if (is_array($table)) {//Si $table est un tableau
                foreach ($table as $key => $value){
                    if (!in_array($value, FormControl::$regexList)) {
                        //Si valeur ne se trouve pas dans la liste des regex disponibles.
                        throw new Error('Expression régulière introuvable : vérifier les tableaux passés au constructeur');
                    }
                }
            } elseif {
                //Si $table n'est pas un tableau
                throw new Error($table.' N\'est pas un tableau');
            } else {
                return true;
            }
        } catch (Exception $e) {
            array_push($this->getMainErrorList(), $e->getMessage());
            //TODO : retour a la page du formulaire !!!!
        } finally {
            return false;
        }*/
        
    }
    
    public function getRequiredFields(){
        return $this->requiredFields;
    }
    
    public function setRequiredFields($tableau){//VERIFIER
        $this->requiredFields = $tableau;
    }
    
    public function getNonRequiredFields(){
        return $this->nonRequiredFields;
    }
    
    public function setNonRequiredFields($tableau){//VERIFIER
        $this->nonRequiredFields = $tableau;
    }
    
    public function getMainErrorList() {
        return $this->mainErrorList;
    }
    
    public function setMainErrorList($tableau) {//VERIFIER
        $this->mainErrorList = $tableau;
    }
    
    public function getFieldsErrorList() {
        return $this->fieldsErrorList;
    }
    
    public function setFieldsErrorList($tableau) {//VERIFIER
        $fieldsErrorList = $tableau;
    }
}
