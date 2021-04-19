//regex les plus courantes
const NOM = /^[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ](['-\s])?[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+(['-\s][a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöŒœŨũŰűùúûüýÿŶŷŸ][a-zàáâãäåæçèéêëìíîïñòóôõöœũűùúûüýÿŷ]+)?$/;
const CODEPOSTAL = /^[0-9]{5, 5}$/;
const TELEPHONE = /^[0-9]{10}$/;
const EMAIL = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

let validation = document.getElementById("envoyer");
validation.addEventListener("click", valider);

function valider(e) {
    let elementsAVerifier = [
        document.getElementById("nom"),
        document.getElementById("prenom"),
        document.getElementById("genre"),
        document.getElementById("naissance"),
        document.getElementById("cp"),
        document.getElementById("adresse"),
        document.getElementById("ville"),
        document.getElementById("email"),
        document.getElementById("sujet"),
        document.getElementById("question"),
        document.getElementById("accord")
    ];
    let elementsRemplis = elementsRequisVides(elementsAVerifier, requis, nettoyer, e);
    formatCorrect("nom", NOM, elementsRemplis, mauvaisFormatDeNom, e);
    formatCorrect("prenom", NOM, elementsRemplis, mauvaisFormatDeNom, e);
    formatCorrect("email", EMAIL, elementsRemplis, mauvaisFormatDeMail, e);
    formatCorrect("cp", CODEPOSTAL, elementsRemplis, mauvaisFormatDeCP, e);
}

/**
 * Fonction qui prend en charge une liste d'éléments.
 * Si élément requis pas renseigné : appel fonctionErreur
 * Sinon : appel fonctionNettoyer
 * @param {Array, NodeList} elements Un tableau ou le resultat d'un querySelectorAll
 * @param {function} fonctionErreur Si élément requis vide, fonctionErreur(elements[courant]).
 * @param {function} fonctionNettoyer Si élément requis renseigné, fonctionNettoyer(elements[courant]).
 * @param {Event} e Reférence à Event pour empêcher l'action du formulaire si l'élément requis n'est pas renseigné.
 * @returns 
 */
function elementsRequisVides(elements, fonctionErreur, fonctionNettoyer, e) {
    //Pour tout "élément" marqué "required" vide, on lance "fonctionErreur" sinon "fonctionNettoyer".
    let tableau = [];//copie des éléments non vides restants dans tableau car si elements est un nodelist, impossible a modifier.
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

function formatCorrect(id, regex, tableau, fonction, e) {
    for (const iterateur of tableau) {
        if (document.getElementById(id) === iterateur) {
            if (regex.test(iterateur.value) == false) {
                e.preventDefault();
                fonction(iterateur);
            } 
        }
    }
}

function nettoyer(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "";
    document.getElementById(element).className = "";
}

function requis(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "Champs obligatoire";
    document.getElementById(element).className = "btn btn-primary bg-danger w-100 mt-1";
}

function mauvaisFormatDeNom(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "Mauvais format : uniquement des lettres dont la première est en majuscule éventuellement";
    document.getElementById(element).className = "btn btn-primary bg-warning w-100 mt-1";
}

function mauvaisFormatDeMail(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "Mauvais format : moi@exemple.com";
    document.getElementById(element).className = "btn btn-primary bg-warning w-100 mt-1";
}

function mauvaisFormatDeCP(element) {
    element = element.id + "-erreur";
    document.getElementById(element).textContent = "Mauvais format : composé de 6 chiffres";
    document.getElementById(element).className = "btn btn-primary bg-warning w-100 mt-1";
}