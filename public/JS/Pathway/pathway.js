var SELECT_ID = 0;
var NB_ACTIVITY = 0;

/**
 * Appelée au chargement de la page de création d'un parcours (circuit)
 */
document.addEventListener('DOMContentLoaded', () => {
    SELECT_ID = 0;
})

/**
 * Permet d'ajouter une liste déroulante pour choisir une activité lors de la cration d'un parcours (pathway)
 */
function handleAddActivity() {

    NB_ACTIVITY = NB_ACTIVITY + 1;
    document.getElementById('nbActivity').value = NB_ACTIVITY
    
    // Recuperation du select par défaut (deja rempli avec les bonnes options)
    var selectSample = document.getElementsByClassName("select-activity-sample")[0]

    // Création d'un nouveau select et copie des options du select par défaut
    var select = document.createElement("select");
    var pActivityNumber = document.createElement("p");
    pActivityNumber.setAttribute('id', 'activity-number')
    pActivityNumber.style.marginRight = '10px'
    pActivityNumber.innerText =  (SELECT_ID+1) +' : ' 
    
    // Création d'une div pour afficher le numero de l'activité, le select et le bouton de suppression a côté
    var div = document.createElement("div")
    div.setAttribute('class', 'form-field')


    div.appendChild(pActivityNumber)
    div.appendChild(select)
    var img = new Image();
    img.src = 'img/delete.svg'
    img.setAttribute('id','img-'+SELECT_ID)
    img.setAttribute('onclick', 'deleteSelect(this.id)')
    div.appendChild(img)


    // On definit son id : select-1, select-2...
    select.setAttribute('id', 'select-'+SELECT_ID);
    select.setAttribute('name', 'activity-'+SELECT_ID);
    SELECT_ID++

    // On ajoute les options du bon select dans celui que l'on vient de créer
    let len = selectSample.options.length
    for (let i = 0; i < len; i++) {
        select.options[select.options.length] = new Option (selectSample.options[i].text, selectSample.options[i].value);
    }

    // On l'affiche et on l'ajoute a la fin de la balise div select-container
    select.style.display = "block";
    let divAddActivity = document.getElementById('select-container')
    divAddActivity.appendChild(div)
} 

/** Permet de supprimer un select dans la liste déroulante */
function deleteSelect(id) {
    NB_ACTIVITY = NB_ACTIVITY - 1;
    document.getElementById('nbActivity').value = NB_ACTIVITY
    console.log(id)
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
}
