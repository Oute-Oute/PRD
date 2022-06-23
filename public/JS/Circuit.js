
var SELECT_ID = 0;

/**
 * Fonction appelée au chargement de la page de création d'un parcours (circuit)
 */
document.addEventListener('DOMContentLoaded', () => {
    console.log("bonjour");
    SELECT_ID = 0;
})

/**
 * Fonction permettant d'ajouter une liste déroulante pour choisir une activité lors de la cration d'un parcours (circuit)
 */
function handleAddActivity() {

    // s est le select par défaut (déjà rempli avec les bonnes options)
    var selectSample = document.getElementsByClassName("select-activity-sample")[0]

    // select est le nouveau select que l'on veut ajouter
    var select = document.createElement("select");
    var pActivityNumber = document.createElement("p");
    pActivityNumber.setAttribute('id', 'activity-number')
    pActivityNumber.style.marginRight = '10px'
    pActivityNumber.innerText = 'Activité ' + (SELECT_ID+1) 

    var div = document.createElement("div")
    div.style.display = 'flex'
    div.style.flexDirection = 'row'
    div.style.alignItems = 'center'
    div.appendChild(pActivityNumber)
    div.appendChild(select)

    // On definit son id : select-1, select-2...
    select.setAttribute('id', 'select-'+SELECT_ID);
    SELECT_ID++
    
    // On ajoute les options du bon select dans celui que l'on vient de créer
    let len = selectSample.options.length
    for (let i = 0; i < len; i++) {
        //console.log(s.options[i])
        select.options[select.options.length] = new Option (selectSample.options[i].text, selectSample.options[i].value);
    }

    // on l'affiche et on l'ajoute a la fin de la balise div select-container
    select.style.display = "block";
    let divAddActivity = document.getElementById('select-container')
    divAddActivity.appendChild(div)
 
} 
