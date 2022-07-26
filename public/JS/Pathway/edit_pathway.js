var SELECT_ID = 0;
var NB_ACTIVITY = 0;


var HUMAN_RESOURCE_CATEGORIES // list of humans resources categories
var MATERIAL_RESOURCE_CATEGORIES // list of materials resources categories

var RESOURCES_BY_ACTIVITIES = new Array()
var PATHWAY // Contain all the data of a pathway to edit

var ACTIVITY_IN_PROGRESS // allow to store an activity after the creating / editing process 
var ID_EDITED_ACTIVITY 
var IS_EDIT_MODE = false


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

    document.getElementById('resource-nb').value = 1
  

    document.getElementById('pathwayid').value = PATHWAY.id

    document.getElementById('pathwayname').value = PATHWAY.pathwayname
    
    initActivity()
    handleHumanButton()
    initActivitiesList()
    fillActivityList()

    // calcul de la taille de la liste
    let heightTitle = document.getElementById('name').offsetHeight
    let heightCreationDiv =document.getElementById('create-activity-container').offsetHeight
    heightCreationDiv = heightCreationDiv - heightTitle
    document.getElementById('list').style.height = heightCreationDiv+'px'

})


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
    }

}

function showTargets() {
    $('#edit-pathway-modal-targets').modal("show");
}

/**
 * Permet d'afficher la fenêtre modale d'informations
 */
function showInfosPathway(idPathway, name) {
    document.getElementById('pathway').innerHTML = name;
   
    var tableBody = document.getElementById('tbodyShow');
    tableBody.innerHTML = ''; // We delete what we wrote in the modal form precedently

    $.ajax({
        type : 'POST',
        url  : '/ajaxPathway',
        data : {idPathway: idPathway},
        dataType : "json",
        success : function(data){        
            tableAppointment(tableBody, data);
        },
        error: function(data){
            console.log("error");
        }
        });
    
    $('#infos-pathway-modal').modal("show");
}

function tableAppointment(tableBody, data){
    if(data.length <= 0){
        var tr = document.createElement('TR');
        tableBody.appendChild(tr);
        var td = document.createElement('TD');
        td.setAttribute('colspan', 5);
        td.append("Pas de patients prévus pour ce parcours");
        tr.appendChild(td);
    }
    else{
        for(i = 0; i < data.length; i++){
            var tr = document.createElement('TR');
            tableBody.appendChild(tr);
            var td1 = document.createElement('TD');
            var td2 = document.createElement('TD');
            td1.append(data[i]['lastname'] + ' ' + data[i]['firstname']);
            td2.append(data[i]['date']);
            tr.appendChild(td1);tr.appendChild(td2);
        }
    }
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

    if (verif) {
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
            activity.style.height = 'auto'
            //activity.setAttribute('disabled', 'disabled')
            let str =  'Activité '+Number(indexActivityAvailable+1) +' : '
            str += RESOURCES_BY_ACTIVITIES[indexActivity].activityname
            str += ' (' +RESOURCES_BY_ACTIVITIES[indexActivity].activityduration +'min)'
            let p = document.createElement('p')
            p.style.width = '80%';
            p.innerHTML = str

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
            div.appendChild(imgEdit)
            div.appendChild(imgDelete)
            
           /* pindex = document.createElement('p')
            pindex.innerText = indexActivity
            activity.appendChild(pindex)*/

            activity.appendChild(p)
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

            } else {
                if (ACTIVITY_IN_PROGRESS.humanResourceCategories[index].available) {

                    ACTIVITY_IN_PROGRESS.humanResourceCategories[index].nb = Number(ACTIVITY_IN_PROGRESS.humanResourceCategories[index].nb) + Number(resourceNb)

                } else {

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
                    
                }
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
                return indexMaterial
            }
        }

    } else {

        for (let indexHuman = 0; indexHuman < ACTIVITY_IN_PROGRESS.humanResourceCategories.length; indexHuman++) {
            if (ACTIVITY_IN_PROGRESS.humanResourceCategories[indexHuman].id == id) {
                return indexHuman
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

    if (verif) {
        document.getElementById('json-resources-by-activities').value = JSON.stringify(RESOURCES_BY_ACTIVITIES);
        btnSubmit.click()
    }
}