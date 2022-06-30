
var SELECT_ID_EDIT = 0
var NB_ACTIVITY_EDIT = 0

function disableSubmit_EditForm() {
    let btnSubmit = document.getElementById('edit-submit')
    btnSubmit.disabled=true
}


/**
 * Permet de supprimer un select dans la liste déroulante 
 * @param {*} id : img-0, img-1
 */
function deleteSelect_EditForm(id) {
    disableSubmit_EditForm();

    // On récupère le numero de la div a supprimer  
    // Pour cela on recupere que le dernier caracetere de l'id de l'img : (img-1)
    id = id[id.length - 1] 
    // On peut donc recuperer la div
    let divToDelete = document.getElementsByClassName('edit-form-div-activity-'+id)[0]
    // puis la supprimer
    let divAddActivity = document.getElementById('edit-activities-container')
    divAddActivity.removeChild(divToDelete)
    
    // On actusalise l'input qui contient le nb d'activité
    NB_ACTIVITY = NB_ACTIVITY - 1;
    document.getElementById('nbActivity_EditForm').value = NB_ACTIVITY_EDIT

    SELECT_ID_EDIT = SELECT_ID_EDIT - 1;
}

/**
 * Permet d'afficher la fenêtre modale d'édition
 */
function showEditModalForm(id, name, index){
    $('#edit-pathway-modal').modal("show");

    let divAddActivity = document.getElementById('edit-activities-container')
    divAddActivity.innerHTML = ''

    SELECT_ID_EDIT = 0
    NB_ACTIVITY_EDIT = activitiesByPathways.length

    document.getElementById('edit-pathwayname').value = name
    for (let i = 0; i < activitiesByPathways.length ; i++) {
        // Création d'une div qui contient les inputs pour le nom de l'activité la durée et le btn de suppression
        let div = document.createElement("div")
        div.setAttribute('class', 'form-field')

        let inputName = document.createElement('input')
        inputName.setAttribute('class', 'input-name')
        inputName.setAttribute('placeholder', 'Nom')
        inputName.setAttribute('onchange', 'disableSubmit_EditForm()')
        inputName.value = activitiesByPathways[index].activities[i].name
        //inputName.setAttribute('name', 'name-activity-'+SELECT_ID)

        let inputDuration = document.createElement('input')
        inputDuration.setAttribute('class', 'input-duration')
        inputDuration.setAttribute('placeholder', 'Durée (min)')
        inputDuration.setAttribute('type', 'number')
        inputDuration.setAttribute('min', '0')
        inputDuration.setAttribute('onchange', 'disableSubmit_EditForm()')
        inputDuration.value = activitiesByPathways[index].activities[i].duration
        //inputDuration.setAttribute('name', 'duration-activity-'+SELECT_ID)

        let img = new Image();
        img.src = 'img/delete.svg'
        img.setAttribute('id','edit-form-img-'+SELECT_ID_EDIT)
        img.setAttribute('onclick', 'deleteSelect_EditForm(this.id)')

        div.appendChild(inputName)
        div.appendChild(inputDuration)
        div.appendChild(img)

        // On l'affiche et on l'ajoute a la fin de la balise div activities-container
        //select.style.display = "block";
        let divAddActivity = document.getElementById('edit-activities-container')

        let divcontainer = document.createElement('div')
        //divcontainer.setAttribute('class', "title-container")
        divcontainer.setAttribute('class', 'flex-row')
        divcontainer.style.justifyContent = "center"
        let pTitle = document.createElement("p")
        pTitle.innerHTML = "Activité : "
        let divclass = divcontainer.getAttribute('class')  //ajouter la classe 'div-activity-(id)' en plusde form-field a div
        divcontainer.setAttribute('class', divclass + ' edit-form-div-activity-'+SELECT_ID_EDIT)
        divcontainer.appendChild(pTitle)
        divcontainer.appendChild(div)
        divAddActivity.appendChild(divcontainer)

        SELECT_ID_EDIT = SELECT_ID_EDIT + 1
    }
}


/**
 * Permet d'ajouter une liste déroulante pour choisir une activité lors de la cration d'un parcours (pathway)
 */
function handleAddActivity_EditForm() {

    NB_ACTIVITY_EDIT = NB_ACTIVITY_EDIT + 1;
    document.getElementById('nbActivity').value = NB_ACTIVITY_EDIT

    disableSubmit_EditForm();
    
    // Création d'une div qui contient les inputs pour le nom de l'activité la durée et le btn de suppression
    let div = document.createElement("div")
    div.setAttribute('class', 'form-field')

    let inputName = document.createElement('input')
    inputName.setAttribute('class', 'input-name')
    inputName.setAttribute('placeholder', 'Nom')
    inputName.setAttribute('onchange', 'disableSubmit_EditForm()')
    //inputName.setAttribute('name', 'name-activity-'+SELECT_ID)

    let inputDuration = document.createElement('input')
    inputDuration.setAttribute('class', 'input-duration')
    inputDuration.setAttribute('placeholder', 'Durée (min)')
    inputDuration.setAttribute('type', 'number')
    inputDuration.setAttribute('min', '0')
    inputDuration.setAttribute('onchange', 'disableSubmit_EditForm()')
    //inputDuration.setAttribute('name', 'duration-activity-'+SELECT_ID)

    let img = new Image();
    img.src = 'img/delete.svg'
    img.setAttribute('id','edit-form-img-'+SELECT_ID_EDIT)
    img.setAttribute('onclick', 'deleteSelect_EditForm(this.id)')

    div.appendChild(inputName)
    div.appendChild(inputDuration)
    div.appendChild(img)

    // On l'affiche et on l'ajoute a la fin de la balise div activities-container
    //select.style.display = "block";
    let divAddActivity = document.getElementById('edit-activities-container')

    let divcontainer = document.createElement('div')
    //divcontainer.setAttribute('class', "title-container")
    divcontainer.setAttribute('class', 'flex-row')
    divcontainer.style.justifyContent = "center"
    let pTitle = document.createElement("p")
    pTitle.innerHTML = "Activité : "
    let divclass = divcontainer.getAttribute('class')  //ajouter la classe 'div-activity-(id)' en plusde form-field a div
    divcontainer.setAttribute('class', divclass + ' edit-form-div-activity-'+SELECT_ID_EDIT)
    divcontainer.appendChild(pTitle)
    divcontainer.appendChild(div)
    divAddActivity.appendChild(divcontainer)

    SELECT_ID_EDIT++
} 

/**
 * Permet de verifier les champs et de leur donner un 'name' pour la requete
 */
function verifyChanges_EditForm() {

    let formOk = true
    // D'abord on recupere la div qui contient toutes les activity
    let activitiesContainer = document.getElementById('edit-activities-container')

    // On parcours toutes nos activités 
    // On set leur 'name' et on verifie leurs contenus
    for (let i = 0; i < NB_ACTIVITY_EDIT; i++) {

        activitiesContainer.children[i].children[1].children[0].setAttribute('name', 'name-activity-'+ Number(i))
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
        }

    }

    if (document.getElementById('pathwayname').value === '') {
        formOk = false
    }

    if (formOk) {
        let btnSubmit = document.getElementById('submit')
        btnSubmit.disabled = false;
    }
}