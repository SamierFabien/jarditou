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