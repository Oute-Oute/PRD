var SELECT_ID = 0;
var NB_ACTIVITY = 0;



/**
 * Appelée au chargement de la page de création d'un parcours (circuit)
 */
document.addEventListener('DOMContentLoaded', () => {
    SELECT_ID = 0;
    showNewModalForm()
})


function disableSubmit() {
    let btnSubmit = document.getElementById('submit')
    btnSubmit.disabled=true
}

/**
 * Permet d'ajouter une liste déroulante pour choisir une activité lors de la cration d'un parcours (pathway)
 */
function handleAddActivity() {

    NB_ACTIVITY = NB_ACTIVITY + 1;
    document.getElementById('nbactivity').value = NB_ACTIVITY

    disableSubmit();
    
    // Création d'une div qui contient les inputs pour le nom de l'activité la durée et le btn de suppression
    let div = document.createElement("div")
    div.setAttribute('class', 'activity-field')    

    let inputName = document.createElement('input')
    inputName.setAttribute('class', 'input-name')
    inputName.setAttribute('placeholder', 'Nom')
    inputName.setAttribute('onchange', 'disableSubmit()')
    //inputName.setAttribute('name', 'name-activity-'+SELECT_ID)

    /*let inputDuration = document.createElement('input')
    inputDuration.setAttribute('class', 'input-duration')
    inputDuration.setAttribute('placeholder', 'Durée (min)')
    inputDuration.setAttribute('type', 'number')
    inputDuration.setAttribute('min', '0')
    inputDuration.setAttribute('onchange', 'disableSubmit()')*/
    //inputDuration.setAttribute('name', 'duration-activity-'+SELECT_ID)

    let imgDelete = new Image();
    imgDelete.src = 'img/delete.svg'
    imgDelete.setAttribute('id','img-'+SELECT_ID)
    imgDelete.setAttribute('onclick', 'deleteSelect(this.id)')

    let imgEdit = new Image();
    imgEdit.src = 'img/edit.svg'
    imgEdit.setAttribute('id','img-'+SELECT_ID)
    imgEdit.setAttribute('onclick', 'editSelect(this.id)')

    div.appendChild(inputName)
    //div.appendChild(inputDuration)
    div.appendChild(imgEdit)
    div.appendChild(imgDelete)

    // On l'affiche et on l'ajoute a la fin de la balise div activities-container
    //select.style.display = "block";
    let divAddActivity = document.getElementById('activities-container')

    let divcontainer = document.createElement('div')
    //divcontainer.setAttribute('class', "title-container")
    divcontainer.setAttribute('class', 'flex-row')
    divcontainer.style.justifyContent = "center"
    let pTitle = document.createElement("p")
    pTitle.innerHTML = 'Activité : '
    pTitle.setAttribute('class', 'label')
    //let divclass = divcontainer.getAttribute('class')  //ajouter la classe 'div-activity-(id)' en plusde activity-field a div
    divcontainer.setAttribute('class', 'div-activity-'+SELECT_ID)
    divcontainer.appendChild(pTitle)
    divcontainer.appendChild(div)

    let divEdit = document.createElement('div')
    divEdit.setAttribute('id', 'div-edit-activity-'+SELECT_ID)
    //divcontainer.appendChild(divEdit)
    divcontainer.appendChild(divEdit)

    divAddActivity.appendChild(divcontainer)

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
    let divToDelete = document.getElementsByClassName('div-activity-'+id)[0]
    // puis la supprimer
    let divAddActivity = document.getElementById('activities-container')
    divAddActivity.removeChild(divToDelete)
    
    // On actusalise l'input qui contient le nb d'activité
    NB_ACTIVITY = NB_ACTIVITY - 1;
    document.getElementById('nbactivity').value = NB_ACTIVITY

    SELECT_ID = SELECT_ID - 1;
}

/**
 * Permet de modifier un select dans la liste déroulante 
 * @param {*} id : img-0, img-1
 */
function editSelect(id) {
    disableSubmit();

    // On récupère le numero de la div a supprimer  
    // Pour cela on recupere que le dernier caracetere de l'id de l'img : (img-1)
    id = id[id.length - 1] 
    // On peut donc recuperer la div
    //let divToEdit = document.getElementsByClassName('div-activity-'+id)[0]
    let divToEdit = document.getElementById('div-edit-activity-'+id)
    console.log(divToEdit)
    divToEdit.style.height = '100px'
    divToEdit.style.border = "solid lightgrey 1px"


    p1 = document.createElement('p')
    p1.innerHTML = 'Humaines'
    
    p2 = document.createElement('p')
    p2.innerHTML = 'Materielles'
    divToEdit.appendChild(p1)
    divToEdit.appendChild(p2)
    
    // On actusalise l'input qui contient le nb d'activité
    NB_ACTIVITY = NB_ACTIVITY - 1;
    document.getElementById('nbactivity').value = NB_ACTIVITY

    SELECT_ID = SELECT_ID - 1;
}

/**
 * Permet de verifier les champs et de leur donner un 'name' pour la requete
 */
function verifyChanges() {

    let formOk = true
    // D'abord on recupere la div qui contient toutes les activity
    let activitiesContainer = document.getElementById('activities-container')

    // On parcours toutes nos activités 
    // On set leur 'name' et on verifie leurs contenus
    for (let i = 0; i < NB_ACTIVITY; i++) {

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




/**
 * Permet d'afficher la fenêtre modale d'ajout
 */
function showNewModalForm(){
    $('#add-pathway-modal').modal("show");
}

/**
 * Permet de fermer la fenêtre modale d'ajout
 */
function hideNewModalForm() {
    $('#add-pathway-modal').modal("hide");
    console.log('hide')
}

