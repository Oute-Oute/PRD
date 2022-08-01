/**
 * METTRE DANS CE FICHIER TOUTES LES METHODS DE ADD PATHWAY 
 * POUR DISSOCIER DE pathway.js
 */


/**
 * Called at the loading of the "add pathway" page 
 */
document.addEventListener('DOMContentLoaded', () => {
    SELECT_ID = 0;

    HUMAN_RESOURCE_CATEGORIES = JSON.parse(
        document.getElementById("json-human-resource-categories").value
    );

    MATERIAL_RESOURCE_CATEGORIES = JSON.parse(
        document.getElementById("json-material-resource-categories").value
    );

    initActivity()
    handleHumanButton()
    fillActivityList()

    // On cherche a définir la taille de notre div contenant la liste des activités :
    let heightTitle = document.getElementById('title-height').offsetHeight
    let heightCreationDiv = document.getElementById('create-activity-container').offsetHeight
    // 20px pour le padding de 10 en haut et en bas
    heightCreationDiv = heightCreationDiv - heightTitle - 20
    document.getElementById('list').style.height = heightCreationDiv + 'px'
})