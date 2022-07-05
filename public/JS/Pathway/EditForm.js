
// SLEECT ID : correspond a l'indice de l'activité à créée 0, 1, 2... 
var SELECT_ID_EDIT = 0
// NB ACTIVITY : nombre totale d'activité
var NB_ACTIVITY_EDIT = 0

function edit__disableSubmit() {
    let btnSubmit = document.getElementById('edit--submit')
    btnSubmit.disabled=true
}


/**
 * Permet de supprimer un select dans la liste déroulante 
 * @param {*} id : img-0, img-1
 * en prenant uniquement le dernier chiffre de l'id on recupere l'indice de l'activité a supprimer
 */
function edit__deleteSelect(id) {
    edit__disableSubmit();

    // On récupère le numero de la div a supprimer  
    // Pour cela on recupere que le dernier caracetere de l'id de l'img : (img-1)
    id = id[id.length - 1] 
    // On peut donc recuperer la div
    let divToDelete = document.getElementsByClassName('edit--form-div-activity-'+id)[0]
    // puis la supprimer
    let divAddActivity = document.getElementById('edit--activities-container')
    divAddActivity.removeChild(divToDelete)
    
    // On actusalise l'input qui contient le nb d'activité
    NB_ACTIVITY_EDIT = NB_ACTIVITY_EDIT - 1;
    document.getElementById('edit--nbactivity').value = NB_ACTIVITY_EDIT

    SELECT_ID_EDIT = SELECT_ID_EDIT - 1;
}

/**
 * Permet d'afficher la fenêtre modale d'édition
 * => remplit les inputs suivants :
 * Nombre d'activité déjà présentes
 * Id du pathway
 * Nom du pathway
 * Les noms et durée des activités déjà présentes dans le pathway
 */
function showEditModalForm(id, name, index){
    // Affichage de la fenetre modale 
    $('#edit--pathway-modal').modal("show");


    // On recupère la div qui contient nos activités
    let divAddActivity = document.getElementById('edit--activities-container')
    divAddActivity.innerHTML = ''   // On supprime toutes les activités qui existent dans cette div

    // on définit nos variables 
    SELECT_ID_EDIT = 0
    NB_ACTIVITY_EDIT = activitiesByPathways[index].activities.length
    // input contenant le nombre d'activité (on le definit avec le nombre d'activité déjà présente dans le pathway)
    document.getElementById('edit--nbactivity').value = NB_ACTIVITY_EDIT
    //document.getElementById('edit--nbactivity-toedit').value = NB_ACTIVITY_EDIT


    // On set nos input lié au pathway

    // input contenant l'id du pathway
    document.getElementById('edit--pathwayid').value = activitiesByPathways[index].idPathway

    //input contenant le nom du pathway
    document.getElementById('edit--pathwayname').value = name


    // Puis on créé nos activités : 

    for (let i = 0; i < activitiesByPathways[index].activities.length ; i++) {
        // Création d'une div qui contient les inputs pour le nom de l'activité la durée et le btn de suppression
        let div = document.createElement("div")
        div.setAttribute('class', 'form-field')

        // input de l'id de l'activité utile pour editer les activités au lieu de toutes les supprimer (a revoir)
        /*
        let inputIdActivity = document.createElement('input')
        inputIdActivity.setAttribute('name', 'edit--activity-id-'+i)
        inputIdActivity.style.display = "none";
        inputIdActivity.value = activitiesByPathways[index].activities[i].idActivity
        */

        // input du nom de l'activité
        let inputName = document.createElement('input')
        inputName.setAttribute('class', 'input-name')
        inputName.setAttribute('placeholder', 'Nom')
        inputName.setAttribute('onchange', 'edit__disableSubmit()')
        inputName.value = activitiesByPathways[index].activities[i].name
        //inputName.setAttribute('name', 'name-activity-'+SELECT_ID)

        // input de la durée de l'activité
        let inputDuration = document.createElement('input')
        inputDuration.setAttribute('class', 'input-duration')
        inputDuration.setAttribute('placeholder', 'Durée (min)')
        inputDuration.setAttribute('type', 'number')
        inputDuration.setAttribute('min', '0')
        inputDuration.setAttribute('onchange', 'edit__disableSubmit()')
        inputDuration.value = activitiesByPathways[index].activities[i].duration
        //inputDuration.setAttribute('name', 'duration-activity-'+SELECT_ID)

        // image pour supprimer l'activité
        let img = new Image();
        img.src = 'img/delete.svg'
        img.setAttribute('id','edit--img-'+SELECT_ID_EDIT)
        img.setAttribute('onclick', 'edit__deleteSelect(this.id)')

        // on ajoute les inputs et l'image dans une div
        div.appendChild(inputName)
        div.appendChild(inputDuration)
        //div.appendChild(inputIdActivity)
        div.appendChild(img)

        // Puis on ajoute cette div a la fin de la balise div activities-container (qui contient toutes les activités)
        let divAddActivity = document.getElementById('edit--activities-container')

        let divcontainer = document.createElement('div')
        //divcontainer.setAttribute('class', "title-container")
        //divcontainer.setAttribute('class', 'flex-row')
        divcontainer.style.justifyContent = "center"
        let pTitle = document.createElement("p")
        pTitle.setAttribute('class', 'label')
        pTitle.innerHTML = "Activité : "
       // let divclass = divcontainer.getAttribute('class')  //ajouter la classe 'div-activity-(id)' en plusde form-field a div
        divcontainer.setAttribute('class', 'edit--form-div-activity-'+SELECT_ID_EDIT)
        divcontainer.appendChild(pTitle)
        divcontainer.appendChild(div)
        divAddActivity.appendChild(divcontainer)

        SELECT_ID_EDIT = SELECT_ID_EDIT + 1
    }
}


/**
 * Gestion d'ajout d'activité dans un parcours pour le formulaire d'édition
 */
function edit__handleAddActivity() {

    document.getElementById('edit--pathwayid')

    NB_ACTIVITY_EDIT = NB_ACTIVITY_EDIT + 1;
    document.getElementById('edit--nbactivity').value = NB_ACTIVITY_EDIT

    edit__disableSubmit();
    
    // Création d'une div qui contient les inputs pour le nom de l'activité la durée et le btn de suppression
    let div = document.createElement("div")
    div.setAttribute('class', 'form-field')

    let inputName = document.createElement('input')
    inputName.setAttribute('class', 'input-name')
    inputName.setAttribute('placeholder', 'Nom')
    inputName.setAttribute('onchange', 'edit__disableSubmit()')
    //inputName.setAttribute('name', 'name-activity-'+SELECT_ID)

    let inputDuration = document.createElement('input')
    inputDuration.setAttribute('class', 'input-duration')
    inputDuration.setAttribute('placeholder', 'Durée (min)')
    inputDuration.setAttribute('type', 'number')
    inputDuration.setAttribute('min', '0')
    inputDuration.setAttribute('onchange', 'edit__disableSubmit()')
    //inputDuration.setAttribute('name', 'duration-activity-'+SELECT_ID)

    let img = new Image();
    img.src = 'img/delete.svg'
    img.setAttribute('id','edit--img-'+SELECT_ID_EDIT)
    img.setAttribute('onclick', 'edit__deleteSelect(this.id)')

    div.appendChild(inputName)
    div.appendChild(inputDuration)
    div.appendChild(img)

    // On l'affiche et on l'ajoute a la fin de la balise div activities-container
    //select.style.display = "block";
    let divAddActivity = document.getElementById('edit--activities-container')

    let divcontainer = document.createElement('div')
    //divcontainer.setAttribute('class', "title-container")
    //divcontainer.setAttribute('class', 'flex-row')
    divcontainer.style.justifyContent = "center"
    let pTitle = document.createElement("p")
    pTitle.innerHTML = "Activité : "
    pTitle.setAttribute('class', 'label')
    //let divclass = divcontainer.getAttribute('class')  //ajouter la classe 'div-activity-(id)' en plusde form-field a div
    divcontainer.setAttribute('class', 'edit--form-div-activity-'+SELECT_ID_EDIT)
    divcontainer.appendChild(pTitle)
    divcontainer.appendChild(div)
    divAddActivity.appendChild(divcontainer)

    SELECT_ID_EDIT++
} 

/**
 * Permet de verifier les champs et de leur donner un 'name' pour la requete
 */
function edit__verifyChanges() {

    let formOk = true
    // D'abord on recupere la div qui contient toutes les activity
    let activitiesContainer = document.getElementById('edit--activities-container')

    // On parcours toutes nos activités 
    // On set leur 'name' et on verifie leurs contenus
    for (let i = 0; i < NB_ACTIVITY_EDIT; i++) {
        console.log( activitiesContainer.children[i].children[1])
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

    if (document.getElementById('edit--pathwayname').value === '') {
        formOk = false
    }

    if (formOk) {
        let btnSubmit = document.getElementById('edit--submit')
        btnSubmit.disabled = false;
    }
}