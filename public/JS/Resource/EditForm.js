// SLEECT ID : correspond a l'indice de l'activité à créée 0, 1, 2... 
var SELECT_ID_EDIT = 0
// NB ACTIVITY : nombre totale d'activité
var NB_CATEGORY_EDIT = 0


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

    // On récupère le numero de la div a supprimer  
    // Pour cela on recupere que le dernier caracetere de l'id de l'img : (img-1)
    id = id[id.length - 1] 
    // On peut donc recuperer la div
    let divToDelete = document.getElementsByClassName('category-'+id)[0]
    // puis la supprimer
    let divAddCategory = document.getElementById('edit--categories-container')
    divAddCategory.removeChild(divToDelete)
    // On actusalise l'input qui contient le nb d'activité
    NB_CATEGORY_EDIT = NB_CATEGORY_EDIT - 1;
    document.getElementById('edit--nbcategory').value = NB_CATEGORY_EDIT

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
        
    SELECT_ID_EDIT = 0;
    // Affichage de la fenetre modale 
    $('#edit--human-resource-modal').modal("show");
    let categoriesContainer = document.getElementById('edit--categories-container');

    document.getElementById('edit--resourceid').value = id;
    document.getElementById('edit--resourcename').value = name
    NB_CATEGORY_EDIT = 0;
    let categoriesId = []
    
    for (let j = 0; j < categoriesByResources[index].categories.length; j++) {
        categoriesId.push(categoriesByResources[index].categories[j].idCategory)
    }

    for (let i = 0; i <= categoriesContainer.children.length; i++) {
        categoriesContainer.children[i].children[0].checked = false;

        if(categoriesId.includes(categoriesContainer.children[i].children[0].value)) {
            categoriesContainer.children[i].children[0].checked = true;
        }
    }

    
    }



    


/**
* Permet d'afficher la fenêtre modale d'édition
* => remplit les inputs suivants :
* Nombre d'activité déjà présentes
* Id du pathway
* Nom du pathway
* Les noms et durée des activités déjà présentes dans le pathway
*/
function showEditModalFormMaterial(id, name, index){
    SELECT_ID_EDIT = 0;
    // Affichage de la fenetre modale 
    $('#edit--material-resource-modal').modal("show");
    let categoriesContainer = document.getElementById('edit--categories-container');

    document.getElementById('edit--resourceid').value = id;
    document.getElementById('edit--resourcename').value = name
    NB_CATEGORY_EDIT = 0;
    let categoriesId = []
    
    for (let j = 0; j < categoriesByResources[index].categories.length; j++) {
        categoriesId.push(categoriesByResources[index].categories[j].idCategory)
    }

    for (let i = 0; i <= categoriesContainer.children.length; i++) {
        categoriesContainer.children[i].children[0].checked = false;

        if(categoriesId.includes(categoriesContainer.children[i].children[0].value)) {
            categoriesContainer.children[i].children[0].checked = true;
        }
    }
   
}

/**
 * Gestion d'ajout d'activité dans un parcours pour le formulaire d'édition
 */
function edit__handleAddCategory() {
    let selectSample = document.getElementsByClassName('select-category-sample')[0];
    if(selectSample.length != 0) {

        let newSelect = document.createElement('select');
        let btnSubmit = document.getElementById('edit--submit')
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
        let categoriesContainer = document.getElementById('edit--categories-container');
        let formField = document.createElement("div");
        formField.setAttribute('class', 'form-field category-'+SELECT_ID_EDIT);
        newSelect.style.border = 'none';
        //Image pour delete une categ
        let image = new Image();
        image.src = 'img/delete.svg';
        image.style.marginLeft = '30px';
        image.setAttribute('id','img-'+SELECT_ID_EDIT)
        image.setAttribute('onclick', 'edit__deleteSelect(this.id)')
        formField.appendChild(newSelect);
        formField.appendChild(image);
        categoriesContainer.appendChild(formField);
        SELECT_ID_EDIT = SELECT_ID_EDIT +1;

        NB_CATEGORY_EDIT = NB_CATEGORY_EDIT +1;
        document.getElementById('edit--nbcategory').value = NB_CATEGORY_EDIT;
    }
    else {
        alert('Il n\'y a pas de catégories existantes !')
    }
} 

/**
 * Permet de verifier les champs et de leur donner un 'name' pour la requete
 */
 function edit__verifyChanges() {
    // D'abord on recupere la div qui contient toutes les activity
    let categoriesContainer = document.getElementById('edit--categories-container')
    let btnAdd = document.getElementById('btn-none-edit-resource')
    let nbCategory = document.getElementById('edit--nbcategory');
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
        
    nbCategory.value = nbCateg;
    btnAdd.click();

}