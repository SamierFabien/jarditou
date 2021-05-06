//regex courantes
const NOM = /^[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ](['-\s])?[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+(['-\s][a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+)?$/;
const CODEPOSTAL = /^[0-9]{5}$/;
const TELEPHONE = /^[0-9]{10}$|[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}([\s-./])?[0-9]{2}/;
const EMAIL = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
const DATE = /^[0-9]{4}[\s-/][0-9]{2}[\s-/][0-9]{2}$|[0-9]{2}[\s-/][0-9]{2}[\s-/][0-9]{4}$/;

/**
 * Fonction qui prend en charge une liste d'éléments.
 * Si élément requis pas renseigné : appel fonctionErreur
 * Sinon : appel fonctionNettoyer
 * @param {Object} elements Un tableau ou le resultat d'un querySelectorAll
 * @param {function} fonctionErreur Si élément requis vide, fonctionErreur(elements[courant]).
 * @param {function} fonctionNettoyer Si élément requis renseigné, fonctionNettoyer(elements[courant]).
 * @param {Event} e Reférence à Event pour empêcher l'action du formulaire si l'élément requis n'est pas renseigné.
 * @returns Array un tableau 
 */
function elementsRequisVides(elements, fonctionErreur, fonctionNettoyer, e) {
    let tableau = [];//copie des éléments renseignés requis restants dans un tableau car si elements est un nodelist, impossible a modifier.
    for (let i = 0; i < elements.length; i++) {
        if (elements[i].validity.valueMissing) {
            e.preventDefault();
            fonctionErreur(elements[i]);
        } else {
            tableau.push(elements[i]);
            fonctionNettoyer(elements[i]);
        }
    }
    return tableau;
}

/**
 * Teste si element est dans listeElements.
 * Si oui, on teste element avec regex.
 * Si teste regex pas bon, fonction(element).
 * @param {Element} element L'élément qu'on veut tester.
 * @param {Regex} regex Regex de test.
 * @param {Object} tableau Tableau ou nodeList des éléments renseignés et que l'on veut tester.
 * @param {function} fonction Si "element" différent de regex, appel de fonction[element]
 * @param {Event} e Reférence à Event pour empêcher l'action du formulaire si l'élément requis n'est pas renseigné.
 */
function formatCorrect(element, regex, listeElements, fonction, e) {
    for (const iterateur of listeElements) {
        if (element === iterateur) {
            if (regex.test(iterateur.value) == false) {
                e.preventDefault();
                fonction(iterateur);
            } 
        }
    }
}

/**
 * Fonction de "remise à zéro" de la balise d'affichage d'erreur.
 * Pour #element, #element-erreur pas de de texte et pas de class
 * @param {Element} element Un élément du DOM
 */
function nettoyer(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "";
    document.getElementById(element).className = "";
}

/**
 * Fonction d'affichage d'erreur pour élément requis pas renseigné.
 * Pour #element, #element-erreur texte "champs obligatoire" et design bootstrap via class
 * @param {Element} element Un élément du DOM
 */
function requis(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "Champs obligatoire.";
    document.getElementById(element).className = "btn alert-danger w-100 mt-1";
}

/**
 * Fonction d'affichage d'erreur si test de regex NOM est faux.
 * Pour #element, #element-erreur texte "mauvais format (...)" et design bootstrap via class
 * @param {Element} element Un élément du DOM
 */
function mauvaisFormatDeNom(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "Mauvais format. Exemples valides : 'Dupont', 'dupont, 'Jean Claude', 'jean claude', 'Jean-Claude', 'N'Bekele'.";
    document.getElementById(element).className = "btn alert-warning w-100 mt-1";
}

/**
 * Fonction d'affichage d'erreur si test de regex EMAIL est faux.
 * Pour #element, #element-erreur texte "mauvais format (...)" et design bootstrap via class
 * @param {Element} element Un élément du DOM
 */
 function mauvaisFormatDeDate(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "Mauvais format. Formats supporté : '00/00/0000' '0000/00/00' avec des slashs, espaces et tirets comme séparateurs.";
    document.getElementById(element).className = "btn alert-warning w-100 mt-1";
}

/**
 * Fonction d'affichage d'erreur si test de regex EMAIL est faux.
 * Pour #element, #element-erreur texte "mauvais format (...)" et design bootstrap via class
 * @param {Element} element Un élément du DOM
 */
function mauvaisFormatDeMail(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "Mauvais format. Format supporté : 'moi@exemple.com'.";
    document.getElementById(element).className = "btn alert-warning w-100 mt-1";
}

/**
 * Fonction d'affichage d'erreur si test de regex CODEPOSTAL est faux.
 * Pour #element, #element-erreur texte "mauvais format (...)" et design bootstrap via class
 * @param {Element} element Un élément du DOM
 */
function mauvaisFormatDeCP(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "Mauvais format : composé de 5 chiffres uniquement.";
    document.getElementById(element).className = "btn alert-warning w-100 mt-1";
}


/**
 * Fonction lancée au clic sur le bouton "envoyer" du formulaire "contactEnvoyer".
 * Vérifie si les éléments requis sont bien renseignés et ensuite les données renseignées par des regex.
 */
let validation = document.getElementById("contactEnvoyer").addEventListener("click", function (e) {
    let elementsAVerifier = document.querySelectorAll("#formulaireContact input, #formulaireContact select, #formulaireContact textarea");
    let elementsRemplis = elementsRequisVides(elementsAVerifier, requis, nettoyer, e);
    formatCorrect(document.getElementById("nom"), NOM, elementsRemplis, mauvaisFormatDeNom, e);
    formatCorrect(document.getElementById("prenom"), NOM, elementsRemplis, mauvaisFormatDeNom, e);
    formatCorrect(document.getElementById("naissance"), DATE, elementsRemplis, mauvaisFormatDeDate, e)
    formatCorrect(document.getElementById("email"), EMAIL, elementsRemplis, mauvaisFormatDeMail, e);
    formatCorrect(document.getElementById("cp"), CODEPOSTAL, elementsRemplis, mauvaisFormatDeCP, e);
});