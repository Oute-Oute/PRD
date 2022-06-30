var SELECT_ID_EDIT = 0
var NB_CATEGORY_EDIT = 0

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
    let divToDelete = document.getElementsByClassName('edit-form-div-resource-category-'+id)[0]
    // puis la supprimer
    let divAddCategory = document.getElementById('edit-categories-container')
    divAddCategory.removeChild(divToDelete)
    
    // On actusalise l'input qui contient le nb d'activité
    NB_CATEGORY_EDIT = NB_CATEGORY_EDIT - 1;
    document.getElementById('nbCategory_EditForm').value = NB_CATEGORY_EDIT

    SELECT_ID_EDIT = SELECT_ID_EDIT - 1;
}

/**
 * Permet d'afficher la fenêtre modale d'édition
 */
function showEditModalFormHumanResource(id, name, index){
    $('#edit-human-resource-modal').modal("show");
    let divAddCategory = document.getElementById('edit-categories-container')
    //divAddCategory.innerHTML = ''

    SELECT_ID_EDIT = 0
    NB_CATEGORY_EDIT = categoriesByResource.length

    document.getElementById('edit-resourcename').value = name
    for (let i = 0; i < categoriesByResource.length ; i++) {
        // Création d'une div qui contient les inputs pour le nom de l'activité la durée et le btn de suppression
        let div = document.createElement("div")
        div.setAttribute('class', 'form-field')

        let inputName = document.createElement('input')
        inputName.setAttribute('class', 'input-name')
        inputName.setAttribute('placeholder', 'Nom')
        inputName.setAttribute('onchange', 'disableSubmit_EditForm()')
        inputName.value = categoriesByResource[index].categories[i].name

        let img = new Image();
        img.src = 'img/delete.svg'
        img.setAttribute('id','edit-form-img-'+SELECT_ID_EDIT)
        img.setAttribute('onclick', 'deleteSelect_EditForm(this.id)')

        div.appendChild(inputName)
        div.appendChild(img)

        // On l'affiche et on l'ajoute a la fin de la balise div activities-container
        //select.style.display = "block";
        let divAddCategory = document.getElementById('edit-activities-container')

        let divcontainer = document.createElement('div')
        divcontainer.setAttribute('class', 'flex-row')
        divcontainer.style.justifyContent = "center"
        let pTitle = document.createElement("p")
        pTitle.innerHTML = "Catégorie : "
        let divclass = divcontainer.getAttribute('class')  //ajouter la classe 'div-activity-(id)' en plusde form-field a div
        divcontainer.setAttribute('class', divclass + ' edit-form-div-activity-'+SELECT_ID_EDIT)
        divcontainer.appendChild(pTitle)
        divcontainer.appendChild(div)
        divAddCategory.appendChild(divcontainer)

        SELECT_ID_EDIT = SELECT_ID_EDIT + 1
    }
}


/**
 * Permet d'ajouter une liste déroulante pour choisir une activité lors de la cration d'un parcours (pathway)
 */
function handleAddCategory_EditForm() {

    NB_CATEGORY_EDIT = NB_CATEGORY_EDIT + 1;
    document.getElementById('nbCategory').value = NB_CATEGORY_EDIT

    disableSubmit_EditForm();
    
    // Création d'une div qui contient les inputs pour le nom de l'activité la durée et le btn de suppression
    let div = document.createElement("div")
    div.setAttribute('class', 'form-field')

    let inputName = document.createElement('input')
    inputName.setAttribute('class', 'input-name')
    inputName.setAttribute('placeholder', 'Nom')
    inputName.setAttribute('onchange', 'disableSubmit_EditForm()')
    //inputName.setAttribute('name', 'name-activity-'+SELECT_ID)

    let img = new Image();
    img.src = 'img/delete.svg'
    img.setAttribute('id','edit-form-img-'+SELECT_ID_EDIT)
    img.setAttribute('onclick', 'deleteSelect_EditForm(this.id)')

    div.appendChild(inputName)
    div.appendChild(img)

    // On l'affiche et on l'ajoute a la fin de la balise div activities-container
    //select.style.display = "block";
    let divAddCategory = document.getElementById('edit-categories-container')

    let divcontainer = document.createElement('div')
    //divcontainer.setAttribute('class', "title-container")
    divcontainer.setAttribute('class', 'flex-row')
    divcontainer.style.justifyContent = "center"
    let pTitle = document.createElement("p")
    pTitle.innerHTML = "Activité : "
    let divclass = divcontainer.getAttribute('class')  //ajouter la classe 'div-activity-(id)' en plusde form-field a div
    divcontainer.setAttribute('class', divclass + ' edit-form-div-category-'+SELECT_ID_EDIT)
    divcontainer.appendChild(pTitle)
    divcontainer.appendChild(div)
    divAddCategory.appendChild(divcontainer)

    SELECT_ID_EDIT++
} 
