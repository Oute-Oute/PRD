
// SLEECT ID : correspond a l'indice de l'activité à créée 0, 1, 2... 
var SELECT_ID_EDIT = 0
// NB ACTIVITY : nombre totale d'activité
var NB_CATEGORY_EDIT = 0




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
    let divToDelete = document.getElementsByClassName('edit--form-div-category-'+id)[0]
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
    SELECT_ID = 0;
    // Affichage de la fenetre modale 
    $('#edit--human-resource-modal').modal("show");

    document.getElementById('edit--resourceid').value = id;
    document.getElementById('edit--resourcename').value = name
    NB_CATEGORY_EDIT = NB_CATEGORY_EDIT + 1;
    document.getElementById('edit--nbcategory').value = NB_CATEGORY_EDIT

    let selectSample = document.getElementsByClassName('select-category-sample')[0];


    // On recupère la div qui contient nos activités
    let categoriesContainer = document.getElementById('edit--categories-container');
    categoriesContainer.innerHTML = ''   // On supprime toutes les activités qui existent dans cette div
    
    for(let j = 0; j < categoriesByResources[index].categories.length; j++) {

        let newSelect = document.createElement('select');

        //Boucle pour remplir toutes les categs dans chaque select
        for (let i = 0; i < selectSample.length; i++){
            let option = document.createElement('option')
            option.value = selectSample.options[i].value;
            option.text = selectSample.options[i].text;
            //trouver comment pré-sélectionner l'élément du select (jquery?)
            newSelect.add(option);        
        }

        newSelect.setAttribute('name' , 'select-'+SELECT_ID);

        let formField = document.createElement("div");
        formField.setAttribute('class', 'form-field category-'+SELECT_ID);

        //style pour le select
        newSelect.style.border = 'none';
        //Image pour delete une categ
        let image = new Image();
        image.src = 'img/delete.svg';
        image.style.marginLeft = '30px';
        image.setAttribute('id','img-'+SELECT_ID)
        image.setAttribute('onclick', 'edit__deleteSelect(this.id)')
        formField.appendChild(newSelect);
        formField.appendChild(image);
        console.log(formField)
        console.log(categoriesContainer)
        categoriesContainer.appendChild(formField);

        SELECT_ID = SELECT_ID +1;

    }

    let nbCategory = document.getElementById('edit--nbcategory');
    nbCategory.value = SELECT_ID;

    
}


/**
 * Gestion d'ajout d'activité dans un parcours pour le formulaire d'édition
 */
function edit__handleAddCategory() {

    document.getElementById('edit--resourceid')
    let selectSample = document.getElementsByClassName('select-category-sample')[0];
    let newSelect = document.createElement('select');
    NB_CATEGORY_EDIT = NB_CATEGORY_EDIT + 1;
    document.getElementById('edit--nbcategory').value = NB_CATEGORY_EDIT

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
    nbCategory.value = SELECT_ID;


    // On l'affiche et on l'ajoute a la fin de la balise div activities-container
    //select.style.display = "block";
    let divAddCategory = document.getElementById('edit--categories-container')

    let divcontainer = document.createElement('div')
    //divcontainer.setAttribute('class', "title-container")
    divcontainer.setAttribute('class', 'flex-row')
    divcontainer.style.justifyContent = "center"
    let pTitle = document.createElement("p")
    pTitle.innerHTML = "Catégorie : "
    let divclass = divcontainer.getAttribute('class')  //ajouter la classe 'div-activity-(id)' en plusde form-field a div
    divcontainer.setAttribute('class', divclass + ' edit--form-div-category-'+SELECT_ID_EDIT)
    divcontainer.appendChild(pTitle)
    divcontainer.appendChild(div)
    divAddCategory.appendChild(divcontainer)

    SELECT_ID_EDIT++
} 
