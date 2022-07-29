var SELECT_ID = 0;
var NB_ACTIVITY = 0;


var HUMAN_RESOURCE_CATEGORIES // list of humans resources categories
var MATERIAL_RESOURCE_CATEGORIES // list of materials resources categories
var TARGETS // list of targets of the pathway we want to edit
var RESOURCES_BY_ACTIVITIES = new Array()
var PATHWAY // Contain all the data of a pathway to edit

var ACTIVITY_IN_PROGRESS // allow to store an activity after the creating / editing process 
var ID_EDITED_ACTIVITY 
var IS_EDIT_MODE = false

var ID_ACTIVITY_PREDECESSOR = -1;
var NAME_ACTIVITY_PREDECESSOR = '';
var NB_SUCCESSOR= 0;
var ACTIVITY_POSITION = new Array();
var SUCCESSORS = new Array();
var lines= new Array();
var VALIDATE = 0;

/**
 * Call at the loading of the add pathway page
 */
document.addEventListener('DOMContentLoaded', () => {
    SELECT_ID = 0;

    HUMAN_RESOURCE_CATEGORIES = JSON.parse(
        document.getElementById("json-human-resource-categories").value
    );

    MATERIAL_RESOURCE_CATEGORIES = JSON.parse(
        document.getElementById("json-material-resource-categories").value
    );

    PATHWAY = JSON.parse(
        document.getElementById("json-pathway").value
    );

    TARGETS = JSON.parse(
        document.getElementById('json-targets').value
    )

    document.getElementById('resource-nb').value = 1

    document.getElementById('pathwayid').value = PATHWAY.id

    document.getElementById('pathwayname').value = PATHWAY.pathwayname
    
    initTargets()
    initActivity()
    handleHumanButton()
    initActivitiesList()
    fillActivityList()
    initSuccessorsList()

    // calcul de la taille de la liste
    let heightTitle = document.getElementById('name').offsetHeight
    let heightCreationDiv =document.getElementById('create-activity-container').offsetHeight
    heightCreationDiv = heightCreationDiv - heightTitle
    document.getElementById('list').style.height = heightCreationDiv+'px'

})

/**
 * Initialize the targets input
 */
function initTargets() {
    if (TARGETS.length != 0) {
        targets = document.getElementsByClassName('target')
        let len = targets.length
        for (let i = 0; i < Number(len-1); i++) {
            targets[i].value = TARGETS[i+1].target
        }
        targets[len-1].value = TARGETS[0].target
    }
}


function initActivitiesList() {
    for (let i = 0; i < PATHWAY.activities.length; i++) {
        RESOURCES_BY_ACTIVITIES[i] = new Object()
        RESOURCES_BY_ACTIVITIES[i].humanResourceCategories = PATHWAY.activities[i].humanResourceCategories
        RESOURCES_BY_ACTIVITIES[i].materialResourceCategories = PATHWAY.activities[i].materialResourceCategories
        RESOURCES_BY_ACTIVITIES[i].available = true 

        let len = RESOURCES_BY_ACTIVITIES[i].humanResourceCategories.length
        for (let indexHR = 0; indexHR < len; indexHR++) {
            RESOURCES_BY_ACTIVITIES[i].humanResourceCategories[indexHR].already = true
            RESOURCES_BY_ACTIVITIES[i].humanResourceCategories[indexHR].available = true
        }

        len = RESOURCES_BY_ACTIVITIES[i].materialResourceCategories.length
        for (let indexMR = 0; indexMR < len; indexMR++) {
            RESOURCES_BY_ACTIVITIES[i].materialResourceCategories[indexMR].already = true
            RESOURCES_BY_ACTIVITIES[i].materialResourceCategories[indexMR].available = true
        }

        RESOURCES_BY_ACTIVITIES[i].id = PATHWAY.activities[i].id
        RESOURCES_BY_ACTIVITIES[i].activityname = PATHWAY.activities[i].activityname
        RESOURCES_BY_ACTIVITIES[i].activityduration = PATHWAY.activities[i].activityduration

        NB_ACTIVITY++;
    }
}

function initSuccessorsList() {
    for (let i = 0; i < PATHWAY.successors.length; i++) {
        SUCCESSORS[i] = new Object()
        for(j = 1; j <= NB_ACTIVITY; j++){
            divAct = document.getElementById('act' + j)
            inputs = divAct.getElementsByTagName('input')
            let idActivity = inputs[0].value
            if(idActivity == PATHWAY.successors[i].idActivityA){
                SUCCESSORS[i].idActivityA = 'activity' + j
            }
            if(idActivity == PATHWAY.successors[i].idActivityB){
                SUCCESSORS[i].idActivityB = 'activity' + j
            }
        }
        SUCCESSORS[i].nameActivityA = PATHWAY.successors[i].nameActivityA
        SUCCESSORS[i].nameActivityB = PATHWAY.successors[i].nameActivityB

        SUCCESSORS[i].delayMin = PATHWAY.successors[i].delayMin
        SUCCESSORS[i].delayMax = PATHWAY.successors[i].delayMax

        NB_SUCCESSOR++;
    }
    console.log(SUCCESSORS);
}

/**
 * Allow to show the modal for the target
 */
function showTargets() {
    $('#edit-pathway-modal-targets').modal("show");
}

/**
 * Allow to hide the modal for the target
 */
function hideTargets() {
    $('#edit-pathway-modal-targets').modal("hide");
}

function initActivity() {
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
        res.already = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHR].already
        res.available = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHR].available

        RESOURCES_BY_ACTIVITIES[len].humanResourceCategories.push(res)
    }

    for (let indexMR = 0; indexMR < ACTIVITY_IN_PROGRESS.materialResourceCategories.length; indexMR++) {
        let res = new Object();
        res.id = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMR].id
        res.name = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMR].name
        res.nb = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMR].nb
        res.already = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMR].already
        res.available = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMR].available

        RESOURCES_BY_ACTIVITIES[len].materialResourceCategories.push(res)
    }

    RESOURCES_BY_ACTIVITIES[len].activityname = document.getElementById('input-name').value
    RESOURCES_BY_ACTIVITIES[len].activityduration = document.getElementById('input-duration').value
    RESOURCES_BY_ACTIVITIES[len].id = Number(-1)
}


/**
 * All to add an activity to the list, thanks to ACTIVITY_IN_PROGRESS
 * or to modify the information of an activity already in the list, thanks to IS_EDIT_MODE and ACTIVITY_IN_PROGRESS
 */
function addActivity() {

    let verif = true

    // On verifie que tous les champs sont bons 
    if (document.getElementById('input-name').value == '') {
        verif = false
        alert("Le nom de l'activité ne peut pas être vide")
    }
    else if (Number(document.getElementById('input-duration').value) < 0) {
        verif = false
        alert("La durée de l'activité n'est pas correcte ")
    }    
    else if (document.getElementById('input-duration').value == '') {
        verif = false
        alert("La durée de l'activité n'est pas correcte ")
    }

    console.log(RESOURCES_BY_ACTIVITIES)
    console.log(ACTIVITY_IN_PROGRESS)

    if (verif) {
        // if the activity is open in edit mode
        if (IS_EDIT_MODE) {
            RESOURCES_BY_ACTIVITIES[ID_EDITED_ACTIVITY].activityname = document.getElementById('input-name').value
            RESOURCES_BY_ACTIVITIES[ID_EDITED_ACTIVITY].activityduration = document.getElementById('input-duration').value
            RESOURCES_BY_ACTIVITIES[ID_EDITED_ACTIVITY].available = ACTIVITY_IN_PROGRESS.available
            RESOURCES_BY_ACTIVITIES[ID_EDITED_ACTIVITY].btnHM = 'human'
            RESOURCES_BY_ACTIVITIES[ID_EDITED_ACTIVITY].id = ACTIVITY_IN_PROGRESS.id
        
            //ACTIVITY_IN_PROGRESS.humanResourceCategories = new Array()
            let len = ACTIVITY_IN_PROGRESS.humanResourceCategories.length
            RESOURCES_BY_ACTIVITIES[ID_EDITED_ACTIVITY].humanResourceCategories = new Array()
            for (let indexHuman = 0; indexHuman < len;  indexHuman++) {
                let res = new Object()
                res.id = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHuman].id
                res.name = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHuman].name
                res.nb = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHuman].nb
                res.available = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHuman].available
                res.already = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHuman].already
        
                RESOURCES_BY_ACTIVITIES[ID_EDITED_ACTIVITY].humanResourceCategories.push(res)
            }

            len = ACTIVITY_IN_PROGRESS.materialResourceCategories.length
            RESOURCES_BY_ACTIVITIES[ID_EDITED_ACTIVITY].materialResourceCategories = new Array()
            for (let indexMaterial = 0; indexMaterial < len;  indexMaterial++) {
                let res = new Object()
                res.id = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMaterial].id
                res.name = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMaterial].name
                res.nb = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMaterial].nb
                res.available = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMaterial].available
                res.already = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMaterial].already
        
                RESOURCES_BY_ACTIVITIES[ID_EDITED_ACTIVITY].materialResourceCategories.push(res)
            }

            fillActivityList()

        } else {
            // add activity to the array
            addArray()
            NB_ACTIVITY = NB_ACTIVITY + 1;
            document.getElementById('nbactivity').value = NB_ACTIVITY

            // reset the fields 
            ACTIVITY_IN_PROGRESS = new Object()
            ACTIVITY_IN_PROGRESS.humanResourceCategories = new Array()
            ACTIVITY_IN_PROGRESS.materialResourceCategories = new Array()
            ACTIVITY_IN_PROGRESS.available = true
            ACTIVITY_IN_PROGRESS.already = true
            ACTIVITY_IN_PROGRESS.btnHM = 'human'
            document.getElementById('input-name').value = ''
            document.getElementById('input-duration').value = ''
            handleHumanButton()
        }
        fillActivityList()
        return 1

    } else {
        // error message
        return 0
    }
}

/**
 *  Fill the activity list  
 */
function fillActivityList() {

    let divActivitiesList = document.getElementById('list')
    divActivitiesList.innerHTML = ''

    let indexActivityAvailable = 0

    for (let indexActivity = 0; indexActivity < RESOURCES_BY_ACTIVITIES.length; indexActivity++) {
        if (RESOURCES_BY_ACTIVITIES[indexActivity].available == true) {
            let activity = document.createElement('div')
            activity.setAttribute('class', 'div-activity')
            activity.setAttribute('id', 'act' + (indexActivity+1))
            activity.style.maxHeight = '150px'
            //activity.setAttribute('disabled', 'disabled')

            let input = document.createElement('input')
            input.setAttribute('type', 'hidden')
            input.setAttribute('value', RESOURCES_BY_ACTIVITIES[indexActivity].id)

            let str =  'Activité '+Number(indexActivityAvailable+1) +' : '
            str += RESOURCES_BY_ACTIVITIES[indexActivity].activityname
            str += ' (' +RESOURCES_BY_ACTIVITIES[indexActivity].activityduration +'min)'
            
            let divContainerP = document.createElement('div')
            divContainerP.setAttribute('class', 'container-p')
            let p = document.createElement('p')
            p.style.width = '80%';
            p.innerHTML = str
            divContainerP.appendChild(p)

            let imgDelete = new Image();
            imgDelete.src = '../../img/delete.svg'
            imgDelete.setAttribute('id','imgd-'+indexActivity)
            imgDelete.setAttribute('onclick', 'deleteActivity(this.id)')
            imgDelete.setAttribute('title', 'Supprimer l\'activité du parcours')
            imgDelete.style.width = '20px'
            imgDelete.style.cursor = 'pointer'

            let imgEdit = new Image();
            imgEdit.src = '../../img/edit.svg'
            imgEdit.setAttribute('id','imge-'+indexActivity)
            imgEdit.setAttribute('onclick', 'editActivity(this.id)')
            imgEdit.setAttribute('title', 'Édition de l\'activité')
            imgEdit.style.width = '20px'
            imgEdit.style.cursor = 'pointer'
            imgEdit.style.marginRight = '10px'

            let div = document.createElement('div')
            div.setAttribute('class', 'btns')
            div.appendChild(imgEdit)
            div.appendChild(imgDelete)
            
           /* pindex = document.createElement('p')
            pindex.innerText = indexActivity
            activity.appendChild(pindex)*/
            activity.appendChild(input);
            activity.appendChild(divContainerP)
            activity.appendChild(div)
            divActivitiesList.appendChild(activity)
            indexActivityAvailable++
        }
    }
    

    if (indexActivityAvailable == 0) {
        let noactivity = document.createElement('p')
        noactivity.innerHTML = "Aucune activité pour le moment !"
        noactivity.style.marginLeft ="10px"
        divActivitiesList.appendChild(noactivity)
    }


}

/**
 * Allow to remove an activity in the list 
 * @param { id of the HTML Element which calls the function } id Ex : img-2, img-10
 */
function deleteActivity(id) {

    // We got the index of the div to delete  
    // To do this we get number after '-' in the id of the image : (img-1)
    id = getId(id)
    
    // On peut donc recuperer la div
    /*let divToDelete = document.getElementById('div-activity-'+id)
    // puis la supprimer
    let divAddActivity = document.getElementsByClassName('activities-container')[0]
    divAddActivity.removeChild(divToDelete)*/
    
    // We update the input which contain the number of activity 
    NB_ACTIVITY = NB_ACTIVITY - 1;
    document.getElementById('nbactivity').value = NB_ACTIVITY

    RESOURCES_BY_ACTIVITIES[id].available = false
    //SELECT_ID = SELECT_ID - 1;
    fillActivityList()
}
 

/**
 * Allow to edit an activity  
 */
function editActivity(id) {
    IS_EDIT_MODE = true
    document.getElementById('btn-cancel-activity').style.display = 'flex'
    document.getElementById('btn-confirm-activity').style.display = 'flex'
    document.getElementById('btn-add-activity').style.display = 'none'
    document.getElementById('lbl-title-create').innerText = 'Édition d\'une activité'
    
    id = getId(id)
    ID_EDITED_ACTIVITY = id


    // copy of the activity :
    ACTIVITY_IN_PROGRESS.activityname = RESOURCES_BY_ACTIVITIES[id].activityname
    ACTIVITY_IN_PROGRESS.activityduration = RESOURCES_BY_ACTIVITIES[id].activityduration
    ACTIVITY_IN_PROGRESS.available = RESOURCES_BY_ACTIVITIES[id].available
    ACTIVITY_IN_PROGRESS.btnHM = 'human'
    ACTIVITY_IN_PROGRESS.id = RESOURCES_BY_ACTIVITIES[id].id

    ACTIVITY_IN_PROGRESS.humanResourceCategories = new Array()
    let len = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories.length
    for (let indexHuman = 0; indexHuman < len;  indexHuman++) {
        let res = new Object()
        res.id = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[indexHuman].id
        res.name = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[indexHuman].name
        res.nb = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[indexHuman].nb
        res.available = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[indexHuman].available
        res.already = RESOURCES_BY_ACTIVITIES[id].humanResourceCategories[indexHuman].already

        ACTIVITY_IN_PROGRESS.humanResourceCategories.push(res)
    }

    ACTIVITY_IN_PROGRESS.materialResourceCategories = new Array() 
    len = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories.length
    for (let indexMaterial = 0; indexMaterial < len;  indexMaterial++) {
        let res = new Object()
        res.id = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[indexMaterial].id
        res.name = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[indexMaterial].name
        res.nb = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[indexMaterial].nb
        res.available = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[indexMaterial].available
        res.already = RESOURCES_BY_ACTIVITIES[id].materialResourceCategories[indexMaterial].already

        ACTIVITY_IN_PROGRESS.materialResourceCategories.push(res)
    }

    handleHumanButton()
    document.getElementById('input-name').value = ACTIVITY_IN_PROGRESS.activityname
    document.getElementById('input-duration').value = ACTIVITY_IN_PROGRESS.activityduration

}

/**
 * Allow to cancel the changes during the editing of the activity 
 */
function cancelEditActivity() {
    IS_EDIT_MODE = false

    document.getElementById('btn-cancel-activity').style.display = 'none'
    document.getElementById('btn-confirm-activity').style.display = 'none'
    document.getElementById('btn-add-activity').style.display = 'flex'

    document.getElementById('lbl-title-create').innerText = 'Création d\'une activité'
    initActivity()
    handleHumanButton()
    document.getElementById('input-name').value = ''
    document.getElementById('input-duration').value = ''
}


/**
 * Allow to confirm the changes during the editing of the activity 
 */
function confirmEditActivity() {

    let res = addActivity() 
    if (res) {
        initActivity()
        document.getElementById('btn-cancel-activity').style.display = 'none'    
        document.getElementById('btn-confirm-activity').style.display = 'none'
        document.getElementById('btn-add-activity').style.display = 'flex'
    
        document.getElementById('input-name').value = ''
        document.getElementById('input-duration').value = ''

        document.getElementById('lbl-title-create').innerText = 'Création d\'une activité'
        IS_EDIT_MODE = false
        
    } else {
        // l'edition n'a pas fonctionné
    }
    handleHumanButton()

}




function getId(str) {
    str = str.toString()
    id = str.split('-')
    return id[id.length - 1]
}



/**
 *  Action performed when the '+' button is pressed to add a resource to an activity 
 */
function addResources() {

    // On verifie que le champs quantité est bien rempli 
    let verif = true
    if (document.getElementById('resource-nb').value == '') {
        verif = false
        alert("La quantité de la ressource n'est pas correcte")
    }
    else if (Number(document.getElementById('resource-nb').value) < 1) {
        verif = false
        alert("La quantité de la ressource ne peut pas être inférieure à 1")
    }

    

    if (verif) {
        // ! Si le bouton human est activé !
        if (ACTIVITY_IN_PROGRESS.btnHM == 'human') {
            let resourceNb = document.getElementById('resource-nb').value
            let resourceId = document.getElementById('select-resources').value 

            index = verifyResourcesDuplicates(resourceId, false)
            
            if (index == -1) {

                let resourceName = '';
                for (let indexHRC = 0; indexHRC < HUMAN_RESOURCE_CATEGORIES.length; indexHRC++) {
                    if (HUMAN_RESOURCE_CATEGORIES[indexHRC].id == resourceId) {
                        resourceName = HUMAN_RESOURCE_CATEGORIES[indexHRC].categoryname
                    }
                }
    
                ACTIVITY_IN_PROGRESS.humanResourceCategories.push(new Object())
                let len = ACTIVITY_IN_PROGRESS.humanResourceCategories.length
                ACTIVITY_IN_PROGRESS.humanResourceCategories[len - 1].id = resourceId
                ACTIVITY_IN_PROGRESS.humanResourceCategories[len - 1].name = resourceName
                ACTIVITY_IN_PROGRESS.humanResourceCategories[len - 1].nb = resourceNb
                ACTIVITY_IN_PROGRESS.humanResourceCategories[len - 1].available = true
                ACTIVITY_IN_PROGRESS.humanResourceCategories[len - 1].already = false


            } else {       
                ACTIVITY_IN_PROGRESS.humanResourceCategories[index].nb = Number(ACTIVITY_IN_PROGRESS.humanResourceCategories[index].nb) + Number(resourceNb)
                
            }

           
            fillHRCList()
        } else {
            // ! Si le bouton material est activé !

            let resourceNb = document.getElementById('resource-nb').value
            let resourceId = document.getElementById('select-resources').value 

            // We verify if the resource already exist in the list
            index = verifyResourcesDuplicates(resourceId, true)


            if (index == -1) {

                let resourceName = '';
                for (let indexMRC = 0; indexMRC < MATERIAL_RESOURCE_CATEGORIES.length; indexMRC++) {
                    if (MATERIAL_RESOURCE_CATEGORIES[indexMRC].id == resourceId) {
                        resourceName = MATERIAL_RESOURCE_CATEGORIES[indexMRC].categoryname
                    }
                }
    
                ACTIVITY_IN_PROGRESS.materialResourceCategories.push(new Object())
                let len = ACTIVITY_IN_PROGRESS.materialResourceCategories.length
                ACTIVITY_IN_PROGRESS.materialResourceCategories[len - 1].id = resourceId
                ACTIVITY_IN_PROGRESS.materialResourceCategories[len - 1].name = resourceName
                ACTIVITY_IN_PROGRESS.materialResourceCategories[len - 1].nb = resourceNb
                ACTIVITY_IN_PROGRESS.materialResourceCategories[len - 1].available = true 

            } else {
                if (ACTIVITY_IN_PROGRESS.materialResourceCategories[index].available) {

                    ACTIVITY_IN_PROGRESS.materialResourceCategories[index].nb = Number(ACTIVITY_IN_PROGRESS.materialResourceCategories[index].nb) + Number(resourceNb)

                } else {

                    let resourceName = '';
                    for (let indexMRC = 0; indexMRC < MATERIAL_RESOURCE_CATEGORIES.length; indexMRC++) {
                        if (MATERIAL_RESOURCE_CATEGORIES[indexMRC].id == resourceId) {
                            resourceName = MATERIAL_RESOURCE_CATEGORIES[indexMRC].categoryname
                        }
                    }
    
                    ACTIVITY_IN_PROGRESS.materialResourceCategories.push(new Object())
                    let len = ACTIVITY_IN_PROGRESS.materialResourceCategories.length
                    ACTIVITY_IN_PROGRESS.materialResourceCategories[len - 1].id = resourceId
                    ACTIVITY_IN_PROGRESS.materialResourceCategories[len - 1].name = resourceName
                    ACTIVITY_IN_PROGRESS.materialResourceCategories[len - 1].nb = resourceNb
                    ACTIVITY_IN_PROGRESS.materialResourceCategories[len - 1].available = true 

                }
            }
 

            fillMRCList()
        }
    }

}

/**
 * Takes an id in parameter and verify if the resource id already in the list of the activity.
 * Returns the index of the resource in the list if it exists
 * @param {*} id 
 * @param {*} material 
 */
function verifyResourcesDuplicates(id, material) {
    
    // Si material est true
    if (material) {

        for (let indexMaterial = 0; indexMaterial < ACTIVITY_IN_PROGRESS.materialResourceCategories.length; indexMaterial++) {
            if (ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMaterial].id == id) {
                if (ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMaterial].available) {
                    return indexMaterial
                }
            }
        }

    } else {

        for (let indexHuman = 0; indexHuman < ACTIVITY_IN_PROGRESS.humanResourceCategories.length; indexHuman++) {
            if (ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHuman].id == id) {
                if (ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHuman].available) {
                    return indexHuman
                }            
            }
        }

    }
    
    return -1
}


/**
 * Remplit la liste des ressources humaines 
 */
function fillHRCList() {

    
    // On recupere la liste dans laquelle on va ajouter notre ressource
    ul = document.getElementById('list-resources')
    ul.style.listStyle='none'
    ul.innerHTML = ''

    let len = ACTIVITY_IN_PROGRESS.humanResourceCategories.length
    let availableResourceCount = 0

    if (len > 0) {
        for (let indexHRC = 0 ; indexHRC < len ; indexHRC++) {

            if (ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHRC].available) {
                // On crée le li qui va stocker la ressource (visuellement) 
                var li = document.createElement('li');
                let resourceNb = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHRC].nb 
                let resourceName = ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHRC].name
                li.innerText = resourceName +' ('+resourceNb+')'
            

                let imgDelete = new Image();
                imgDelete.src = '../../img/delete.svg'
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
                availableResourceCount++
            }
        }
    } 

    if (availableResourceCount == 0) {
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
    let availableResourceCount = 0
    
    if (len > 0) {
        for (let indexMRC = 0 ; indexMRC < len ; indexMRC++) {

            if (ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMRC].available) {
                // On crée le li qui va stocker la ressource (visuellement) 
                var li = document.createElement('li');
        
                let resourceNb = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMRC].nb 
                let resourceName = ACTIVITY_IN_PROGRESS.materialResourceCategories[indexMRC].name
                li.innerText = resourceName +' ('+resourceNb+')'
            
                let imgDelete = new Image();
                imgDelete.src = '../../img/delete.svg'
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
                availableResourceCount++
            }
        }
    }

    if (availableResourceCount == 0) {
        var li = document.createElement('li');
        li.innerText = 'Aucune ressource materielles pour le moment !'
        ul.appendChild(li)
    }
}

/**
 * Gestion du clic sur le bouton 'humaines' dans les ressources d'une activité
 */
function handleHumanButton() {

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
 */
function handleMaterialButton() {

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
 * @param {id de l'element html} id 
 */
function deleteResource(id) {

    idSplitted = id.split('-');
    typeRessource = idSplitted[idSplitted.length - 2]
    //idActivity = idSplitted[idSplitted.length - 2]
    idRessource = idSplitted[idSplitted.length - 1]

    if (typeRessource === 'h') {
        //ACTIVITY_IN_PROGRESS.humanResourceCategories.splice(idRessource, 1)
        ACTIVITY_IN_PROGRESS.humanResourceCategories[idRessource].available = false
        fillHRCList();
    } else {
        //ACTIVITY_IN_PROGRESS.materialResourceCategories.splice(idRessource, 1)
        ACTIVITY_IN_PROGRESS.materialResourceCategories[idRessource].available = false
        fillMRCList();
    }
}

/**
 * delete the "," "." and "e" from the input for the target
 * @param {delete} input 
 */
function preventForTarget(input) {
    input.value = input.value.replace("e", "");
    input.value = input.value.replace(",", "");
    input.value = input.value.replace(".", "");
}

/**
 * Verify that targets are correct
 */
function isTargetCorrect() {
    errorInferiorToZero = false
    let targets = document.getElementsByClassName('target')
    for (let i = 0 ; (i < targets.length) && (!errorInferiorToZero) ; i++) {
        if (Number(targets[i].value) < 0) {
            return false
        }  
    }
    return true
}

/**
 * Verifie que le nom du parcours est correct
 * Stocke le tableau contenant toutes les activités ressources dans un input pour qu'il soit accesible dans le serveur
 * Envoie la requete POST au serveur
 */
function submitPathway() {
    let btnSubmit = document.getElementById('submit')
    let verif = true

    // On verifie que tous les champs sont bons 
    if (document.getElementById('pathwayname').value == '') {
        verif = false
        alert("Le nom du parcours ne peut pas être vide")
    }

    if (isTargetCorrect())
    {
        if (verif) {
            document.getElementById('json-resources-by-activities').value = JSON.stringify(RESOURCES_BY_ACTIVITIES);
            document.getElementById('json-successors').value = JSON.stringify(SUCCESSORS);
            console.log(RESOURCES_BY_ACTIVITIES)
            btnSubmit.click()
        }
    } else {
        alert("Au moins une valeur n'est pas bonne dans les objectifs journaliers")
    }
}

function showActivitiesPathway() {
    VALIDATE = 0;
    document.getElementById('title-pathway-activities').innerHTML = "Lier les activités";
    drawActivitiesGraph();
    fillSuccessorList();
    drawArrows();
    
    $('#edit-pathway-modal-activities').modal("show");
}

function hideActivitiesPathway(){
    deleteSuccessors();
    $('#edit-pathway-modal-activities').modal("hide");
}

function drawActivitiesGraph(){
    var divContent = document.getElementById('divContent');
    divContent.innerHTML = ""; // reset the content

    for(i = 0; i < RESOURCES_BY_ACTIVITIES.length; i++){
        rba = RESOURCES_BY_ACTIVITIES[i];
        if(rba.available){
            createActivitiesGraph(rba.activityname, i+1, rba.activityduration);
        }
    }
}

function createActivitiesGraph(name, idActivity, duration){
    var divContent = document.getElementById('divContent');

    var div = document.createElement('DIV');
    div.setAttribute('id', 'activity'+ idActivity);
    div.classList.add("pathway-div-activity-graph");

    var divHeader = document.createElement('div');
    divHeader.classList.add("pathway-div-activity-header");
    divHeader.innerHTML = name;

    var p = document.createElement('p');
    p.style.fontSize = '80%';
    p.innerHTML = "durée : " + duration + "min"; 

    div.appendChild(divHeader); div.appendChild(p);
    divContent.appendChild(div);
    
    $(".pathway-div-activity-graph").draggable({
        containment: "#divContent",
      });

    div.addEventListener('mousemove', AnimEvent.add(function() {
        lines.forEach((l) => {
            if(l.start == div || l.end == div){
                l.position();
            }
          });
    }), false);

    div.addEventListener('scroll', AnimEvent.add(function() {
        lines.forEach((l) => {
            if(l.start == div || l.end == div){
                l.position();
            }
          });
    }), false);


    div.addEventListener('dblclick', function (e) {
        if(ID_ACTIVITY_PREDECESSOR != -1){
            errorLine = false;
            if(ID_ACTIVITY_PREDECESSOR == div.id){
                errorLine = true;
                alert("Vous ne pouvez pas lier une activité à elle-même !");
            }
            start = document.getElementById(ID_ACTIVITY_PREDECESSOR);
            end = document.getElementById(div.id);
            for(i = 0; i < NB_SUCCESSOR; i++){
                if(SUCCESSORS[i].idActivityA == start.id && SUCCESSORS[i].idActivityB == end.id){
                    alert('Ce lien est déjà créé !')
                    errorLine = true;
                }
                if(SUCCESSORS[i].idActivityA == end.id && SUCCESSORS[i].idActivityB == start.id){
                    alert("Un lien existe déjà dans l'autre sens, veuillez le supprimer avant d'en ajouter un autre.")
                    errorLine = true;
                }
            }
            if(!errorLine){
                l = new LeaderLine(start, end, {color: '#0dac2d', middleLabel: "Lien n°" + (NB_SUCCESSOR+1)});

                lines.push(l);
                addSuccessor(ID_ACTIVITY_PREDECESSOR, div.id, NAME_ACTIVITY_PREDECESSOR, name);
                ID_ACTIVITY_PREDECESSOR = -1;
            }
            else{
                ID_ACTIVITY_PREDECESSOR = -1;
                NAME_ACTIVITY_PREDECESSOR = '';
            }
        }
        else{
            ID_ACTIVITY_PREDECESSOR = div.id;
            NAME_ACTIVITY_PREDECESSOR = name;
        }
    });
}

function addSuccessor(idA, idB, nameA, nameB) {
    addArraySuccessor(idA, idB, nameA, nameB);
    NB_SUCCESSOR++;
    fillSuccessorList();
}

function drawArrows(){ 
    for(i = 0; i < NB_SUCCESSOR; i++){
        start = document.getElementById(SUCCESSORS[i].idActivityA);
        end = document.getElementById(SUCCESSORS[i].idActivityB);
        l = new LeaderLine(start, end, {color: '#0dac2d', middleLabel: "Lien n°" + (i+1)});
        lines.push(l);
    }
}

function addArraySuccessor(idA, idB, nameA, nameB) {
    let len = SUCCESSORS.length

    SUCCESSORS[len] = new Object()
    SUCCESSORS[len].idActivityA = idA;
    SUCCESSORS[len].idActivityB = idB;

    SUCCESSORS[len].nameActivityA = nameA;
    SUCCESSORS[len].nameActivityB = nameB;

    SUCCESSORS[len].delayMin = 0;
    SUCCESSORS[len].delayMax = 10;
}

/* remplit la liste des successeurs (sur la droite) */
function fillSuccessorList() {

    let divSuccessorsList = document.getElementById('list-graph')
    divSuccessorsList.innerHTML = ''

    for (let indexSuccessor = 0; indexSuccessor < SUCCESSORS.length; indexSuccessor++) {
            let successor = document.createElement('div')
            successor.setAttribute('class', 'div-activity')
            successor.setAttribute('id', 'link-' + indexSuccessor);
            let idA = document.createElement('input');
            idA.setAttribute('type', 'hidden');
            idA.setAttribute('value', SUCCESSORS[indexSuccessor].idActivityA);
            let idB = document.createElement('input');
            idB.setAttribute('type', 'hidden');
            idB.setAttribute('value', SUCCESSORS[indexSuccessor].idActivityB);
            successor.appendChild(idA); successor.appendChild(idB);
            let str = "Lien n°" + (indexSuccessor+1);
            let p = document.createElement('p')
            p.innerHTML = str

            let imgDelete = new Image();
            imgDelete.src = '../../img/delete.svg';
            imgDelete.setAttribute('id', 'succ_imgd-' + indexSuccessor);
            imgDelete.setAttribute('onclick', 'deleteSuccessor(this.id)');
            imgDelete.setAttribute('title', 'Supprimer le lien');
            imgDelete.style.width = '20px';
            imgDelete.style.cursor = 'pointer';

            let imgDownArrow = new Image();
            imgDownArrow.src = '../../img/down-arrow.svg';
            imgDownArrow.setAttribute('id', 'succ_imgdown-' + indexSuccessor);
            imgDownArrow.setAttribute('onclick', 'showDelay('+indexSuccessor+')');
            imgDownArrow.setAttribute('title', 'Montrer les délais');
            imgDownArrow.style.width = '20px';
            imgDownArrow.style.cursor = 'pointer';

            let divMin = document.createElement('div')
            divMin.setAttribute('id', 'divMin' + (indexSuccessor))

            let labelMin = document.createElement('label');
            labelMin.classList.add("label");
            labelMin.innerHTML = "Délai min (minutes) : ";
            labelMin.style.width = "70%";

            let inputMin = document.createElement('input');
            inputMin.setAttribute('id', 'delayMinInput' + (indexSuccessor+1));
            inputMin.setAttribute('type', 'number');
            inputMin.setAttribute('min', 0);
            inputMin.setAttribute('step', 1);
            inputMin.setAttribute('value', 0);
            inputMin.style.width = "30%";

            divMin.appendChild(labelMin);
            divMin.appendChild(inputMin);
            divMin.style.display = "none";

            let divMax = document.createElement('div')
            divMax.setAttribute('id', 'divMax' + (indexSuccessor))

            let labelMax = document.createElement('label');
            labelMax.classList.add("label");
            labelMax.innerHTML = "Délai max (minutes) : ";
            labelMax.style.width = "70%"

            let inputMax = document.createElement('input');
            inputMax.setAttribute('id', 'delayMaxInput' + (indexSuccessor+1));
            inputMax.setAttribute('type', 'number');
            inputMax.setAttribute('min', 0);
            inputMax.setAttribute('step', 1);
            inputMax.setAttribute('value', 360);
            inputMax.style.width = "30%"

            divMax.appendChild(labelMax);
            divMax.appendChild(inputMax);
            divMax.style.display = "none";

            let divDel = document.createElement('div');
            divDel.appendChild(imgDelete);

            let divDown = document.createElement('div');
            divDown.appendChild(imgDownArrow);

            successor.appendChild(p);
            successor.appendChild(divDel);
            successor.appendChild(divDown);

            let divSuccessor = document.createElement('div');
            divSuccessor.classList.add("div-successor")

            divSuccessor.appendChild(successor);
            divSuccessor.appendChild(divMin);
            divSuccessor.appendChild(divMax);

            divSuccessorsList.appendChild(divSuccessor);
    }
    if (SUCCESSORS.length == 0) {
        let nosuccessor = document.createElement('p');
        nosuccessor.innerHTML = "Aucun lien pour le moment !";
        nosuccessor.style.marginLeft ="10px";
        divSuccessorsList.appendChild(nosuccessor);
    }
}

function showDelay(id){
    divMin = document.getElementById('divMin' + id);
    divMax = document.getElementById('divMax' + id);

    if(divMin.style.display == "none" || divMax.style.display == "none"){
        divMin.style.display = "block";
        divMax.style.display = "block";
    }
    else{
        divMin.style.display = "none";
        divMax.style.display = "none";
    }
}

function deleteSuccessor(id) {
    id = getId(id);
    let divSuccessor = document.getElementById('link-' + id);
    let inputs = divSuccessor.getElementsByTagName('input');

    for(i = 0; i < lines.length; i++){
        idA = inputs[0].value;
        idB = inputs[1].value;
        if (lines[i].start == document.getElementById(idA) && lines[i].end == document.getElementById(idB)){
            lines[i].remove();
            lines.splice(i, 1);
        }
    }

    for(i = 0; i < lines.length; i++){
        lines[i].middleLabel="Lien n°" + (i+1);
    }

    NB_SUCCESSOR--;
    SUCCESSORS.splice(id, 1);
    
    fillSuccessorList();
}

function deleteSuccessors(){
    NB_SUCCESSOR = 0;
    SUCCESSORS = new Array();
    initSuccessorsList();
    deleteArrows();
    fillSuccessorList();
}

function deleteArrows(){
    for (var l of lines) {
        l.remove();
    }
    lines = new Array();
}

function validateSuccessors(){
    error = 0;
    //error = checkSuccessor();
    switch(error){
        case 0:
            for(i = 0; i < NB_SUCCESSOR; i++){
                inputMin = document.getElementById("delayMinInput" + (i+1));
                inputMax = document.getElementById("delayMaxInput" + (i+1));
                SUCCESSORS[i].delayMin = inputMin.value;
                SUCCESSORS[i].delayMax = inputMax.value;
            }
            deleteArrows();
            VALIDATE = 1;
            $('#edit-pathway-modal-activities').modal("hide");
        break;
        case 1:
            alert("Il reste des activités non liées !");
        break;
        case 2:
            alert("Vous avez formé une boucle ! Veuillez laisser une activité de départ sans lien entrant.")
        break;
    }
}

function checkSuccessor(){
    // Return 0 if everything is ok, else some int that will be used in a switch to display specific error
    console.log(SUCCESSORS)
    if(NB_ACTIVITY == 1 || NB_ACTIVITY == 0){
        return 0;
    }
    var predecessor;
    var loop = true;
    for(i = 0; i < NB_ACTIVITY; i++){
        var link = true;
        predecessor = false;
        for(j = 0; j < NB_SUCCESSOR; j++){
            // Check if 
            if(SUCCESSORS[j].nameActivityA == RESOURCES_BY_ACTIVITIES[i].activityname || 
                SUCCESSORS[j].nameActivityB == RESOURCES_BY_ACTIVITIES[i].activityname){
                    link = false;
            }
            if(SUCCESSORS[j].nameActivityA == RESOURCES_BY_ACTIVITIES[i].activityname){
                predecessor = true;
            }
        }
        if(link){
            return 1;
        }
        if(!predecessor){
            loop = false;
        }
    }
    if(loop){
        return 2;
    }
    else{
        return 0;  
    }
}