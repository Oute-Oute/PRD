var SELECT_ID = 0;
var NB_CATEGORY = 0;

function showInfosModalHuman(resourceId, resourceName, resourceType) {
    document.getElementById('human-resource-id').innerText = resourceId
    document.getElementById('human-resource-name').innerText = resourceName
    document.getElementById('human-resource-available').innerText = resourceType
    $('#infos-human-resource-modal').modal("show");
}

function showInfosModalMaterial(resourceId, resourceName) {
    document.getElementById('material-resource-id').innerText = resourceId
    document.getElementById('material-resource-name').innerText = resourceName
    $('#infos-material-resource-modal').modal("show");
}

function showInfosModalHumanCateg(humanResourceCategId, humaneResourceCategName) {
    document.getElementById('human-resource-category-id').innerText = humanResourceCategId
    document.getElementById('human-resource-category-name').innerText = humaneResourceCategName
    $('#infos-human-resource-category-modal').modal("show");
}

function showInfosModalMaterialCateg(materialResourceCategId, materialResourceCategName) {

    document.getElementById('material-resource-category-id').innerText = materialResourceCategId
    document.getElementById('material-resource-category-name').innerText = materialResourceCategName
    $('#infos-material-resource-category-modal').modal("show");
}

function showNewHumanModalForm(){
    $('#new-human-resource-modal').modal("show");
}

function showNewMaterialModalForm(){
    $('#new-material-resource-modal').modal("show");
}

function showNewHumanCategModalForm() {
    $('#new-human-resource-category-modal').modal("show");
}

function showNewMaterialCategModalForm() {
    $('#new-material-resource-category-modal').modal("show");
}

function showEditHumanCategModalForm(id, name) {
    document.getElementById('idcategoryedit').value = id;
    document.getElementById('categorynameedit').value = name;
 $('#edit-human-resource-category-modal').modal("show");
}

function showEditMaterialCategModalForm(id, name) {
    document.getElementById('idcategoryedit').value = id;
    document.getElementById('categorynameedit').value = name;
$('#edit-material-resource-category-modal').modal("show");
}
/** 
 * Permet d'ajouter une ou plusieurs catégories de ressources à une ressource humaine lors de sa création.
 */
function handleAddHumanCategory() {

    let selectSample = document.getElementsByClassName('select-category-sample')[0];
    let newSelect = document.createElement('select');
    let btnSubmit = document.getElementById('submit')
    newSelect.addEventListener('change', function() {
            btnSubmit.disabled = true;
      });
    
    //Boucle pour remplir toutes les categs dans chaque select
    for (let i = 0; i < selectSample.length; i++){
        let option = document.createElement('option')
        option.value = selectSample.options[i].value;
        option.text = selectSample.options[i].text;
        newSelect.add(option);
    }

    newSelect.setAttribute('name' , 'select-'+SELECT_ID);
    let categoriesContainer = document.getElementById('categories-container');
    let formField = document.createElement("div");
    formField.setAttribute('class', 'form-field category-'+SELECT_ID);
    newSelect.style.border = 'none';
    //Image pour delete une categ
    let image = new Image();
    image.src = 'img/delete.svg';
    image.style.marginLeft = '30px';
    image.setAttribute('id','img-'+SELECT_ID)
    image.setAttribute('onclick', 'deleteSelect(this.id)')
    formField.appendChild(newSelect);
    formField.appendChild(image);
    categoriesContainer.appendChild(formField);
    let nbCategory = document.getElementById('nbCategory');
    SELECT_ID = SELECT_ID +1;
    console.log(SELECT_ID)

    NB_CATEGORY = NB_CATEGORY +1;
    nbCategory.value = NB_CATEGORY;

}
/** Permet de supprimer un select dans la liste déroulante */
function deleteSelect(id) {


    // On récupère le numero de la div a supprimer  
    // Pour cela on recupere que le dernier caracetere de l'id de l'img : (img-1)
    id = id[id.length - 1] 
    // On peut donc recuperer la div
    let divToDelete = document.getElementsByClassName('category-'+id)[0]
    // puis la supprimer
    let divAddCategory = document.getElementById('categories-container')
    divAddCategory.removeChild(divToDelete)
    
    let nbCategory = document.getElementById('nbCategory');
    SELECT_ID = SELECT_ID - 1;
    console.log(SELECT_ID)
    NB_CATEGORY = NB_CATEGORY -1;
    nbCategory.value = NB_CATEGORY;
}


/**
 * Permet de verifier les champs et de leur donner un 'name' pour la requete
 */
 function verifyChanges() {

    let formOk = true
    // D'abord on recupere la div qui contient toutes les activity
    let categoriesContainer = document.getElementById('categories-container')
    
    // On parcours toutes nos activités 
    // On set leur 'name' et on verifie leurs contenus
    for (let i = 1; i <= NB_CATEGORY; i++) {
        categoriesContainer.children[i].children[0].setAttribute('name', 'id-category-'+ Number(i-1))
        categoriesContainer.children[i].children[0].setAttribute('id', 'id-category-'+ Number(i-1))        
    }
    let categoriesCheckDuplicata = []
    for (let i = 1; i < NB_CATEGORY+1; i++) {
        let category = document.getElementById('id-category-'+ Number(i-1))
        categoriesCheckDuplicata.push(category.value);
    }

    if(hasDuplicates(categoriesCheckDuplicata) == false) {
        if (document.getElementById('resourcename').value === '') {
            formOk = false
        }
    
        if (formOk) {
            let btnSubmit = document.getElementById('submit')
            btnSubmit.disabled = false;
        }
    }

    else {
        alert("Il y a plusieurs fois la même catégorie !")
    }
    
    
}

function hasDuplicates(array) {
    var valuesSoFar = Object.create(null);
    for (var i = 0; i < array.length; ++i) {
        var value = array[i];
        if (value in valuesSoFar) {
            return true;
        }
        valuesSoFar[value] = true;
    }
    return false;
}

function hideNewModalForm() {
    $('#new-human-resource-category-modal').modal("hide");
    $('#new-human-resource-modal').modal("hide");
    $('#new-material-resource-category-modal').modal("hide");
    $('#new-material-resource-modal').modal("hide");
}

function hideEditModalForm() {
    $('#edit-human-resource-category-modal').modal("hide");
    $('#edit--human-resource-modal').modal("hide");
    $('#edit--material-resource-category-modal').modal("hide");
    $('#edit--material-resource-modal').modal("hide");
}
// #container-modal 
// .modal-form             #form-add-activity