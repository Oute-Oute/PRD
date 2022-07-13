var SELECT_ID = 0;
var NB_ACTIVITY = 0;


var HUMAN_RESOURCE_CATEGORIES
var MATERIAL_RESOURCE_CATEGORIES
var RESOURCES_BY_ACTIVITIES = new Array()
var ACTIVITY_IN_PROGRESS
var ID_ACTIVITY

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
    //addActivity()
    initActivity()
    handleHumanButton()
    fillActivityList()

})


/**
 * Permet d'afficher la fenêtre modale d'informations
 */
function showInfosPathway(id, name) {
    document.getElementById('pathway-id').innerText = id;
    document.getElementById('pathway-name').innerText = name;
    $('#infos-pathway-modal').modal("show");
    

}


/**
 * Permet d'afficher la fenêtre modale d'ajout
 */
function showNewModalForm(){
    //console.log("non")
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

function initActivity() {
    disableSubmit();

    ACTIVITY_IN_PROGRESS = new Object()
    ACTIVITY_IN_PROGRESS.humanResourceCategories = new Array()
    ACTIVITY_IN_PROGRESS.materialResourceCategories = new Array()
    ACTIVITY_IN_PROGRESS.available = true
    ACTIVITY_IN_PROGRESS.btnHM = 'human'
}

function addArray() {
    let len = RESOURCES_BY_ACTIVITIES.length

    RESOURCES_BY_ACTIVITIES[len] = new Object()
    RESOURCES_BY_ACTIVITIES[len].humanResourceCategories = new Array()
    RESOURCES_BY_ACTIVITIES[len].materialResourceCategories = new Array()
    RESOURCES_BY_ACTIVITIES[len].available = true


    for (let indexHR = 0; indexHR < ACTIVITY_IN_PROGRESS.humanResourceCategories.length; indexHR++) {
        let res = new Object();
        res.id = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHR].id
        res.name = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHR].name
        res.nb = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHR].nb
        RESOURCES_BY_ACTIVITIES[len].humanResourceCategories.push(res)
    }


    for (let indexMR = 0; indexMR < ACTIVITY_IN_PROGRESS.materialResourceCategories.length; indexMR++) {
        let res = new Object();
        res.id = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMR].id
        res.name = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMR].name
        res.nb = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMR].nb
        RESOURCES_BY_ACTIVITIES[len].materialResourceCategories.push(res)
    }
    RESOURCES_BY_ACTIVITIES[len].activityname = document.getElementById('input-name').value
    RESOURCES_BY_ACTIVITIES[len].activityduration = document.getElementById('input-duration').value
}

function addActivity() {

    disableSubmit();

    let verif = true

    // On verifie que tous les champs sont bons 
    if (document.getElementById('input-name').value == '') {
        verif = false
    }
    if (document.getElementById('input-duration').value == '') {
        verif = false
    }

    if (verif) {
        // ajout de l'activité au tableau
        addArray()
        NB_ACTIVITY = NB_ACTIVITY + 1;
        document.getElementById('nbactivity').value = NB_ACTIVITY

        // on reinitialise les champs 
        ACTIVITY_IN_PROGRESS = new Object()
        ACTIVITY_IN_PROGRESS.humanResourceCategories = new Array()
        ACTIVITY_IN_PROGRESS.materialResourceCategories = new Array()
        ACTIVITY_IN_PROGRESS.available = true
        ACTIVITY_IN_PROGRESS.btnHM = 'human'
        document.getElementById('input-name').value = ''
        document.getElementById('input-duration').value = ''
        handleHumanButton()

    } else {
        //afficher une message d'erreur
    }

    fillActivityList()

    //RESOURCES_BY_ACTIVITIES[SELECT_ID].btnHM = 'human'
    //let id = Number(RESOURCES_BY_ACTIVITIES.length - 1)
    //handleHumanButton()
}

function fillActivityList() {

    let divActivitiesList = document.getElementById('activities-list')
    divActivitiesList.innerHTML = ''
    let label = document.createElement('label')
    label.setAttribute('class', 'label')
    label.innerHTML = 'Listes des activités'
    divActivitiesList.appendChild(label)

    let indexActivityAvailable = 0
    for (let indexActivity = 0; indexActivity < RESOURCES_BY_ACTIVITIES.length; indexActivity++) {
        if (RESOURCES_BY_ACTIVITIES[indexActivity].available == true) {
            let activity = document.createElement('p')
            activity.innerHTML +=  'Activité '+Number(indexActivityAvailable+1) +' : '
            activity.innerHTML += RESOURCES_BY_ACTIVITIES[indexActivity].activityname
            activity.innerHTML += ' (' +RESOURCES_BY_ACTIVITIES[indexActivity].activityduration +'min)'
            divActivitiesList.appendChild(activity)
            indexActivityAvailable++
        }
    }

}

/**
 * Permet d'ajouter une liste déroulante pour choisir une activité lors de la cration d'un parcours (pathway)
 */
function handleAddActivity() {


    // Création d'une div qui contient les inputs pour le nom de l'activité la durée et le btn de suppression
    /*let div = document.createElement("div")
    div.setAttribute('class', 'form-field')    
    div.setAttribute('id', 'activity-field-'+SELECT_ID)*/

    /*let inputName = document.createElement('input')
    inputName.setAttribute('class', 'input-name')
    inputName.setAttribute('id', 'input-activity-name-'+SELECT_ID)
    //inputName.disabled = true
    inputName.setAttribute('placeholder', 'Nom')
    inputName.setAttribute('onchange', 'disableSubmit()')*/
    //inputName.setAttribute('name', 'name-activity-'+SELECT_ID)

  /*  let inputDuration = document.createElement('input')
    inputDuration.setAttribute('class', 'input-duration')
    inputDuration.setAttribute('placeholder', 'Durée (min)')
    inputDuration.setAttribute('type', 'number')
    inputDuration.setAttribute('min', '0')
    inputDuration.setAttribute('onchange', 'disableSubmit()')
    inputDuration.setAttribute('id', 'input-activity-duration-'+SELECT_ID)*/

    //inputDuration.setAttribute('name', 'duration-activity-'+SELECT_ID)

   /* let imgEdit = new Image();
    imgEdit.src = '../img/edit.svg'
    imgEdit.setAttribute('id','img-'+SELECT_ID)
    //imgEdit.setAttribute('onclick', 'showResourcesEditing(this.id)')
    imgEdit.setAttribute('onclick', 'editSelect(this.id)')
    imgEdit.setAttribute('title', 'Éditer les ressources de l\'activité')*/

   /* let imgDelete = new Image();
    imgDelete.src = '../img/delete.svg'
    imgDelete.setAttribute('id','img-'+SELECT_ID)
    imgDelete.setAttribute('onclick', 'deleteSelect(this.id)')
    imgDelete.setAttribute('title', 'Supprimer l\'activité du parcours')*/

   /* div.appendChild(inputName)
    div.appendChild(inputDuration)
    //div.appendChild(imgEdit)
    div.appendChild(imgDelete)*/

    // On l'affiche et on l'ajoute a la fin de la balise div activities-container
    //select.style.display = "block";
    let divAddActivity = document.getElementsByClassName('activities-container')[0]
    //divAddActivity.setAttribute('id', 'activities-container-'+SELECT_ID)
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
    //div_res_edit.appendChild(createDivEdit())

    divcontainer.appendChild(createDivEdit())

 //   divcontainer.appendChild(divEdit)

    divAddActivity.appendChild(divcontainer)

    // On appelle la methode : pour afficher la liste de ressources humaines par défaut 
    handleHumanButton()

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
    id = getId(id)
    
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
    fillActivityList()
}


var showedit = false;
var DIV_TO_EDIT_OLD
/**
 * Permet de modifier une activité  
 * @param {*} id : XXX-0, XXX-1
 */
function editSelect(id) {
    disableSubmit();

    document.getElementById('name').innerHTML = 'oui'
    //RESOURCES_BY_ACTIVITIES.length

    // On récupère le numero de la div a supprimer  
    // Pour cela on recupere que le dernier caracetere de l'id de l'img : (img-1)
    id = getId(id)

    // On peut donc recuperer la div
    //let divToEdit = document.getElementsByClassName('div-activity-'+id)[0]
    let divToEdit = document.getElementById('div-edit-activity-'+id)

    if (DIV_TO_EDIT_OLD == undefined) {
        divToEdit.style.display = 'flex'
        DIV_TO_EDIT_OLD = divToEdit
    } else {
        if (divToEdit != DIV_TO_EDIT_OLD) {
            divToEdit.style.display = 'flex'
            DIV_TO_EDIT_OLD.style.display = 'none'
            DIV_TO_EDIT_OLD = divToEdit
        }
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
    imgAdd = new Image()
    imgAdd.src = '../img/plus.png'
    imgAdd.setAttribute('id', 'img-')
    imgAdd.setAttribute('onclick', 'addResources('+SELECT_ID+')')
    imgAdd.setAttribute('title', 'Ajouter la ressource a la liste')
    imgAdd.style.width = '20px'
    imgAdd.style.height = '20px'
    //imgAdd.src = '../'
    btnPlus = document.createElement('button')
    btnPlus.setAttribute('type', 'button')
    btnPlus.innerHTML = '+'
    btnPlus.setAttribute('title', 'Ajouter la ressource a la liste')
    btnPlus.setAttribute('id', 'btn-'+SELECT_ID)
    btnPlus.setAttribute('onclick', 'addResources(this.id)')
    
    divAddResources.appendChild(selectResources)
    divAddResources.appendChild(inputNbResources)
    divAddResources.appendChild(imgAdd)
    
    /* Ajout de tous les enfants a la div parent */
    divEditActivity.appendChild(divBtnsResources)
    divEditActivity.appendChild(divResources)
    divEditActivity.appendChild(divAddResources)

    //

    return divEditActivity
}


function getId(str) {
    str = str.toString()
    id = str.split('-')
    return id[id.length - 1]
}

function addResources() {
    // recuperation de l'id
    //id = id[id.length - 1] 
    //id = getId(id)

    // ! Si le bouton human est activé !

    if (ACTIVITY_IN_PROGRESS.btnHM == 'human') {
        let resourceNb = document.getElementById('resource-nb').value
        let resourceId = document.getElementById('select-resources').value //pas utilisé pour l'instant

        let resourceName ='';
        for (let indexHRC = 0; indexHRC < HUMAN_RESOURCE_CATEGORIES.length; indexHRC++) {
            if (HUMAN_RESOURCE_CATEGORIES[indexHRC].id == resourceId) {
                resourceName = HUMAN_RESOURCE_CATEGORIES[indexHRC].categoryname
            }
        }

        ACTIVITY_IN_PROGRESS.humanResourceCategories.push(new Object())
        let len = ACTIVITY_IN_PROGRESS.humanResourceCategories.length
        ACTIVITY_IN_PROGRESS.humanResourceCategories[len-1].id = resourceId
        ACTIVITY_IN_PROGRESS.humanResourceCategories[len-1].name = resourceName
        ACTIVITY_IN_PROGRESS.humanResourceCategories[len-1].nb = resourceNb
    
        fillHRCList()
    } else {
        // ! Si le bouton material est activé !

        let resourceNb = document.getElementById('resource-nb').value
        let resourceId = document.getElementById('select-resources').value //pas utilisé pour l'instant

        let resourceName ='';
        for (let indexMRC = 0; indexMRC < MATERIAL_RESOURCE_CATEGORIES.length; indexMRC++) {
            if (MATERIAL_RESOURCE_CATEGORIES[indexMRC].id == resourceId) {
                resourceName = MATERIAL_RESOURCE_CATEGORIES[indexMRC].categoryname
            }
        }

        ACTIVITY_IN_PROGRESS.materialResourceCategories.push(new Object())
        let len = ACTIVITY_IN_PROGRESS.materialResourceCategories.length
        ACTIVITY_IN_PROGRESS.materialResourceCategories[len-1].id = resourceId
        ACTIVITY_IN_PROGRESS.materialResourceCategories[len-1].name = resourceName
        ACTIVITY_IN_PROGRESS.materialResourceCategories[len-1].nb = resourceNb
    
        fillMRCList()
    }


}

/**
 * Remplit la liste des ressources humaines 
 * @param {id de l'activité dans laquelle on veut ajouter des ressources} id 
 */
function fillHRCList() {

    
    // On recupere la liste dans laquelle on va ajouter notre ressource
    ul = document.getElementById('list-resources')
    ul.style.listStyle='none'
    ul.innerHTML = ''

    let len = ACTIVITY_IN_PROGRESS.humanResourceCategories.length

    if (len > 0) {
        for (let indexHRC = 0 ; indexHRC < len ; indexHRC++) {
            // On crée le li qui va stocker la ressource (visuellement) 
            var li = document.createElement('li');
            let resourceNb = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHRC].nb 
            let resourceName = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHRC].name
            li.innerText = resourceName +' ('+resourceNb+')'
        

            let imgDelete = new Image();
            imgDelete.src = '../img/delete.svg'
            imgDelete.setAttribute('onclick', 'deleteResource(this.id)')
            imgDelete.setAttribute('title', 'Supprimer la ressource')
            imgDelete.style.width='20px'
            imgDelete.style.marginRight = '10%'
            imgDelete.setAttribute('id', 'resource-h-'+indexHRC)

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
    ul = document.getElementById('list-resources')
    ul.innerHTML = ''

    let len = ACTIVITY_IN_PROGRESS.materialResourceCategories.length

    if (len > 0) {
        for (let indexMRC = 0 ; indexMRC < len ; indexMRC++) {
            // On crée le li qui va stocker la ressource (visuellement) 
            var li = document.createElement('li');
    
            let resourceNb = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMRC].nb 
            let resourceName = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMRC].name
            li.innerText = resourceName +' ('+resourceNb+')'
        
            let imgDelete = new Image();
            imgDelete.src = '../img/delete.svg'
            imgDelete.setAttribute('onclick', 'deleteResource(this.id)')
            imgDelete.setAttribute('title', 'Supprimer la ressource')
            imgDelete.style.width='20px'
            imgDelete.style.marginRight = '10%'
            imgDelete.setAttribute('id', 'resource-m-'+indexMRC)

            div = document.createElement('div')
            div.appendChild(imgDelete)
            div.appendChild(li)
            div.style.display = 'flex'
            div.style.alignItems = 'center'

            ul.appendChild(div)
        }
    } else {
        var li = document.createElement('li');
        li.innerText = 'Aucune ressource materielle pour le moment !'
        ul.appendChild(li)
    }
}

/**
 * Gestion du clic sur le bouton 'humaines' dans les ressources d'une activité
 */
function handleHumanButton() {
    // recuperation de l'id
    //id = id[id.length - 1] 

    // mise en place du style pour le menu selectionné (Humaines ou Materielles)
    let bh = document.getElementById('human-button')
    bh.style.textDecoration = 'underline'
    bh.style.fontWeight = '700'

    // mise en place du style pour le menu non selectionné (Humaines ou Materielles)
    let bm = document.getElementById('material-button')
    bm.style.textDecoration = 'none'
    bm.style.fontWeight = 'normal'

    // remplissage du select avec les données de la bd
    let select = document.getElementById('select-resources')
    removeOptions(select)

    for (let indexHR = 0; indexHR < HUMAN_RESOURCE_CATEGORIES.length; indexHR++) {
        option = document.createElement('option')
        option.value = HUMAN_RESOURCE_CATEGORIES[indexHR].id
        option.text = HUMAN_RESOURCE_CATEGORIES[indexHR].categoryname
        select.appendChild(option)
    }
    
    // human / material
    ACTIVITY_IN_PROGRESS.btnHM = 'human'

    fillHRCList()
}


/**
 * Gestion du clic sur le bouton 'materielle' dans les ressources d'une activité
 * @param {id de l'activité donc on veut afficher les ressources materielles} id 
 */
function handleMaterialButton() {
    // recuperation de l'id
    //id = id[id.length - 1] 


    // mise en place du style pour le menu selectionné (Humaines ou Materielles)
    let bm = document.getElementById('material-button')
    bm.style.textDecoration = 'underline'
    bm.style.fontWeight = '700'

    // mise en place du style pour le menu non selectionné (Humaines ou Materielles)
    let bh = document.getElementById('human-button')
    bh.style.textDecoration = 'none'
    bh.style.fontWeight = 'normal'
 
    // remplissage du select avec les données de la bd
    let select = document.getElementById('select-resources')
    removeOptions(select)

    for (let indexMR = 0; indexMR < MATERIAL_RESOURCE_CATEGORIES.length; indexMR++) {
        option = document.createElement('option')
        option.value = MATERIAL_RESOURCE_CATEGORIES[indexMR].id
        option.text = MATERIAL_RESOURCE_CATEGORIES[indexMR].categoryname
        select.appendChild(option)
    }

    // human / material
    ACTIVITY_IN_PROGRESS.btnHM = 'material'
    fillMRCList()
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
    typeRessource = idSplitted[idSplitted.length - 2]
    //idActivity = idSplitted[idSplitted.length - 2]
    idRessource = idSplitted[idSplitted.length - 1]

    if (typeRessource === 'h') {
        ACTIVITY_IN_PROGRESS.humanResourceCategories.splice(idRessource, 1)
        fillHRCList();
    } else {
        ACTIVITY_IN_PROGRESS.materialResourceCategories.splice(idRessource, 1)
        fillMRCList();
    }
    //console.log(RESOURCES_BY_ACTIVITIES)

}