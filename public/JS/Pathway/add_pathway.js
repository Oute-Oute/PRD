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

    let heightTitle = document.getElementById('name').offsetHeight
    let heightCreationDiv = document.getElementById('create-activity-container').offsetHeight
    heightCreationDiv = heightCreationDiv - heightTitle
    document.getElementById('list').style.height = heightCreationDiv + 'px'
})