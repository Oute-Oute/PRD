function addPatient(){
    $('#add-patient-modal').modal("show");
}

function editPatient(lastname, firstname) {
    console.log("oui");
    //document.getElementById('edit-patient-modal').getElementById('lastname').value = lastname;
    //document.getElementById('edit-patient-modal').getElementById('firstname').value = firstname;
    $('#edit-patient-modal').modal("show");
}

function showPatient(id) {
    console.log(id);
    $('#show-patient-modal').modal("show");
}

var SELECT_ID = 0;

document.addEventListener('DOMContentLoaded', () => {
    SELECT_ID = 0;
})

function handleAddPathway() {

    // Recuperation du select par défaut (deja rempli avec les bonnes options)
    var selectSample = document.getElementsByClassName("select-pathway-sample")[0]

    // Création d'un nouveau select et copie des options du select par défaut
    var select = document.createElement("select");
    var pActivityNumber = document.createElement("p");
    pActivityNumber.setAttribute('id', 'pathway-number')
    pActivityNumber.style.marginRight = '10px'
    pActivityNumber.innerText = 'Parcours ' + (SELECT_ID+1) 
    
    // Création d'une div pour afficher le numero de l'activité, le select et le bouton de suppression a côté
    var div = document.createElement("div")
    div.style.display = 'flex'
    div.style.flexDirection = 'row'
    div.style.alignItems = 'center'
    div.appendChild(pActivityNumber)
    div.appendChild(select)

    // On definit son id : select-1, select-2...
    select.setAttribute('id', 'select-'+SELECT_ID);
    select.setAttribute('name', 'parcours-'+SELECT_ID);
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