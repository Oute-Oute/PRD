var SELECT_ID = 0;
var NB_CATEGORY = 0;
var hrArray = [];
var hcrArray = [];
var mrArray = [];
var mcrArray = [];


document.addEventListener('DOMContentLoaded', () => {
})

document.addEventListener('DOMContentLoaded', () => {
})

/**
  * Allows to display the modal that is used to create a new human resource
 */
function showNewHumanModalForm(){
    $('#new-human-resource-modal').modal("show");
}

/**
  * Allows to display the modal that is used to create a new material resource
 */
function showNewMaterialModalForm(){
    $('#new-material-resource-modal').modal("show");
}

/**
  * Allows to display the modal that is used to create a new category of human resource
 */
function showNewHumanCategModalForm() {
    $('#new-human-resource-category-modal').modal("show");
}


/**
  * Allows to display the modal that is used to create a new category of material resource
 */
function showNewMaterialCategModalForm() {
    $('#new-material-resource-category-modal').modal("show");
}


/**
  * Allows to display the modal that is used to edit a human resource category
 */
function showEditHumanCategModalForm(id, name) {
    document.getElementById('idcategoryedit').value = id;
    document.getElementById('categorynameedit').value = name;
 $('#edit-human-resource-category-modal').modal("show");
}


/**
  * Allows to display the modal that is used to edit material resource category
 */
function showEditMaterialCategModalForm(id, name) {
    document.getElementById('idcategoryedit').value = id;
    document.getElementById('categorynameedit').value = name;
$('#edit-material-resource-category-modal').modal("show");
}

/**
 * Allows to check fields in the modal form to create a human resource
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


/**
 * Allows to check fields in the modal form to create a material resource
 */
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


/**
 * Allows to check fields in the edit modal form to create a human resource
 */
function materialResourceVerifyEdit() {

    // D'abord on recupere la div qui contient toutes les activity
    let categoriesContainer = document.getElementById('categories-container')
    let btnAdd = document.getElementById('btn-none-edit-human-resource')
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


/**
 * Allows to hide a create modal form. Called when you click somewhere else than the modal
 */
function hideNewModalForm() {
    $('#new-human-resource-category-modal').modal("hide");
    $('#new-human-resource-modal').modal("hide");
    $('#new-material-resource-category-modal').modal("hide");
    $('#new-material-resource-modal').modal("hide");
}

/**
 * Allows to hide an edit modal form. Called when you click somewhere else than the modal
 */
function hideEditModalForm() {

    $('#edit-human-resource-category-modal').modal("hide");
    $('#edit--human-resource-modal').modal("hide");
    $('#edit-material-resource-category-modal').modal("hide");
    $('#edit--material-resource-modal').modal("hide");
    $('#edit--unavailability-material-resource-modal').modal("hide");
    $('#edit--unavailability-human-resource-modal').modal("hide");

}

/**
 * Allows to change the selected tab in the material resource page
 */
function change_tab_material(id)
{
  document.getElementById("resources").className="notselected";
  document.getElementById("categories").className="notselected";
  var paginator=document.getElementById('paginator');
  document.getElementById(id).className="selected";
  
  let resources = document.getElementById("list-material-resources")
  let categories = document.getElementById("list-material-categories")
  if(id == 'resources') {
    categories.style.display = 'none'
    resources.style.display = 'block'
    paginator.style.display=''; 
  }
  else {
    categories.style.display = 'block'
    resources.style.display = 'none'
    paginator.style.display='none';
  }
}

/**
 * Allows to change the selected tab in the human resource page
 */
function change_tab_human(id)
{
  document.getElementById("resources").className="notselected";
  document.getElementById("categories").className="notselected";
   var paginator=document.getElementById('paginator');
  document.getElementById(id).className="selected";
  let resources = document.getElementById("list-human-resources");
  let categories = document.getElementById("list-human-categories");
  if(id == 'resources') {
    categories.style.display = 'none';
    resources.style.display = 'block';
    paginator.style.display='';
  }
  else {
    categories.style.display = 'block';
    resources.style.display = 'none';
    paginator.style.display='none';
  }
}

/**
 * Allows to filter resources according to the categories
 */
function filterResource(type,selected=null) {
    var categoriesStr=[];
    for (var i=0;i<selected["categories"].length-1;i++){
        categoriesStr += selected["categories"][i]["category"]+", ";
    }
    if(selected["categories"].length>0){
    categoriesStr += selected["categories"][selected["categories"].length-1]["category"];
    }
    var Type=type.charAt(0).toUpperCase()+type.slice(1); //equal to type.capitalize()
    var trs = document.querySelectorAll('#table'+Type+'Resource tr:not(.header'+Type+'Resource)');
    for(let i=0; i<trs.length; i++){
            trs[i].style.display='none';
    }
    table=document.getElementById(type+'Table');
    var tr=document.createElement('tr');
    table.appendChild(tr);
    var resourceName=document.createElement('td');
    resourceName.append(selected.value);
    tr.appendChild(resourceName);
    var categoriestd=document.createElement('td');
    categoriestd.append(categoriesStr);
    tr.appendChild(categoriestd);
    var buttons=document.createElement('td');
    var infos=document.createElement('button');
    infos.setAttribute('class','btn-infos btn-secondary');
    infos.setAttribute('onclick','showInfosModal'+Type+'('+selected.id+',"'+selected.value+'")');
    infos.append('Informations');
    var edit=document.createElement('button');
    edit.setAttribute('class','btn-edit btn-secondary');
    edit.setAttribute('onclick','showEditModalForm'+Type+'('+selected.id+',"'+selected.value+'")');
    edit.append('Editer');
    var unavailabilities=document.createElement('button');
    unavailabilities.setAttribute('class','btn-add btn-secondary');
    unavailabilities.setAttribute('onclick','showUnavailability'+Type+'('+selected.id+',"'+selected.value+'")');
    unavailabilities.append('Indisponibilités');
    var formDelete=document.createElement('form');
    formDelete.setAttribute('action',"/"+type+"/resource/"+selected.id);
    formDelete.setAttribute('style','display:inline');
    formDelete.setAttribute('method','POST');
    formDelete.setAttribute('id','formDelete'+selected.id);
    formDelete.setAttribute('onsubmit','return confirm("Voulez-vous vraiment supprimer cette ressource ?")');
    var inputHidden=document.createElement('input');
    inputHidden.setAttribute('type','hidden');
    inputHidden.setAttribute('name','pathwayid');
    inputHidden.setAttribute('value',selected.id);
    formDelete.appendChild(inputHidden);
    var deleteButton=document.createElement('button');
    deleteButton.setAttribute('class','btn-delete btn-secondary');
    deleteButton.append('Supprimer');
    deleteButton.setAttribute('type','submit');
    buttons.appendChild(infos);
    buttons.appendChild(unavailabilities);
    buttons.appendChild(edit);
    formDelete.appendChild(deleteButton);
    buttons.appendChild(formDelete);
    tr.appendChild(buttons);
  }

  function displayAll(type) {
    var trs = document.querySelectorAll('#table'+type+'Resource tr:not(.header'+type+'Resource)');
    var input = document.getElementById('autocompleteInput'+type+'Name');
    if(input.value == ''){
    for(let i=0; i<trs.length; i++){
        if(trs[i].style.display == 'none'){
            trs[i].style.display='table-row';
        }
        else if(trs[i].className != 'original'){
            trs[i].remove()
        }
    }
}
}

/**
 * Allows to filter human resources according to entered category name
 */
  function filterHumanResourceCategory(idInput,selected=null){
    var trs = document.querySelectorAll('#tableHumanResourceCategory tr:not(.headerHumanResourceCategory)');
    if(selected == null){
        var filter = document.querySelector('#'+idInput).value; 
        }
        else{
            var filter = selected;
        }
    console.log(filter);
    for(let i=0; i<trs.length; i++){
        var regex = new RegExp(filter, 'i');   
        var name=trs[i].cells[0].outerText;
        if(hcrArray.indexOf(name) == -1){
            hcrArray.push(name);
            }
        if(regex.test(name)==false){
            trs[i].style.display='none';
        }
        else{
            trs[i].style.display=''; 
        }
    }
  }

  
/**
 * Allows to filter material resources according to entered category name
 */
  function filterMaterialResourceCategory(idInput,selected=null){
    var trs = document.querySelectorAll('#tableMaterialResourceCategory tr:not(.headerMaterialResourceCategory)');
    if(selected == null){
        var filter = document.querySelector('#'+idInput).value; 
        }
        else{
            var filter = selected;
        }
    for(let i=0; i<trs.length; i++){
        var regex = new RegExp(filter, 'i');   
        var name=trs[i].cells[0].outerText;
        if(mcrArray.indexOf(name) == -1){
            mcrArray.push(name);
            }
        if(regex.test(name)==false){
            trs[i].style.display='none';
        }
        else{
            trs[i].style.display=''; 
        }
    }
  }