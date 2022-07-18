var SELECT_ID = 0;
var NB_CATEGORY = 0;
var WORKING_HOURS;

document.addEventListener('DOMContentLoaded', () => {
    WORKING_HOURS = JSON.parse(document.getElementById('working-hours-content').value)    
})

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
    if(selectSample.length != 0) {
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

else {
    alert('Il n\'y a pas de catégories existantes !')
}

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
 function humanResourceVerify() {

    // D'abord on recupere la div qui contient toutes les activity
    let categoriesContainer = document.getElementById('categories-container')
    let btnAdd = document.getElementById('btn-none-add-human-resource')
    let divWorkingHoursBegin = document.getElementById('working-hours-input-begin')
    let divWorkingHoursEnd = document.getElementById('working-hours-input-end')
    let pbWorkingHoursSolo = false;
    let endHigherThanBegin = false;
    let nbCategory = document.getElementById('nbCategory');
    var nbCateg = 0;
    // On parcours toutes nos activités 
    // On set leur 'name' et on verifie leurs contenus
    for (let i = 0; i <= categoriesContainer.children.length-1; i++) {
        if(categoriesContainer.children[i].children[0].checked) {
        categoriesContainer.children[i].children[0].setAttribute('name', 'id-category-'+ nbCateg)
        categoriesContainer.children[i].children[0].setAttribute('id', 'id-category-' + nbCateg) 
        categoriesContainer.children[i].children[1].setAttribute('id', 'lbl-category-' + nbCateg)
        nbCateg = nbCateg +1;
        }
        
    } 

    for (let j = 0; j <= 6; j++)
    {
        if((divWorkingHoursBegin.children[j].value == '' && divWorkingHoursEnd.children[j].value != '') || (divWorkingHoursBegin.children[j].value  != '' && divWorkingHoursEnd.children[j].value == '')){
            pbWorkingHoursSolo = true;
        }
        if((divWorkingHoursBegin.children[j].value > divWorkingHoursEnd.children[j].value)) {
            endHigherThanBegin = true;
        }
    }
    nbCategory.value = nbCateg
    if(pbWorkingHoursSolo == true) {
        alert('Veuillez saisir l\'heure de début et de fin, ou aucun des deux horaires pour chaque jour de disponibilité !')
    }
    else if(endHigherThanBegin == true) {
        alert('Veuillez saisir des horaires de début antérieures à celles de fin pour chaque jour de disponibilité !')
    }
    else {
        btnAdd.click();
    }

    
}

function materialResourceVerify() {

    // D'abord on recupere la div qui contient toutes les activity
    let categoriesContainer = document.getElementById('categories-container')
    let btnAdd = document.getElementById('btn-none-add-human-resource')
    let nbCategory = document.getElementById('nbCategory');
    var nbCateg = 0;
    // On parcours toutes nos activités 
    // On set leur 'name' et on verifie leurs contenus
    for (let i = 0; i <= categoriesContainer.children.length-1; i++) {
        if(categoriesContainer.children[i].children[0].checked) {
        categoriesContainer.children[i].children[0].setAttribute('name', 'id-category-'+ nbCateg)
        categoriesContainer.children[i].children[0].setAttribute('id', 'id-category-' + nbCateg) 
        categoriesContainer.children[i].children[1].setAttribute('id', 'lbl-category-' + nbCateg)
        nbCateg = nbCateg +1;
        }
        
    }
    nbCategory.value = nbCateg
    btnAdd.click();    
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

function change_tab_material(id)
{
  document.getElementById("resources").className="notselected";
  document.getElementById("categories").className="notselected";
  document.getElementById(id).className="selected";
  let resources = document.getElementById("list-material-resources")
  let categories = document.getElementById("list-material-categories")
  if(id == 'resources') {
    categories.style.display = 'none'
    resources.style.display = 'block'
  }
  else {
    categories.style.display = 'block'
    resources.style.display = 'none'

  }
}

function change_tab_human(id)
{
  document.getElementById("resources").className="notselected";
  document.getElementById("categories").className="notselected";
  document.getElementById(id).className="selected";
  let resources = document.getElementById("list-human-resources");
  let categories = document.getElementById("list-human-categories");
  if(id == 'resources') {
    categories.style.display = 'none';
    resources.style.display = 'block';
  }
  else {
    categories.style.display = 'block';
    resources.style.display = 'none';

  }
}
// #container-modal 
// .modal-form             #form-add-activity