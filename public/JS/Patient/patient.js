//function permettant d'ouvrir la modale d'ajout d'un patient
function addPatient(){
    $('#add-patient-modal').modal("show");
}

//function permettant d'ouvrir la modale d'édition d'un patient
function editPatient(id, lastname, firstname) {
    //on initialise les informations affichées avec les données du patient modifié
    document.getElementById('idpatient').value = id;
    document.getElementById('lastname').value = lastname;
    document.getElementById('firstname').value = firstname;

    //on affiche la modale
    $('#edit-patient-modal').modal("show");
}

function showInfosPatient(id, lastname, firstname) {
    document.getElementById('patient-id').innerText = id;
    document.getElementById('patient-lastname').innerText = lastname;
    document.getElementById('patient-firstname').innerText = firstname;
    $('#infos-patient-modal').modal("show");

}
