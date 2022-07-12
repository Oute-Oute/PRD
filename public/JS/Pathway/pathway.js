var SELECT_ID = 0;
var NB_ACTIVITY = 0;


var HUMAN_RESOURCE_CATEGORIES
var MATERIAL_RESOURCE_CATEGORIES
var RESOURCES_BY_ACTIVITIES = new Array()


/**
 * Appelée au chargement de la page de création d'un parcours (circuit)
 */
document.addEventListener('DOMContentLoaded', () => {
    SELECT_ID = 0;
    //showNewModalForm()
    HUMAN_RESOURCE_CATEGORIES = JSON.parse(
        document.getElementById("json-human-resource-categories").value
    );

    MATERIAL_RESOURCE_CATEGORIES = JSON.parse(
        document.getElementById("json-material-resource-categories").value
    );

    console.log('oui')
    let modals = document.getElementsByClassName('modal-dialog')
    let len = modals.length
    console.log(len)
    for (let i = 0; i < len; i++) {
        modals[i].style.maxWidth = '70%'
    }
})


/**
 * Permet d'afficher la fenêtre modale d'informations
 */
function showInfosPathway(id, name) {
    document.getElementById('pathway-id').innerText = id;
    document.getElementById('pathway-name').innerText = name;
    $('#infos-pathway-modal').modal("show");
    

}

function showResourcesEditing() {

    let div = createDivEdit(0)

    let divres = document.getElementById('resources-editing')
    divres.appendChild(div)
}

/**
 * Permet d'afficher la fenêtre modale d'ajout
 */
function showNewModalForm(){
    console.log("non")
    $('#add-pathway-modal').modal("show");
    //$('#add-pathway-resources-modal').modal("show");
    //document.getElementById('add-pathway-resources-modal').style.display = 'flex'

}

/**
 * Permet de fermer la fenêtre modale d'ajout
 */
function hideNewModalForm() {
    $('#add-pathway-modal').modal("hide");
}

function disableSubmit() {
    let btnSubmit = document.getElementById('submit')
    btnSubmit.disabled=true
}

/**
 * Permet d'ajouter une liste déroulante pour choisir une activité lors de la cration d'un parcours (pathway)
 */
function handleAddActivity() {

    RESOURCES_BY_ACTIVITIES.push( new Object())
    RESOURCES_BY_ACTIVITIES[SELECT_ID].humanResourceCategories = new Array()
    RESOURCES_BY_ACTIVITIES[SELECT_ID].materialResourceCategories = new Array()
    RESOURCES_BY_ACTIVITIES[SELECT_ID].available = true

    NB_ACTIVITY = NB_ACTIVITY + 1;
    document.getElementById('nbactivity').value = NB_ACTIVITY

    disableSubmit();
    
    // Création d'une div qui contient les inputs pour le nom de l'activité la durée et le btn de suppression
    let div = document.createElement("div")
    div.setAttribute('class', 'form-field')    
    div.setAttribute('id', 'activity-field-'+SELECT_ID)

    let inputName = document.createElement('input')
    inputName.setAttribute('class', 'input-name')
    inputName.setAttribute('id', 'input-activity-name-'+SELECT_ID)
    //inputName.disabled = true
    inputName.setAttribute('placeholder', 'Nom')
    inputName.setAttribute('onchange', 'disableSubmit()')
    //inputName.setAttribute('name', 'name-activity-'+SELECT_ID)

    let inputDuration = document.createElement('input')
    inputDuration.setAttribute('class', 'input-duration')
    inputDuration.setAttribute('placeholder', 'Durée (min)')
    inputDuration.setAttribute('type', 'number')
    inputDuration.setAttribute('min', '0')
    inputDuration.setAttribute('onchange', 'disableSubmit()')
    inputDuration.setAttribute('id', 'input-activity-duration-'+SELECT_ID)

    //inputDuration.setAttribute('name', 'duration-activity-'+SELECT_ID)

    let imgEdit = new Image();
    imgEdit.src = '../img/edit.svg'
    imgEdit.setAttribute('id','img-'+SELECT_ID)
    //imgEdit.setAttribute('onclick', 'showResourcesEditing(this.id)')
    imgEdit.setAttribute('onclick', 'editSelect(this.id)')
    imgEdit.setAttribute('title', 'Éditer les ressources de l\'activité')

    let imgDelete = new Image();
    imgDelete.src = '../img/delete.svg'
    imgDelete.setAttribute('id','img-'+SELECT_ID)
    imgDelete.setAttribute('onclick', 'deleteSelect(this.id)')
    imgDelete.setAttribute('title', 'Supprimer l\'activité du parcours')

    div.appendChild(inputName)
    div.appendChild(inputDuration)
    div.appendChild(imgEdit)
    div.appendChild(imgDelete)

    // On l'affiche et on l'ajoute a la fin de la balise div activities-container
    //select.style.display = "block";
    let divAddActivity = document.getElementsByClassName('activities-container')[0]
    divAddActivity.setAttribute('id', 'activities-container-'+SELECT_ID)
    //let divAddActivity = document.getElementsByClassName('activities-container')

    //divAddActivity.

    let divcontainer = document.createElement('div')
    //divcontainer.setAttribute('class', "title-container")
    //divcontainer.setAttribute('class', 'flex-row')
    divcontainer.style.justifyContent = "center"
    let pTitle = document.createElement("p")
    pTitle.innerHTML = 'Activité : '
    pTitle.setAttribute('class', 'label')
    //let divclass = divcontainer.getAttribute('class')  //ajouter la classe 'div-activity-(id)' en plusde activity-field a div
    divcontainer.setAttribute('id', 'div-activity-'+SELECT_ID)
    divcontainer.appendChild(pTitle)
    divcontainer.appendChild(div)
    //divcontainer.appendChild(createDivEdit()) /* div edit  */

    //let divEdit = document.createElement('div')
    //divEdit.setAttribute('id', 'div-edit-activity-'+SELECT_ID)
    //divEdit.appendChild(createDivEdit())
    //divcontainer.appendChild(createDivEdit())
    let div_res_edit = document.getElementById('resources-editing')
    //console.log(div_res_edit)
    //console.log(divEdit)
    div_res_edit.appendChild(createDivEdit())

 //   divcontainer.appendChild(divEdit)

    divAddActivity.appendChild(divcontainer)

    // On appelle la methode : pour afficher la liste de ressources humaines par défaut 
    handleHumanButton('bh-'+SELECT_ID)

    SELECT_ID++
} 


/**
 * Permet de supprimer un select dans la liste déroulante 
 * @param {*} id : img-0, img-1
 */
function deleteSelect(id) {
    disableSubmit();

    // On récupère le numero de la div a supprimer  
    // Pour cela on recupere que le dernier caracetere de l'id de l'img : (img-1)
    id = id[id.length - 1] 
    // On peut donc recuperer la div
    let divToDelete = document.getElementById('div-activity-'+id)
    // puis la supprimer
    let divAddActivity = document.getElementsByClassName('activities-container')[0]
    divAddActivity.removeChild(divToDelete)
    
    // On actusalise l'input qui contient le nb d'activité
    NB_ACTIVITY = NB_ACTIVITY - 1;
    document.getElementById('nbactivity').value = NB_ACTIVITY

    RESOURCES_BY_ACTIVITIES[id].available = false
    //SELECT_ID = SELECT_ID - 1;
}

/**
 * Permet de modifier une activité  
 * @param {*} id : XXX-0, XXX-1
 */
function editSelect(id) {
    disableSubmit();

    // On récupère le numero de la div a supprimer  
    // Pour cela on recupere que le dernier caracetere de l'id de l'img : (img-1)
    id = id[id.length - 1] 
    // On peut donc recuperer la div
    //let divToEdit = document.getElementsByClassName('div-activity-'+id)[0]
    let divToEdit = document.getElementById('div-edit-activity-'+id)

    if (divToEdit.style.display == 'flex') {
        divToEdit.style.display = 'none'

        let divField = document.getElementById('activity-field-'+id)
        divField.style.borderBottomLeftRadius = '10px'
        divField.style.borderBottomRightRadius = '10px'
    } else {
        divToEdit.style.display = 'flex';

        let divField = document.getElementById('activity-field-'+id)  
        divField.style.borderBottomLeftRadius = '0px'
        divField.style.borderBottomRightRadius = '0px'
    }

}

/**
 * Permet de verifier les champs et de leur donner un 'name' pour la requete
 */
function verifyChanges() {
    let formOk = true
    // D'abord on recupere la div qui contient toutes les activity
    let activitiesContainer = document.getElementsByClassName('activities-container')[0]

    // On parcours toutes nos activités 
    // On set leur 'name' et on verifie leurs contenus
    let indexActivityAvailable = 0;
    for (let i = 0; i < RESOURCES_BY_ACTIVITIES.length; i++) {

        // On ne considere que les activités qui n'ont pas été supprimées
        if (RESOURCES_BY_ACTIVITIES[i].available === true) {
            inputName  = document.getElementById('input-activity-name-'+i)
            inputDuration  = document.getElementById('input-activity-duration-'+i)
            RESOURCES_BY_ACTIVITIES[i].activityname = inputName.value
            RESOURCES_BY_ACTIVITIES[i].activityduration = inputDuration.value

            indexActivityAvailable = indexActivityAvailable + 1
        }

        /*activitiesContainer.children[i].children[1].children[0].setAttribute('name', 'name-activity-'+ Number(i))
        let name = activitiesContainer.children[i].children[1].children[0].value
        activitiesContainer.children[i].children[1].children[1].setAttribute('name', 'duration-activity-'+Number(i))
        let duration = activitiesContainer.children[i].children[1].children[1].value

        // On verifie les inputs 
        if (name === '') {
            formOk = false
        }
        if (duration === '') {
            formOk = false
        }
        if (Number(duration) < 0 ) {
            formOk = false
        }*/

    }

    /*if (document.getElementById('pathwayname').value === '') {
        formOk = false
    }
*/

    document.getElementById('json-resources-by-activities').value = JSON.stringify(RESOURCES_BY_ACTIVITIES);

    if (formOk) {
        let btnSubmit = document.getElementById('submit')
        btnSubmit.disabled = false;
    }
}



function createDivEdit() {

    /* Div parent pour l'ajout de ressource */
    divEditActivity = document.createElement('div')
    divEditActivity.setAttribute('class', 'div-edit-activities')
    divEditActivity.setAttribute('id', 'div-edit-activity-'+SELECT_ID)
    divEditActivity.style.display = 'none'
    //divEditActivity.style.height = '0px'

    /* Premier enfant : les 2 boutons pour choisir materielles humaines */

    divBtnsResources = document.createElement('div')
    divBtnsResources.setAttribute('class', 'div-buttons-resources')
    btnHuman = document.createElement('button')
    btnHuman.innerText = 'Humaines'
    btnHuman.setAttribute('type', 'button')
    btnHuman.setAttribute('id', 'bh-'+SELECT_ID)
    btnHuman.setAttribute('onclick', 'handleHumanButton(this.id)')

    btnMaterial = document.createElement('button')
    btnMaterial.innerText = 'Materielles'
    btnMaterial.setAttribute('type', 'button')
    btnMaterial.setAttribute('id', 'bm-'+SELECT_ID)
    btnMaterial.setAttribute('onclick', 'handleMaterialButton(this.id)')

    RESOURCES_BY_ACTIVITIES[SELECT_ID].btnHM= 'human'

    divBtnsResources.appendChild(btnHuman)
    divBtnsResources.appendChild(btnMaterial)


    /* Deuxieme enfant : Div qui contiendra la liste des ressources */

    divResources = document.createElement('div')
    divResources.setAttribute('class', 'div-resources')
    //divRH = document.createElement('div')
    //divRH.setAttribute('class', 'div-resources-h')
    divRM = document.createElement('div')
    //divRM.setAttribute('class', 'div-resources-m')
    //divResources.appendChild(divRH)
    ul = document.createElement('ul')
    ul.setAttribute('id', 'list-resources-'+SELECT_ID)

    divRM.appendChild(ul)

    divResources.appendChild(divRM)


    /* Troisieme enfant : select  */

    divAddResources = document.createElement('div')
    divAddResources.setAttribute('class', 'div-add-resources')
    divAddResources.setAttribute('title', 'Choisissez la ressource à ajouter')
    selectResources = document.createElement('select')
    selectResources.setAttribute('id', 'select-resources-'+SELECT_ID)
    //for (let indexHR = 0; )

    inputNbResources = document.createElement('input')
    inputNbResources.setAttribute('type', 'number')
    inputNbResources.setAttribute('title', 'Entrer le nombre de ressources à ajouter')
    inputNbResources.setAttribute('placeholder', 'Qte')
    inputNbResources.setAttribute('id', 'resource-nb-'+SELECT_ID)
    //title="Enter input here"
    btnPlus = document.createElement('button')
    btnPlus.setAttribute('type', 'button')
    btnPlus.innerHTML = '+'
    btnPlus.setAttribute('title', 'Ajouter la ressource a la liste')
    btnPlus.setAttribute('id', 'btn-'+SELECT_ID)
    btnPlus.setAttribute('onclick', 'addResources(this.id)')
    
    divAddResources.appendChild(selectResources)
    divAddResources.appendChild(inputNbResources)
    divAddResources.appendChild(btnPlus)

    /* Ajout de tous les enfants a la div parent */
    divEditActivity.appendChild(divBtnsResources)
    divEditActivity.appendChild(divResources)
    divEditActivity.appendChild(divAddResources)

    //

    return divEditActivity
}


function addResources(id) {
    // recuperation de l'id
    id = id[id.length - 1] 

    // ! Si le bouton human est activé !
    if (RESOURCES_BY_ACTIVITIES[id].btnHM == 'human') {
        let resourceNb = document.getElementById('resource-nb-'+id).value
        let resourceId = document.getElementById('select-resources-'+id).value //pas utilisé pour l'instant

        let resourceName ='';
        for (let indexHRC = 0; indexHRC < HUMAN_RESOURCE_CATEGORIES.length; indexHRC++) {
            if (HUMAN_RESOURCE_CATEGORIES[indexHRC].id == resourceId) {
                resourceName = HUMAN_RESOURCE_CATEGORIES[indexHRC].categoryname
            }
        }

        RESOURCES_BY_ACTIVITIES[id].humanResourceCategories.push(new Object())
        let len = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories.length
        RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[len-1].id = resourceId
        RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[len-1].name = resourceName
        RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[len-1].nb = resourceNb
    
        fillHRCList(id)
    } else {
        // ! Si le bouton material est activé !

        let resourceNb = document.getElementById('resource-nb-'+id).value
        let resourceId = document.getElementById('select-resources-'+id).value //pas utilisé pour l'instant

        let resourceName ='';
        for (let indexMRC = 0; indexMRC < MATERIAL_RESOURCE_CATEGORIES.length; indexMRC++) {
            if (MATERIAL_RESOURCE_CATEGORIES[indexMRC].id == resourceId) {
                resourceName = MATERIAL_RESOURCE_CATEGORIES[indexMRC].categoryname
            }
        }

        RESOURCES_BY_ACTIVITIES[id].materialResourceCategories.push(new Object())
        let len = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories.length
        RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[len-1].id = resourceId
        RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[len-1].name = resourceName
        RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[len-1].nb = resourceNb
    
        fillMRCList(id)
    }


}

/**
 * Remplit la liste des ressources humaines 
 * @param {id de l'activité dans laquelle on veut ajouter des ressources} id 
 */
function fillHRCList(id) {

    
    // On recupere la liste dans laquelle on va ajouter notre ressource
    ul = document.getElementById('list-resources-'+id)
    ul.style.listStyle='none'
    ul.innerHTML = ''

    let len = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories.length

    if (len > 0) {
        for (let indexHRC = 0 ; indexHRC < len ; indexHRC++) {
            // On crée le li qui va stocker la ressource (visuellement) 
            var li = document.createElement('li');
            let resourceNb = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[indexHRC].nb 
            let resourceName = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[indexHRC].name
            li.innerText = resourceName +' ('+resourceNb+')'
        

            let imgDelete = new Image();
            imgDelete.src = 'img/delete.svg'
            imgDelete.setAttribute('onclick', 'deleteResource(this.id)')
            imgDelete.setAttribute('title', 'Supprimer la ressource')
            imgDelete.style.width='20px'
            imgDelete.style.marginRight = '10%'
            imgDelete.setAttribute('id', 'resource-h-'+id+'-'+indexHRC)

            div = document.createElement('div')
            div.appendChild(imgDelete)
            div.appendChild(li)
            div.style.display = 'flex'
            div.style.alignItems = 'center'

            ul.appendChild(div)
        }
    } else {
        var li = document.createElement('li');
        li.innerText = 'Aucune ressource humaine pour le moment !'
        ul.appendChild(li)
    }


}


/**
 * Remplit la liste des ressources humaines 
 * @param {id de l'activité dans laquelle on veut ajouter des ressources} id 
 */
function fillMRCList(id) {
    
    // On recupere la liste dans laquelle on va ajouter notre ressource
    ul = document.getElementById('list-resources-'+id)
    ul.innerHTML = ''

    let len = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories.length

    if (len > 0) {
        for (let indexMRC = 0 ; indexMRC < len ; indexMRC++) {
            // On crée le li qui va stocker la ressource (visuellement) 
            var li = document.createElement('li');
    
            let resourceNb = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[indexMRC].nb 
            let resourceName = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[indexMRC].name
            li.innerText = resourceName +' ('+resourceNb+')'
        
            let imgDelete = new Image();
            imgDelete.src = 'img/delete.svg'
            imgDelete.setAttribute('onclick', 'deleteResource(this.id)')
            imgDelete.setAttribute('title', 'Supprimer la ressource')
            imgDelete.style.width='20px'
            imgDelete.style.marginRight = '10%'
            imgDelete.setAttribute('id', 'resource-m-'+id+'-'+indexMRC)

            div = document.createElement('div')
            div.appendChild(imgDelete)
            div.appendChild(li)
            div.style.display = 'flex'
            div.style.alignItems = 'center'

            ul.appendChild(div)
        }
    } else {
        var li = document.createElement('li');
        li.innerText = 'Aucune ressource materielles pour le moment !'
        ul.appendChild(li)
    }
}

/**
 * Gestion du clic sur le bouton 'humaines' dans les ressources d'une activité
 * @param {id de l'activité donc on veut afficher les ressources humaines} id 
 */
function handleHumanButton(id) {
    // recuperation de l'id
    id = id[id.length - 1] 
    
    // mise en place du style pour le menu selectionné (Humaines ou Materielles)
    let bh = document.getElementById('bh-'+id)
    bh.style.textDecoration = 'underline'
    bh.style.fontWeight = '700'

    // mise en place du style pour le menu non selectionné (Humaines ou Materielles)
    let bm = document.getElementById('bm-'+id)
    bm.style.textDecoration = 'none'
    bm.style.fontWeight = 'normal'

    // remplissage du select avec les données de la bd
    let select = document.getElementById('select-resources-'+id)
    removeOptions(select)

    for (let indexHR = 0; indexHR < HUMAN_RESOURCE_CATEGORIES.length; indexHR++) {
        option = document.createElement('option')
        option.value = HUMAN_RESOURCE_CATEGORIES[indexHR].id
        option.text = HUMAN_RESOURCE_CATEGORIES[indexHR].categoryname
        select.appendChild(option)
    }
    
    // human / material
    RESOURCES_BY_ACTIVITIES[id].btnHM = 'human'

    fillHRCList(id)
}


/**
 * Gestion du clic sur le bouton 'materielle' dans les ressources d'une activité
 * @param {id de l'activité donc on veut afficher les ressources materielles} id 
 */
function handleMaterialButton(id) {
    // recuperation de l'id
    id = id[id.length - 1] 

    // mise en place du style pour le menu selectionné (Humaines ou Materielles)
    let bm = document.getElementById('bm-'+id)
    bm.style.textDecoration = 'underline'
    bm.style.fontWeight = '700'

    // mise en place du style pour le menu non selectionné (Humaines ou Materielles)
    let bh = document.getElementById('bh-'+id)
    bh.style.textDecoration = 'none'
    bh.style.fontWeight = 'normal'
 
    // remplissage du select avec les données de la bd
    let select = document.getElementById('select-resources-'+id)
    removeOptions(select)

    for (let indexMR = 0; indexMR < MATERIAL_RESOURCE_CATEGORIES.length; indexMR++) {
        option = document.createElement('option')
        option.value = MATERIAL_RESOURCE_CATEGORIES[indexMR].id
        option.text = MATERIAL_RESOURCE_CATEGORIES[indexMR].categoryname
        select.appendChild(option)
    }

    // human / material
    RESOURCES_BY_ACTIVITIES[id].btnHM = 'material'
    fillMRCList(id)
}


/**
 * Supprime tous les options d'un select
 * @param {L'élément select dont on veut supprimer les options} selectElement 
 * 
 * Source: https://prograide.com/pregunta/37784/comment-effacer-toutes-les-options-dune-liste-deroulante
 */
function removeOptions(selectElement) {
    var i, L = selectElement.options.length - 1;
    
    for (i = L; i >= 0; i--) {
        selectElement.remove(i); 
    } 

}

/**
 * Permet de supprimer une ressource d'une activité
 * @param {*} id 
 */
function deleteResource(id) {

    idSplitted = id.split('-');
    typeRessource = idSplitted[idSplitted.length - 3]
    idActivity = idSplitted[idSplitted.length - 2]
    idRessource = idSplitted[idSplitted.length - 1]


    //console.log(RESOURCES_BY_ACTIVITIES)
    if (typeRessource === 'h') {
        RESOURCES_BY_ACTIVITIES[idActivity].humanResourceCategories.splice(idRessource, 1)
        fillHRCList(idActivity);
    } else {
        RESOURCES_BY_ACTIVITIES[idActivity].materialResourceCategories.splice(idRessource, 1)
        fillMRCList(idActivity);
    }
    //console.log(RESOURCES_BY_ACTIVITIES)

}